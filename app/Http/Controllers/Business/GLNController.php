<?php

namespace App\Http\Controllers\Business;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\GLN;
use App\Models\Enterprise\MStaffNotification;
use App\Models\Icheck\Product\Country;
use Auth;

class GLNController extends Controller
{
    public function index()
    {
        $gln = Auth::user()->gln()->orderBy('created_at', 'desc')->get();

        return view('business.gln.index', compact('gln'));
    }

    public function add()
    {
        $countries = Country::all();

        return view('business.gln.form', compact('countries'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'gln' => 'required|digits_between:7,13|unique:gln,gln,NULL,id,status,' . GLN::STATUS_APPROVED,
            'country_id' => 'required|exists:icheck_product.country,id',
            'address' => 'required',
            'additional_info' => 'required',
            'certificate_file' => 'required|mimes:jpeg,bmp,png,pdf',
            'certificate_file2' => 'mimes:jpeg,bmp,png,pdf',
            'certificate_file3' => 'mimes:jpeg,bmp,png,pdf',
            'certificate_file4' => 'mimes:jpeg,bmp,png,pdf',
            'certificate_file5' => 'mimes:jpeg,bmp,png,pdf',
            'prefix' => 'max:13'
        ]);

        $data = $request->all();

        if ($request->hasFile('certificate_file') and $request->file('certificate_file')->isValid()) {
            $filename = $data['gln'] . '_' . md5(time() . 'f') . '.' . $request->file('certificate_file')->getClientOriginalExtension();
            $request->file('certificate_file')->move(storage_path('app/certificate_files'), $filename);
            $data['certificate_file'] = $filename;
        }

        if ($request->hasFile('certificate_file2') and $request->file('certificate_file2')->isValid()) {
            $filename = $data['gln'] . '_' . md5(time() . 'f') . '.' . $request->file('certificate_file2')->getClientOriginalExtension();
            $request->file('certificate_file2')->move(storage_path('app/certificate_files'), $filename);
            $data['certificate_file2'] = $filename;
        }

        if ($request->hasFile('certificate_file3') and $request->file('certificate_file3')->isValid()) {
            $filename = $data['gln'] . '_' . md5(time() . 'f') . '.' . $request->file('certificate_file3')->getClientOriginalExtension();
            $request->file('certificate_file3')->move(storage_path('app/certificate_files'), $filename);
            $data['certificate_file3'] = $filename;
        }

        if ($request->hasFile('certificate_file4') and $request->file('certificate_file4')->isValid()) {
            $filename = $data['gln'] . '_' . md5(time() . 'f') . '.' . $request->file('certificate_file4')->getClientOriginalExtension();
            $request->file('certificate_file4')->move(storage_path('app/certificate_files'), $filename);
            $data['certificate_file4'] = $filename;
        }

        if ($request->hasFile('certificate_file5') and $request->file('certificate_file5')->isValid()) {
            $filename = $data['gln'] . '_' . md5(time() . 'f') . '.' . $request->file('certificate_file4')->getClientOriginalExtension();
            $request->file('certificate_file4')->move(storage_path('app/certificate_files'), $filename);
            $data['certificate_file4'] = $filename;
        }

        if ($data['additional_info'] == 'other') {
            $data['additional_info'] = $data['additional_info_other'];
        }

//        if (!is_array($this->suggestInfoRaw($data['gln']))) {
//            $data['warning'] = 'GLN này chưa có trên GS1';
//        }

        $gln = GLN::create($data);
        $gln->business()->associate(Auth::user()->id);
        $gln->country()->associate($request->input('country_id'));
        $gln->status = GLN::STATUS_PENDING_ACTIVATION;
        $gln->save();

        $notification = new MStaffNotification();
        $notification->content = '<strong>' . Auth::user()->name . '</strong> đã yêu cầu thêm GLN <strong>' . $gln->gln . '</strong>';
        $notification->type = MStaffNotification::TYPE_BUSINESS_ADD_GLN;
        $notification->data = [
            'business' => Auth::user()->toArray(),
            'gln' => $gln->toArray(),
        ];
        $notification->unread = true;
        $notification->save();

        return redirect()->route('Business::gln@index')
            ->with('success', 'Đã thêm GLN' . $gln->gln);
    }

    public function suggestInfo($gln)
    {
        return response()->json($this->suggestInfoRaw($gln));
    }

    public function suggestInfoRaw($gln)
    {
        $data = @file_get_contents('http://210.86.231.43:8386/V32/client?PrivateKey=ah3869ghe3iur7g5&GLN=' . $gln);

        if (!$data) {
            return new \StdClass();
        }

        $map = array(
            '613'   => 'DZ',
            '485'   => 'AM',
            '476'   => 'AZ',
            '608'   => 'BH',
            '481'   => 'BY',
            '777'   => 'BO',
            '380'   => 'BG',
            '884'   => 'KH',
            '780'   => 'CL',
            '744'   => 'CR',
            '385'   => 'HR',
            '850'   => 'CU',
            '529'   => 'CY',
            '786'   => 'EC',
            '622'   => 'EG',
            '741'   => 'SV',
            '474'   => 'EE',
            '486'   => 'GE',
            '603'   => 'GH',
            '740'   => 'GT',
            '742'   => 'HN',
            '489'   => 'HK',
            '599'   => 'HU',
            '569'   => 'IS',
            '890'   => 'IN',
            '899'   => 'ID',
            '626'   => 'IR',
            '539'   => 'IE',
            '729'   => 'IL',
            '625'   => 'JO',
            '616'   => 'KE',
            '867'   => 'KP',
            '627'   => 'KW',
            '470'   => 'KG',
            '475'   => 'LV',
            '528'   => 'LB',
            '624'   => 'LY',
            '477'   => 'LT',
            '531'   => 'MK',
            '955'   => 'MY',
            '535'   => 'MT',
            '609'   => 'MU',
            '750'   => 'MX',
            '484'   => 'MD',
            '865'   => 'MN',
            '389'   => 'ME',
            '611'   => 'MA',
            '743'   => 'NI',
            '615'   => 'NG',
            '896'   => 'PK',
            '745'   => 'PA',
            '784'   => 'PY',
            '775'   => 'PE',
            '480'   => 'PH',
            '590'   => 'PL',
            '560'   => 'PT',
            '594'   => 'RO',
            '628'   => 'SA',
            '604'   => 'SN',
            '860'   => 'RS',
            '888'   => 'SG',
            '858'   => 'SK',
            '479'   => 'LK',
            '488'   => 'TJ',
            '620'   => 'TZ',
            '885'   => 'TH',
            '619'   => 'TN',
            '483'   => 'TM',
            '482'   => 'UA',
            '773'   => 'UY',
            '478'   => 'UZ',
            '759'   => 'VE',
            '383'   => 'SI',
            '387'   => 'BA',
            '471'   => 'TW',
            '487'   => 'KZ',
            '618'   => 'CI',
            '621'   => 'SY',
            '623'   => 'BN',
            '629'   => 'AE',
            '746'   => 'DM',
            '859'   => 'CZ',
            '880'   => 'KR',
            '893'   => 'VN',
            '958'   => 'MO'
        );

        $check = substr($gln, 0, 3);

        if (isset($map[$check])) {
            $name = $map[$check];
        } else {
            $number = intval($check);

            if ($number >= 0 && $number <= 19) {
                $name = 'US';
            }

            if ($number >= 30 && $number <= 39) {
                $name = 'US';
            }

            if ($number >= 60 && $number <= 99) {
                $name = 'US';
            }

            if ($number >= 100 && $number <= 139) {
                $name = 'US';
            }

            if ($number >= 300 && $number <= 379) {
                $name = 'FR';
            }

            if ($number >= 400 && $number <= 440) {
                $name = 'DE';
            }

            if ($number >= 450 && $number <= 459) {
                $name = 'JP';
            }

            if ($number >= 460 && $number <= 469) {
                $name = 'RU';
            }

            if ($number >= 490 && $number <= 499) {
                $name = 'JP';
            }

            if ($number >= 500 && $number <= 509) {
                $name = 'GB';
            }

            if ($number >= 520 && $number <= 521) {
                $name = 'GR';
            }

            if ($number >= 540 && $number <= 549) {
                $name = 'BE';
            }

            if ($number >= 570 && $number <= 579) {
                $name = 'DK';
            }

            if ($number >= 600 && $number <= 601) {
                $name = 'ZA';
            }

            if ($number >= 640 && $number <= 649) {
                $name = 'FI';
            }

            if ($number >= 690 && $number <= 699) {
                $name = 'CN';
            }

            if ($number >= 700 && $number <= 709) {
                $name = 'NO';
            }

            if ($number >= 730 && $number <= 739) {
                $name = 'SE';
            }

            if ($number >= 754 && $number <= 755) {
                $name = 'CA';
            }

            if ($number >= 760 && $number <= 769) {
                $name = 'CH';
            }

            if ($number >= 770 && $number <= 771) {
                $name = 'CO';
            }

            if ($number >= 778 && $number <= 779) {
                $name = 'AR';
            }


            if ($number >= 789 && $number <= 790) {
                $name = 'BR';
            }

            if ($number >= 800 && $number <= 839) {
                $name = 'IT';
            }

            if ($number >= 840 && $number <= 849) {
                $name = 'ES';
            }

            if ($number >= 868 && $number <= 869) {
                $name = 'TR';
            }

            if ($number >= 870 && $number <= 879) {
                $name = 'NL';
            }

            if ($number >= 900 && $number <= 919) {
                $name = 'AT';
            }

            if ($number >= 930 && $number <= 939) {
                $name = 'AU';
            }

            if ($number >= 940 && $number <= 949) {
                $name = 'NZ';
            }

        }

        $c = Country::where('alpha_2', $name)->first();



        $data = json_decode($data, true);
        $new = [
            'name' => $data['CompanyNameV'],
            'address' => $data['AddressV'],
            'phone' => $data['Phone'],
            'prefix' => $data['CompanyPrefix'],
            'cid' => @$c->id,
        ];

        return $new;
    }

    public function edit($id)
    {
        $gln = GLN::findOrFail($id);
        $countries = Country::all();

        return view('business.gln.form', compact('gln', 'countries'));
    }

    public function update($id, Request $request)
    {
        $gln = GLN::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:255',
            'country_id' => 'required|exists:icheck_product.country,id',
            'address' => 'required',
            'prefix' => 'max:13'
        ]);

        $data = $request->all();

        if (in_array($gln->status, [GLN::STATUS_APPROVED, GLN::STATUS_DISAPPROVED])) {
            $gln->status = GLN::STATUS_PENDING_APPROVAL;
        }

        $gln->update($data);
        $gln->country()->associate($request->input('country_id'));
        $gln->save();

        $notification = new MStaffNotification();
        $notification->content = '<strong>' . Auth::user()->name . '</strong> đã yêu cầu cập nhật thông tin của GLN <strong>' . $gln->gln . '</strong>';
        $notification->type = MStaffNotification::TYPE_BUSINESS_UPDATE_GLN;
        $notification->data = [
            'business' => Auth::user()->toArray(),
            'gln' => $gln->toArray(),
        ];
        $notification->unread = true;
        $notification->save();

        return redirect()->route('Business::gln@edit', $gln->id)
            ->with('success', 'Đã cập nhật thông tin Mã địa điểm toàn cầu');
    }

    public function delete($id)
    {
        $gln = GLN::findOrFail($id);
        $gln->status = GLN::STATUS_PENDING_DELETE;
        $gln->save();

        $notification = new MStaffNotification();
        $notification->content = '<strong>' . Auth::user()->name . '</strong> đã yêu cầu xoá GLN <strong>' . $gln->gln . '</strong>';
        $notification->type = MStaffNotification::TYPE_BUSINESS_DELETE_GLN;
        $notification->data = [
            'business' => Auth::user()->toArray(),
            'gln' => $gln->toArray(),
        ];
        $notification->unread = true;
        $notification->save();

        return redirect()->back()->with('success', 'Đã gửi yêu cầu xoá GLN');
    }
}
