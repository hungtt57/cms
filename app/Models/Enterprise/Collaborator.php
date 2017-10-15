<?php

namespace App\Models\Enterprise;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class Collaborator extends Authenticatable
{
    use HybridRelations;

    const STATUS_DEACTIVATED = 0;
    const STATUS_ACTIVATED = 1;

    public static $statusTexts = [
        self::STATUS_ACTIVATED => 'activated',
        self::STATUS_DEACTIVATED => 'deactivated',
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
        'name', 'avatar', 'address',
        'email', 'phone_number', 'password',
        'password_change_required', 'group'
    ];

    public function activatedBy()
    {
        return $this->belongsTo(User::class, 'activated_by');
    }

    public function avatar($size = 'original')
    {
        $sizes = ["original", "thumb_small", "thumb_medium", "thumb_large", "small", "medium", "large"];

        if (!in_array($size, $sizes)) {
            $size = 'original';
        }

        return 'http://ucontent.icheck.vn/' . $this->getAttribute('avatar') . '_' . $size . '.jpg';
    }
}
