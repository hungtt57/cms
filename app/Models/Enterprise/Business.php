<?php

namespace App\Models\Enterprise;


use App\Models\Icheck\Product\Country;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Icheck\Product\Product;
use App\Models\Enterprise\ProductDistributor;
use DB;
class Business extends Authenticatable
{
    const STATUS_DEACTIVATED = 0;
    const STATUS_ACTIVATED = 1;
    const STATUS_PENDING_ACTIVATION = 2;

    public static $statusTexts = [
        self::STATUS_ACTIVATED => 'activated',
        self::STATUS_DEACTIVATED => 'deactivated',
        self::STATUS_PENDING_ACTIVATION => 'pending activation',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['activated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'password_change_required' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'logo', 'address',
        'email', 'phone_number', 'fax',
        'website', 'contact_info', 'login_email',
        'password', 'password_change_required',
        'country_id','is_distributor','icheck_id','start_date','end_date',
        'manager_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function activatedBy()
    {
        return $this->belongsTo(User::class, 'activated_by');
    }

    public function gln()
    {
        return $this->hasMany(GLN::class);
    }

    public function getStatusTextAttribute($value)
    {
        return static::$statusTexts[$this->attributes['status']];
    }

    public function getIsActivatedAttribute($value)
    {
        return $this->attributes['status'] === static::STATUS_ACTIVATED;
    }

    public function getIsDeactivatedAttribute($value)
    {
        return $this->attributes['status'] === static::STATUS_DEACTIVATED;
    }

    public function getIsPendingActivationAttribute($value)
    {
        return $this->attributes['status'] === static::STATUS_PENDING_ACTIVATION;
    }

    public function logo($size = 'original')
    {
        $sizes = ["original", "thumb_small", "thumb_medium", "thumb_large", "small", "medium", "large"];

        if (!in_array($size, $sizes)) {
            $size = 'original';
        }

        return 'http://ucontent.icheck.vn/' . $this->getAttribute('logo') . '_' . $size . '.jpg';
    }

    public function productsDistributor($filter = '2',$quota = null)
    {
       if($filter==1){
           $pD = ProductDistributor::where('business_id',$this->getAttribute('id'))->where('is_first',1);
       }elseif ($filter ==0){
           $pD = ProductDistributor::where('business_id',$this->getAttribute('id'))->where('is_first',0);
       }else{
           $pD = ProductDistributor::where('business_id',$this->getAttribute('id'));
       }
        if($quota){
            $pD = $pD->where('is_quota',$quota);
        }
        $check = clone $pD;
        $check = $check->count();
        if($check){
            $pD = $pD->orderBy('is_quota','desc')->lists('product_id')->toArray();
            $referenceIdsStr = implode(',', $pD);
//            $product = Product::whereIn('id',$pD)->orderByRaw(DB::raw("FIELD(id, $referenceIdsStr)"));
            $product = Product::whereIn('id',$pD);
        }else{
            $pD = $pD->orderBy('is_quota','desc')->lists('product_id')->toArray();
            $product = Product::whereIn('id',$pD);
        }

        return $product->orderBy('updatedAt','desc');

//        return $this->belongsToMany(Product::class, env('DB_DATABASE').'.product_distributor', 'business_id', 'product_id');
    }
    public function roles()
    {
        return $this->belongsToMany(Brole::class,'business_role');
    }

    public function permissions()
    {
        return $this->belongsToMany(Bpermission::class,'business_permission')->withPivot('value');
    }
}
