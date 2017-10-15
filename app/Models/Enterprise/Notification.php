<?php

namespace App\Models\Enterprise;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Notification extends Authenticatable
{
    
    const STATUS_DEACTIVATED = 0;
    const STATUS_ACTIVATED = 1;
    
    const VIDEO = 0;
    const IMAGE = 1;
    
    const NOTIFICATION = 0;
    const POP_UP = 1;

    public static $statusPost = [
        self::STATUS_ACTIVATED => 'activated',
        self::STATUS_DEACTIVATED => 'deactivated',
    ];
    
    public static $statusObj = [
        self::NOTIFICATION => 'Notifies',
        self::POP_UP => 'Pop Up',
    ];
    
    public static $objectType = [
        self::VIDEO => 'Video',
        self::IMAGE => 'Hình ảnh',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content', 'cate', 'date_start','name',
        'date_start','status','image_video'
    ];
    
    public static function status(){
        return [
            self::STATUS_ACTIVATED => 'Kích hoạt',
            self::STATUS_DEACTIVATED => 'Không chạy',
        ];
    }
}
