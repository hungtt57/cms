<?php

namespace App\Http\Controllers\Staff\Management;

use App\Models\Enterprise\CollaboratorHistoryMoney;
use Illuminate\Http\Request;
use App\Http\Requests\Staff\Management\Collaborator\StoreCollaboratorRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\Collaborator;
use App\Models\Enterprise\Business;
use App\Models\Enterprise\GLN;


use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;
use Mail;
use App\Remote\Remote;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

use Event;
use App\Events\CollaboratorHasBeenAdded;
use App\Models\Enterprise\CollaboratorGroup;
use App\Models\Collaborator\WithdrawalHistory;
use App\Models\Collaborator\ContributeProduct;
use DB;
class CollaboratorController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-collaborator')) {
            abort(403);
        }
        $groups = CollaboratorGroup::all();
        $collaborators = Collaborator::select('*');
        if ($request->input('name')) {
            $collaborators = $collaborators->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('price')) {
            $collaborators = $collaborators->orderBy('balance', $request->input('price'));
        }
        if ($request->input('group')) {
            $collaborators = $collaborators->where('group', $request->input('group'));
        }
        $collaborators = $collaborators->paginate(20);

        return view('staff.management.collaborator.index', compact('collaborators', 'groups'));
    }

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-collaborator')) {
            abort(403);
        }
        $groups = CollaboratorGroup::all();
        return view('staff.management.collaborator.form', compact('groups'));
    }

    public function store(StoreCollaboratorRequest $request, Remote $remote)
    {
        if (auth()->guard('staff')->user()->cannot('add-collaborator')) {
            abort(403);
        }

        $data = $request->all();

        if ($request->hasFile('avatar')) {
            $client = new \GuzzleHttp\Client();

            try {
                $res = $client->request(
                    'POST',
                    'http://upload.icheck.vn/v1/images?uploadType=simple',
                    [
                        'body' => file_get_contents($request->file('avatar')),
                    ]
                );
                $res = json_decode((string)$res->getBody());
            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            $data['avatar'] = $res->prefix;
        }

        if (!($password = $request->input('password'))) {
            $password = str_random(12);
        }

        $data['password'] = bcrypt($password);

        $collaborator = Collaborator::create($data);
        $collaborator->activatedBy()->associate(auth()->guard('staff')->user()->id);
        $collaborator->activated_at = Carbon::now();
        $collaborator->status = Collaborator::STATUS_ACTIVATED;
        $collaborator->save();

//        Event::fire(new CollaboratorHasBeenAdded($collaborator, $password));

        return redirect()->route('Staff::Management::collaborator@index')
            ->with('success', 'Đã thêm Cộng tác viên ' . $collaborator->name);
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
        if (auth()->guard('staff')->user()->cannot('list-collaborator')) {
            abort(403);
        }
        $collaborator = Collaborator::findOrFail($id);

        return view('staff.management.collaborator.show', compact('business'));
    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-collaborator')) {
            abort(403);
        }
        $collaborator = Collaborator::findOrFail($id);
        $groups = CollaboratorGroup::all();
        return view('staff.management.collaborator.form', compact('collaborator', 'groups'));
    }

    public function update($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-collaborator')) {
            abort(403);
        }
        $collaborator = Collaborator::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:255',
            'avatar' => 'image',
            'address' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'password' => 'min:6|confirmed',
        ]);

        $data = $request->all();

        if ($request->hasFile('avatar')) {
            $client = new \GuzzleHttp\Client();

            try {
                $res = $client->request(
                    'POST',
                    'http://upload.icheck.vn/v1/images?uploadType=simple',
                    [
                        'body' => file_get_contents($request->file('avatar')),
                    ]
                );
                $res = json_decode((string)$res->getBody());
            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            $data['avatar'] = $res->prefix;
        }

        if (!$request->input('password')) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $collaborator->update($data);

        return redirect()->route('Staff::Management::collaborator@edit', $collaborator->id)
            ->with('success', 'Đã cập nhật thông tin CTV');
    }

    public function approve($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-collaborator')) {
            abort(403);
        }
        $business = Business::findOrFail($id);

        $this->validate($request, [
            'login_email' => 'required|email|unique:businesses,login_email',
            'password' => 'min:6|confirmed',
        ]);

        $gln = $business->gln->first();
        $duplicatedGln = GLN::where('gln', $gln->gln)
            ->where('status', GLN::STATUS_APPROVED)
            ->whereHas('business', function ($query) use ($business) {
                $query->where('id', '!=', $business->id);
            })
            ->first();

        if (!is_null($duplicatedGln)) {
            return redirect()->back()
                ->withErrors(['gln' => 'Mã địa điểm toàn cầu (GLN) ' . $gln->gln . ' đã được đăng ký bởi một doanh nghiệp khác.'])
                ->withInput();
        }

        $gln->status = GLN::STATUS_APPROVED;
        $gln->save();

        if (!($password = $request->input('password'))) {
            $password = str_random(12);
        }

        $business->login_email = $request->input('login_email');
        $business->password = bcrypt($password);
        $business->activatedBy()->associate(auth()->guard('staff')->user()->id);
        $business->activated_at = Carbon::now();
        $business->status = Business::STATUS_ACTIVATED;
        $business->save();

        $this->sendLoginInfoEmail($business, $password);

        return redirect()->route('Staff::Management::business@show', $business->id)
            ->with('success', 'Kích hoạt thành công tài khoản cho doanh nghiệp');
    }

    public function delete($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-collaborator')) {
            abort(403);
        }
        $collaborator = Collaborator::findOrFail($id);
        $collaborator->delete();
        return redirect()->route('Staff::Management::collaborator@index')
            ->with('success', 'Đã xoá CTV');
    }

    public function deleteList(Request $request)
    {
        $ids = $request->input('selected');
        Collaborator::whereIn('id', $ids)->delete();
        return redirect()->route('Staff::Management::collaborator@index')
            ->with('success', 'Đã xóa Cộng tác viên ');
    }

    public function withdrawMoney($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-collaborator')) {
            abort(403);
        }
        $money = $request->input('money');
        $money = intval($money);
        $collaborator = Collaborator::findOrFail($id);
        if($money == 0 or $collaborator->balance < $money){
            return redirect()->back()->with('error','Vui lòng nhập số tiền muốn rút nhỏ hơn hoặc bằng số hiện có');
        }
        $collaborator->balance =  $collaborator->balance - $money;
        $collaborator->save();
        $data = [
            'collaborator_id' => $collaborator->id,
            'money' => $money,
            'withdrawal_by' => auth()->guard('staff')->user()->email
        ];
        WithdrawalHistory::create($data);

        return redirect()->back()
            ->with('success', 'Đã rút '.$money.' tiền của ' . $collaborator->name);
    }

    public function listGroup()
    {
        if (auth()->guard('staff')->user()->cannot('collaborators-list-group')) {
            abort(403);
        }
        $groups = CollaboratorGroup::all();
        return view('staff.management.collaborator.listGroup', compact('groups'));
    }

    public function addGroup()
    {
        return view('staff.management.collaborator.formGroup');
    }

    public function storeGroup(Request $request)
    {
        $this->validate($request, [
            'group_id' => 'required|unique:collaborator_group'
        ]);
        $group = new CollaboratorGroup();
        $group->group_id = $request->input('group_id');
        $group->save();
        return redirect()->route('Staff::Management::collaborator@listGroup')
            ->with('success', 'Đã thêm group ' . $group->group_id);

    }

    public function editGroup(Request $request, $id)
    {
        $group = CollaboratorGroup::find($id);
        return view('staff.management.collaborator.formGroup', compact('group'));
    }

    public function updateGroup(Request $request, $id)
    {
        $group = CollaboratorGroup::find($id);
        $oldgroup = $group->group_id;
        $new = $request->input('group_id');
        if($oldgroup != $new){
            $group->update(['group_id' => $request->input('group_id')]);
            Collaborator::where('group',$oldgroup)->update(['group' => $request->input('group_id')]);
            ContributeProduct::where('group',$oldgroup)->update(['group' => $request->input('group_id')]);
        }

        return redirect()->route('Staff::Management::collaborator@listGroup')
            ->with('success', 'Đã sửa group ' . $group->group_id);
    }

    //history
    public function history(Request $request)
    {

        if (auth()->guard('staff')->user()->cannot('collaborators-history')) {
            abort(403);
        }
        if (!$request->has('date_range')) {
            $startDate = Carbon::now()->subDays(30)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        } else {
            $dateRange = explode('_', $request->input('date_range'));
            $startDate = Carbon::createFromTimestamp($dateRange[0]);
            $endDate = Carbon::createFromTimestamp($dateRange[1]);
        }
        $groups = CollaboratorGroup::all();
//        $histories = CollaboratorHistoryMoney::whereDate('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->orderBy('created_at', 'desc');
//        if ($request->input('group')) {
//            $histories = $histories->where('group_id', $request->input('group'));
//        }
//        if ($request->input('name')) {
//            $name = $request->input('name');
//            $histories = $histories->whereHas('collaborator', function ($query) use ($name) {
//                $query->where('name', 'like', '%' . $name . '%');
//            });
//        }
//        $histories = $histories->paginate(50);
        $histories =  CollaboratorHistoryMoney::select(\DB::raw('SUM(money) as money, collaborator_id,group_id'))
            ->where('date','<=',$endDate)->where('date','>=',$startDate)
            ->groupBy('collaborator_id')->orderBy('date','asc');
        if($request->input('name')){
            $name = $request->input('name');
            $histories = $histories->whereHas('collaborator', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }
        if($request->input('group')){
            $histories = $histories->where('group_id',$request->input('group'));
        }
        $histories = $histories->get();
        return view('staff.management.collaborator.history', compact('startDate', 'endDate', 'groups', 'histories'));

    }
    public function deleteGroup(Request $request,$id){
        if (auth()->guard('staff')->user()->cannot('collaborators-delete-group')) {
            abort(403);
        }
        $group = CollaboratorGroup::find($id);
        $group->delete();
        return redirect()->back()->with('success','Xóa thành công');
    }
    public function changeGroup(Request $request){
        if (auth()->guard('staff')->user()->cannot('collaborator-change-group')) {
            abort(403);
        }
        $data = $request->all();
        $group = $data['group'];
        $ids = $data['ids'];
        if($ids){
            $ids = explode(',',$ids);
            $array_id =[];
            foreach ($ids as $id){
                $array_id[] = intval($id);
            }
            DB::beginTransaction();
            try{
                Collaborator::whereIn('id',$ids)->update(['group' => $group]);
                ContributeProduct::whereIn('contributorId',$array_id)->update(['group' => $group]);
                DB::commit();
                return redirect()->back()->with('success','Thay đổi group thành công');
            }catch (\Exception $ex){
                DB::rollback();
            }
        }else{
            return redirect()->back()->with('error','Vui lòng chọn CTV');
        }

        return redirect()->back()->with('error','Có lỗi xảy ra ! vui lòng thử lại sau');


    }
}
