<?php

namespace App\Http\Controllers\Staff\Management;

use Illuminate\Http\Request;
use App\Http\Requests\Staff\Management\Business\StoreBusinessRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\Business;
use App\Models\Enterprise\GLN;
use App\Models\Enterprise\Product;

use App\Models\Enterprise\ProductCategory;

use App\Models\Icheck\Product\Product as Product2;
use App\Models\Icheck\Product\Country;
use App\Models\Icheck\Product\ProductAttr;
use App\Models\Icheck\Product\ProductInfo;
use App\Models\Enterprise\ProductDistributor;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;
use Mail;
use App\Remote\Remote;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Event;
use App\Events\BusinessHasBeenApproved;
use App\Events\BusinessWasDisapproved;
use App\Models\Enterprise\Brole;
use App\Models\Icheck\User\Account;
use App\Models\Enterprise\Bpermission;
use App\Models\Enterprise\Role;
class BusinessController extends Controller
{
    public function getMap()
    {
        return '<form method="POST">' . csrf_field() . '<input type="text" name="gln" /><textarea name="gtin"></textarea><button type="submit">Import</button></form>';
    }

    public function postMap(Request $request)
    {

        $gln = GLN::where('gln', $request->input('gln'))->firstOrFail();
        $gtin = preg_split("/\\r\\n|\\r|\\n/", $request->input('gtin'));

        $gtin = array_map(function ($g) {
            return trim(str_replace(' ', '', $g));
        }, $gtin);



        $products = Product2::whereIn('gtin_code', $gtin)->get();
        $attrs = ProductAttr::lists('id')->toArray();

        foreach ($products as $product) {
            $newProduct = Product::firstOrCreate([
                'barcode' => $product->gtin_code,
            ]);

            $data = [
                'name' => $product->product_name,
                'image' => $product->image_default,
                'price' => $product->price_default,
                'status' => Product::STATUS_APPROVED,
            ];

            $infos = ProductInfo::whereIn('attribute_id', $attrs)->where('product_id', $product->id)->get();
            $infos = $infos->lists('content', 'attribute_id')->toArray();

            $data['attrs'] = $infos;

            $newProduct->update($data);
            $newProduct->gln()->associate($gln);

            foreach ($product->categories()->get() as $cat) {
                ProductCategory::firstOrCreate(['product_id' => $newProduct->id, 'category_id' => $cat->id]);
            }

            $newProduct->save();
        }

        return 'ok';
    }


    public function index()
    {
        if (auth()->guard('staff')->user()->cannot('list-business')) {
            abort(403);
        }
        $roles = Brole::all();
        return view('staff.management.business.index', compact('businesses','roles'));
    }

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-business')) {
            abort(403);
        }
        $countries = Country::all();
        $roles = Brole::all();
        $permission = Bpermission::all();
        $role_sales = Role::where('name','Like','SALES')->first();
        $managers = $role_sales->users;
        return view('staff.management.business.form', compact('countries','roles','permission','managers'));
    }

    public function store(StoreBusinessRequest $request, Remote $remote)
    {
        if (auth()->guard('staff')->user()->cannot('add-business')) {
            abort(403);
        }
        $data = $request->all();

//        $this->validate($request, [
//            'start_date' => 'required',
//            'end_date' => 'required'
//
//        ]);

        if ($request->hasFile('logo')) {
            $client = new \GuzzleHttp\Client();

            try {
                $res = $client->request(
                    'POST',
                    'http://upload.icheck.vn/v1/images?uploadType=simple',
                    [
                        'body' => file_get_contents($request->file('logo')),
                    ]
                );
                $res = json_decode((string) $res->getBody());
            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            $data['logo'] = $res->prefix;
        }

        if (!($password = $request->input('password'))) {
            $password = str_random(12);
        }

        $data['password'] = bcrypt($password);

        if($data['icheck_id']!=''){

            $icheck_id = $data['icheck_id'];
            $account = Account::where('icheck_id',$icheck_id)->first();
            if(empty($account)){
                return redirect()->back()->withInput()->with('error','Icheck id không tồn tại!');
            }
            $count = Business::where('icheck_id',$icheck_id)->count();

            if($count > 0){
                return redirect()->back()->withInput()->with('error','Icheck id đã được doanh nghiệp khác đăng kí!');
            }
            $verification_type = 1;
            $account->verification_type = $verification_type;
            $account->save();
            $client = new \GuzzleHttp\Client();
            try {
                $res = $client->request('PUT',env('DOMAIN_API') .'users/' . $account->id, [
                    'auth' => [env('USER_API'), env('PASS_API')],
                    'form_params' => [
                        'verification_type' => $verification_type
                    ],
                ]);
                $res = json_decode((string)$res->getBody());
                if ($res->status != 200) {
                    return redirect()->back()->withInput()->with('error', 'Server bị lỗi khi update Account! Vui lòng thử lại sau');
                }

            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Server bị lỗi khi update Account! Vui lòng thử lại sau');
            }

        }

        if( $data['start_date']){
            $data['start_date'] =  Carbon::createFromTimestamp(strtotime(  $data['start_date']));
        }
        if($data['end_date'] ){
            $data['end_date'] =  Carbon::createFromTimestamp(strtotime(  $data['end_date']));
        }


        $business = Business::create($data);
        $business->country()->associate($data['country_id']);
        $business->activatedBy()->associate(auth()->guard('staff')->user()->id);
        $business->activated_at = Carbon::now();
        $business->status = Business::STATUS_PENDING_ACTIVATION;
        $business->save();
        if( $data['start_date'] and $data['end_date']) {
            $business->roles()->sync($request->input('role', []));
        }


        $permissions = [];
        foreach($request->input('status',[]) as $permissionId => $value) {
            $permissions[$permissionId] = ['value' => $value];
        }

        $business->permissions()->sync($permissions);
        if ($data['gln']) {
            $gln = GLN::create($data);
            $gln->business()->associate($business);
            $gln->country()->associate($data['country_id']);
            $gln->address = $data['address'];
            $gln->status = GLN::STATUS_PENDING_ACTIVATION;
            $gln->save();
        }

        return redirect()->route('Staff::Management::business@show', [$business->id])
            ->with('success', 'Đã thêm Doanh nghiệp');
    }

    public function sendLoginInfoEmail($business, $password)
    {
        Mail::raw('Mật khẩu của bé là: ' . $password,
            function ($message) use ($business) {
                $message->from('business@icheck.vn', 'iCheck cho doanh nghiệp');
                $message->to($business->login_email, $business->name);
                $message->subject('Thông tin đăng nhập iCheck cho doanh nghiệp');
            }
        );
    }

    public function show($id)
    {
        if (auth()->guard('staff')->user()->cannot('list-business')) {
            abort(403);
        }
        $business = Business::findOrFail($id);

        return view('staff.management.business.show', compact('business'));
    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-business')) {
            abort(403);
        }
        $business = Business::findOrFail($id);
        $countries = Country::all();
        $roles = Brole::all();
        $userRoles = $business->roles->keyBy('id');
        $userPermissions = $business->permissions->keyBy('id');
        $permission = Bpermission::all();

        $role_sales = Role::where('name','Like','SALES')->first();
        $managers = $role_sales->users;

        return view('staff.management.business.form', compact('managers','business', 'countries','roles','userRoles','userPermissions','permission'));
    }

    public function update($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-business')) {
            abort(403);
        }

        $business = Business::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:255',
            'logo' => 'image',
            'country_id' => 'required|exists:icheck_product.country,id',
            'address' => 'required',
            'email' => 'required|email',
            'password' => 'min:6|confirmed',
        ]);

        $data = $request->all();
        if ($request->hasFile('logo')) {
            $client = new \GuzzleHttp\Client();

            try {
                $res = $client->request(
                    'POST',
                    'http://upload.icheck.vn/v1/images?uploadType=simple',
                    [
                        'body' => file_get_contents($request->file('logo')),
                    ]
                );
                $res = json_decode((string) $res->getBody());
            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            $data['logo'] = $res->prefix;
        }

        if (!$request->input('password')) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        if( $data['start_date']){
            $data['start_date'] =  Carbon::createFromTimestamp(strtotime(  $data['start_date']));
        }
        if($data['end_date'] ){
            $data['end_date'] =  Carbon::createFromTimestamp(strtotime(  $data['end_date']));
        }

        if($data['icheck_id']!='' and $data['icheck_id'] != $business->icheck_id){
            $icheck_id = $data['icheck_id'];
            $account = Account::where('icheck_id',$icheck_id)->first();
            if(empty($account)){
                return redirect()->back()->withInput()->with('error','Icheck id không tồn tại!');
            }
            $count = Business::where('icheck_id',$icheck_id)->count();

            if($count > 0){
                return redirect()->back()->withInput()->with('error','Icheck id đã được doanh nghiệp khác đăng kí!');
            }

            $verification_type = 1;
            $account->verification_type = $verification_type;
            $account->save();
            $client = new \GuzzleHttp\Client();

            try {
                $res = $client->request('PUT',env('DOMAIN_API') .'users/' . $account->id, [
                    'auth' => [env('USER_API'), env('PASS_API')],
                    'form_params' => [
                        'verification_type' => $verification_type
                    ],
                ]);
                $res = json_decode((string)$res->getBody());

                if ($res->status != 200) {
                    return redirect()->back()->withInput()->with('error', 'Server bị lỗi khi update Account! Vui lòng thử lại sau');
                }

            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Server bị lỗi khi update Account! Vui lòng thử lại sau');
            }


            $icheck_id2 = $business->icheck_id;

            $account2 = Account::where('icheck_id',$icheck_id2)->first();
            if($account2){
                $verification_type = 0;
                $account2->verification_type = $verification_type;
                $account2->save();

                $client = new \GuzzleHttp\Client();
                try {
                    $res = $client->request('PUT',env('DOMAIN_API') .'users/' . $account2->id, [
                        'auth' => [env('USER_API'), env('PASS_API')],
                        'form_params' => [
                            'verification_type' => $verification_type
                        ],
                    ]);

                    $res = json_decode((string)$res->getBody());

                    if ($res->status != 200) {
                        return redirect()->back()->withInput()->with('error', 'Server bị lỗi khi update Account! Vui lòng thử lại sau');
                    }

                } catch (\Exception $e) {
                    return redirect()->back()->withInput()->with('error', 'Server bị lỗi khi update Account! Vui lòng thử lại sau');
                }
            }

            
        }
        if($data['icheck_id'] == '' and $business->icheck_id !=''){
            $icheck_id = $business->icheck_id;
            $account = Account::where('icheck_id',$icheck_id)->first();
            $verification_type = 0;
            $account->verification_type = $verification_type;
            $account->save();
            $client = new \GuzzleHttp\Client();
            try {
                $res = $client->request('PUT',env('DOMAIN_API') .'users/' . $account->id, [
                    'auth' => [env('USER_API'), env('PASS_API')],
                    'form_params' => [
                        'verification_type' => $verification_type
                    ],
                ]);

                $res = json_decode((string)$res->getBody());

                if ($res->status != 200) {
                    return redirect()->back()->withInput()->with('error', 'Server bị lỗi khi update Account! Vui lòng thử lại sau');
                }

            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Server bị lỗi khi update Account! Vui lòng thử lại sau');
            }

        }

        $business->update($data);
        if( $data['start_date'] and $data['end_date']) {
            $business->roles()->sync($request->input('role', []));
        }
        $permissions = [];
        foreach($request->input('status',[]) as $permissionId => $value) {
            $permissions[$permissionId] = ['value' => $value];
        }
        $business->permissions()->sync($permissions);

        return redirect()->route('Staff::Management::business@index')
            ->with('success', 'Đã cập nhật thông tin Nhà sản xuất');
    }

    public function approve($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('approve-business')) {
            abort(403);
        }
        $business = Business::findOrFail($id);
        $this->validate($request, [
            'login_email' => 'required|email|unique:businesses,login_email,' . $business->id,
            'password' => 'min:6|confirmed',
        ]);

//        $gln = $business->gln->first();
//        if(is_null($gln)){
//            return redirect()->back()
//                ->withErrors(['Mã địa điểm toàn cầu (GLN) bị rỗng'])
//                ->withInput();
//        }
//        $duplicatedGln = GLN::where('gln', $gln->gln)
//            ->where('status', GLN::STATUS_APPROVED)
//            ->whereHas('business', function ($query) use ($business) {
//                $query->where('id', '!=', $business->id);
//            })
//            ->first()
//        ;
//        if (!is_null($duplicatedGln)) {
//            return redirect()->back()
//                ->withErrors(['gln' => 'Mã địa điểm toàn cầu (GLN) ' . $gln->gln . ' đã được đăng ký bởi một doanh nghiệp khác.'])
//                ->withInput()
//            ;
//        }
//
//        $gln->status = GLN::STATUS_APPROVED;
//        $gln->save();

        if (!($password = $request->input('password'))) {
            $password = '123456';
        }

        $business->login_email = $request->input('login_email');
        $business->password = bcrypt($password);
        $business->activatedBy()->associate(auth()->guard('staff')->user()->id);
        $business->activated_at = Carbon::now();
        $business->status = Business::STATUS_ACTIVATED;
        $business->save();

        Event::fire(new BusinessHasBeenApproved($business, $password));

        return redirect()->route('Staff::Management::business@show', $business->id)
            ->with('success', 'Kích hoạt thành công tài khoản cho doanh nghiệp');
    }

    public function disapprove($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('approve-business')) {
            abort(403);
        }
        $this->validate($request, [
            'reason' => 'required',
        ]);

        $business = Business::where('id', $id)
            ->where('status', Business::STATUS_PENDING_ACTIVATION)
            ->firstOrFail()
        ;

        $reason = $request->input('reason');
//        $gln = $business->gln()->delete();
        $business->status = Business::STATUS_DEACTIVATED;
        $business->save();
        Event::fire(new BusinessWasDisapproved($business, $reason));

        return redirect()->route('Staff::Management::business@index')
            ->with('success', 'Đã từ chối yêu cầu đăng ký của ' . $business->name);
    }

    public function batchDisapprove(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-business')) {
            abort(403);
        }
        $this->validate($request, [
            'reason' => 'required',
        ]);

        $ids = explode(',', $request->input('ids'));

        $businesses = Business::where('status', Business::STATUS_PENDING_ACTIVATION)
            ->whereIn('id', $ids)
            ->get()
        ;

        if ($businesses->count() <= 0 or $businesses->count() > 20) {
            return redirect()->back()
                ->withErrors(['Không thể thực hiện hành động này'], 'batch');
        }

        $reason = $request->input('reason');

        foreach ($businesses as $business) {
            $gln = $business->gln()->delete();
            //$business->delete();
            Event::fire(new BusinessWasDisapproved($business, $reason));
        }

        return redirect()->back()
            ->with('success', 'Đã từ chối yêu cầu đăng ký của ' . count($ids) . ' doanh nghiệp');
    }

    public function batchActivate(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-business')) {
            abort(403);
        }
        $ids = explode(',', $request->input('ids'));

        $updatedCount = Business::where('status', Business::STATUS_DEACTIVATED)
            ->whereIn('id', $ids)
            ->update([
                'status' => Business::STATUS_ACTIVATED
            ])
        ;

        if ($updatedCount <= 0) {
            return redirect()->back()
                ->withErrors(['Không thể thực hiện hành động này'], 'batch');
        }

        return redirect()->back()
            ->with('success', 'Đã kích hoạt tài khoản cho ' . count($ids) . ' doanh nghiệp');
    }

    public function batchDeactivate(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-business')) {
            abort(403);
        }
        $ids = explode(',', $request->input('ids'));

        $updatedCount = Business::where('status', Business::STATUS_ACTIVATED)
            ->whereIn('id', $ids)
            ->update([
                'status' => Business::STATUS_DEACTIVATED
            ])
        ;

        if ($updatedCount <= 0) {
            return redirect()->back()
                ->withErrors(['Không thể thực hiện hành động này'], 'batch');
        }

        return redirect()->back()
            ->with('success', 'Đã huỷ kích hoạt tài khoản của ' . count($ids) . ' doanh nghiệp');
    }

    public function batchDelete(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-business')) {
            abort(403);
        }
        $ids = explode(',', $request->input('ids'));
        $businesses = Business::whereIn('id',$ids)->get();


        foreach ($businesses as $business) {
            $gln = $business->gln()->delete();
            $business->delete();
        }
        return redirect()->back()
            ->with('success', 'Đã xoá ' . count($ids) . ' doanh nghiệp');
    }
    public function delete($id,Request $request){
        if (auth()->guard('staff')->user()->cannot('edit-business')) {
            abort(403);
        }
        $business = Business::find($id);
        $gln = $business->gln()->delete();
        // auto change permission when delete business
        $pDs = ProductDistributor::where('business_id',$business->id)->where('is_first',1)->get();
        foreach ($pDs as $pD){
            $p = ProductDistributor::where('product_id',$pD->product_id)->where('id','!=',$pD->id)->first();
            if($p){
                $p->is_first = 1;
                $p->save();
            }
        }
        ProductDistributor::where('business_id',$business->id)->delete();
        $business->delete();

        return redirect()->back()
            ->with('success', 'Đã xoá doanh nghiệp');
    }
}
