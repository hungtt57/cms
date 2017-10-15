<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\Enterprise\Business;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Models\Enterprise\GLN;
use App\Models\Enterprise\MStaffNotification;


//use App\Models\Social\Country;
use App\Models\Icheck\Product\Country;
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $username = 'login_email';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $countries = Country::all();

        if (property_exists($this, 'registerView')) {
            return view($this->registerView, compact('countries'));
        }

        return view('auth.register', compact('countries'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $business = $this->create($request->all());

        return redirect()->route('Business::successfullyRegistered')
            ->with('business', $business);
    }

    public function successfullyRegistered()
    {
        return view('auth.successfully_registered');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'gln' => 'digits_between:7,10|integer|unique:gln,gln,NULL,id,status,' . GLN::STATUS_APPROVED,
            'country_id' => 'required|exists:social.country,id',
            'address' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'tos' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        if ($data['gln']) {
            $gln = new GLN();
            $gln->name = $data['name'];
            $gln->gln = $data['gln'];
            $gln->country()->associate($data['country_id']);
            $gln->address = $data['address'];
            $gln->status = GLN::STATUS_PENDING_ACTIVATION;
        }

        $business = new Business();
        $business->name = $data['name'];
        $business->country()->associate($data['country_id']);
        $business->address = $data['address'];
        $business->email = $data['email'];
        $business->phone_number = $data['phone_number'];
        $business->fax = $data['fax'];
        $business->website = $data['website'];
        $business->status = Business::STATUS_PENDING_ACTIVATION;
        $business->save();

        if ($data['gln']) {
            $business->gln()->save($gln);
        }

        $notification = new MStaffNotification();
        $notification->content = 'Có yêu cầu đăng ký doanh nghiệp mới từ <strong>' . $business->name . '</strong>';
        $notification->type = MStaffNotification::TYPE_BUSINESS_REGISTERED;
        $notification->data = [
            'business' => $business->toArray(),
        ];
        $notification->unread = true;
        $notification->save();

        return $business;
    }
}
