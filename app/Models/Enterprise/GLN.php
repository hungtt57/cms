<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;

use App\Models\Icheck\Product\Country;
class GLN extends Model
{
    protected $table = 'gln';

    const STATUS_DISAPPROVED = 0;
    const STATUS_APPROVED = 1;
    const STATUS_PENDING_APPROVAL = 2;
    const STATUS_PENDING_DELETE = 3;
    const STATUS_PENDING_ACTIVATION = 4;

    public static $statusTexts = [
        self::STATUS_DISAPPROVED => 'disapproved',
        self::STATUS_APPROVED => 'approved',
        self::STATUS_PENDING_APPROVAL => 'pending approval',
        self::STATUS_PENDING_DELETE => 'pending delete',
        self::STATUS_PENDING_ACTIVATION => 'pending activation',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'gln', 'address',
        'email', 'phone_number', 'fax',
        'website', 'contact_info',
        'additional_info', 'certificate_file',
        'certificate_file2','certificate_file3','certificate_file4','certificate_file5',
        'warning','prefix'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function getStatusTextAttribute($value)
    {
        return static::$statusTexts[$this->attributes['status']];
    }
}
