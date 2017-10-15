<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Product;

class DNNotificationUser extends Model
{

    const STATUS_PENDING = 0;
    const STATUS_FINISH = 2;
    const STATUS_REJECT = 1;
    const STATUS_APPROVE = 3;

    public static $statusTexts = [
        self::STATUS_PENDING => 'Đang chờ duyệt',
        self::STATUS_REJECT => 'Không được duyệt',
        self::STATUS_FINISH => 'Hoàn thành',
        self::STATUS_APPROVE => 'Đã được duyệt',


    ];

    public static $typeObjectTo = [
        1 => 'Sản phẩm',
        2 => 'Link',
        3 => 'Post'
    ];
    public static $typeSend = [
        1 => 'Gửi luôn',
        2 => 'Đặt lịch',
    ];
//    public static $checkProduct = [
//        1 => 'Toàn bộ sản phẩm',
//        2 => 'Danh sách sản phẩm',
//    ];
    protected $table = 'dn_notification_user';

    protected $fillable = ['content','type_object_to','object_to','type_send','time_send',
        'business_id','status',
        'comment_product','like_product','scan_product',
        'check_product','list_barcode'
    ];
    public function business(){
        return $this->hasOne(Business::class,'id','business_id');
    }

}
