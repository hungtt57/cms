<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Product;

class DNQuestion extends Model
{

    const STATUS_PENDING = 0;
    const STATUS_ANSWERED = 1;
    const STATUS_REQUIRE_BUSINESS = 2;
    const STATUS_APPROVE = 3;


    public static $statusTexts = [
        self::STATUS_PENDING => 'Yêu cầu trả lời',
        self::STATUS_ANSWERED => 'Đã trả lời',
        self::STATUS_REQUIRE_BUSINESS => 'Yêu cầu doanh nghiệp trả lời',
        self::STATUS_APPROVE => 'Hoàn thành',

    ];

    public static $rooms = [
       1 => 'Chăm sóc khách hàng',
        2 => 'Bộ phận data',
        3 => 'Bộ phận kĩ thuật',
        4 => 'Bộ phận kế toán'
    ];

    public static $services = [
        1 => 'Thông tin thương phẩm',
        2 => 'Dịch vụ bán hàng trên icheck',
        3 => 'Dịch vụ Affiliate',
        4 => 'Dịch vụ QRCode'
    ];

    protected $table = 'dn_questions';

    protected $fillable = ['room', 'service','title',
    'content','attachments','status','code','business_id'
    ];
    public function business(){
        return $this->hasOne(Business::class,'id','business_id');
    }

}
