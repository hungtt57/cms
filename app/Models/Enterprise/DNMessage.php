<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Product;

class DNMessage extends Model
{

    const STATUS_PENDING = 0;
    const STATUS_ORDER = 1;
    const STATUS_FINISH = 2;

    public static $statusTexts = [
        self::STATUS_PENDING => 'Đang xử lý',
        self::STATUS_ORDER => 'Đặt lịch',
        self::STATUS_FINISH => 'Hoàn thành',


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
    protected $table = 'dn_message';

    protected $fillable = ['content','type_object_to','object_to','type_send','time_send',
        'business_id','status',
        'comment_product','like_product','scan_product',
        'check_product','list_barcode'
    ];
    public function business(){
        return $this->hasOne(Business::class,'id','business_id');
    }

}
