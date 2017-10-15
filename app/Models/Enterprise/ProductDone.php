<?php
namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
//use App\Models\Social\Category;
use App\Models\Icheck\Product\Category;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProductDone
 *
 * @author admin
 */
class ProductDone  extends Model {
    
    const STATUS_DISAPPROVED = 7;
    const STATUS_APPROVED = 1;
    const STATUS_PENDING_APPROVAL = 2;
    const STATUS_IN_PROGRESS = 3;
    const STATUS_ERROR = 4;
    const STATUS_NEED = 5;
    const CRAWLER = 6;

    public static $statusTexts = [
        self::STATUS_DISAPPROVED => 'Không chấp nhận',
        self::STATUS_APPROVED => 'Hoàn thành',
        self::STATUS_PENDING_APPROVAL => 'Đang chờ duyệt',
        self::STATUS_IN_PROGRESS => 'Đang hoàn thiện',
        self::STATUS_ERROR => 'Lỗi',
        self::STATUS_NEED => 'Cần sửa',
        self::CRAWLER => 'Cần Craler'
    ];
    
    protected $table = 'product_assign_done';
    protected $connection = 'mysql';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['gtin','price','product_price', 'status','user_id', 'user_name', 'product_name','product_image','product_caegories'];
        
    public static function get_image_url($prefix, $size = 'original') {
        if (!$prefix) {
            return;
        }

        if (app('url')->isValidUrl($prefix)) {
            return $prefix;
        }

        $sizes = ["original", "thumb_small", "thumb_medium", "thumb_large", "small", "medium", "large"];

        if (!in_array($size, $sizes)) {
            $size = 'original';
        }

        return 'http://ucontent.icheck.vn/' . $prefix . '_' . $size . '.jpg';
    }
    
    public static function getCate($id)
    {
        $cateName = \App\Models\Enterprise\ProductCategory::
            where('product_id',$id)
            ->get()->keyBy('category_id')
            ->toArray();
        $categories = Category::all()->groupBy('parent_id');
        $categories = static::r($categories, 12);
        $cateAll = [];
        if($categories) {
            foreach ($categories as $k) {
                $cateAll[$k->id] = $k->name;
            }
        }
        $name = '';
        foreach (array_keys($cateName) as $k) {
            if(isset($cateAll[$k])) $name .= $cateAll[$k].'<br>';
        }
        return $name;
    }
    
    public static function r($data, $parent = 0, $level = 0)
    {
        $list = [];
        if (isset($data[$parent])) {
            foreach ($data[$parent] as $cat) {
                $cat->level = $level;
                $list[] = $cat;

                foreach (static::r($data, $cat['id'], $level + 1) as $subCat) {
                    $list[] = $subCat;
                }
            }
        }

        return $list;
    }
}
