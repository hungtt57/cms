<?php

namespace App\Http\Controllers\Ajax\Staff\Management;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;
use Mail;
use App\Remote\Remote;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\ProductTransformer;
use App\Models\Icheck\Product\AttrDynamic;
use App\Models\Icheck\Product\Category;
use Response;
use App\Models\Enterprise\ProductDistributorTemp;
class BusinessDistributorController extends Controller
{
    public function addAttrInline(Request $request){
        $category_id = $request->input('id');
        $product_id =  $request->input('product_id');
        $category = Category::find($category_id);
        $result = [];
        if ($category) {
            $attributes = $category->attributes;
            if ($attributes) {
                $attributes = explode(',', $attributes);
                $attrs = AttrDynamic::whereIn('id', $attributes)->get();
                foreach ($attrs as $attr) {
                    $result[$attr->id] = $this->templateInline($attr,$product_id);
                }
            }
        }
        return json_encode($result);
    }
    public function templateInline($attr,$product_id)
    {
        $string = null;
        if($attr->enum){
            if(trim($attr->type) == 'single'){
                $value = $attr->enum;
                $value = explode(',',$value);
                $s = '';
                foreach ($value as $v){
                    $s .=   '<option value="'.$v.'">'.$v.'</option>';
                }
                $string = '<div class="row"  id="'.$product_id.$attr->id.'" data-count="1" data-id="'.$attr->id.'"><div class="col-md-6"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-6">
                        <select class="select-border-color border-warning js-attr  properties-product">
                            '.$s.'</select> </div> </div>
                       ';
            }
            if(trim($attr->type) == 'multiple'){
                $value = $attr->enum;
                $value = explode(',',$value);
                $s = '';
                foreach ($value as $v){
                    $s .=   '<option value="'.$v.'">'.$v.'</option>';
                }
                $string = '<div class="row"  id="'.$product_id.$attr->id.'" data-count="1" data-id="'.$attr->id.'"><div class="col-md-6"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-6">
                        <select  class="select-border-color border-warning js-attr  properties-product"  multiple="multiple">
                                                   '.$s.'
                                                    </select>
                                                </div>
                                            </div>
                       ';
            }
        }else{
            $string = '<div class="row"  id="'.$product_id.$attr->id.'" data-count="1" data-id="'.$attr->id.'"><div class="col-md-6"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-6">
                        <input  maxlength="25" type="text" class="form-control properties-product">
                 
                                                </div>
                                            </div>
                       ';
        }

        return $string;
    }
    public function updateAttrInline(Request $request){
        $product_id = $request->input('product_id');
        $attr_id  = $request->input('attr_id');
        $value = $request->input('value');
        $product = ProductDistributorTemp::find($product_id);
        if(empty($product) || $product->status != ProductDistributorTemp::STATUS_PENDING_APPROVAL){
            return Response::json(['message' => 'Không tồn tại s/p hoặc s/p sai status'], 404);
        }
        if($value){
            $properties = json_decode($product->properties,true);
                  if(is_array($value)){
                      $properties[$attr_id] = $value;
                  }else{
                      $properties[$attr_id][] = $value;
                  }
            $product->properties = json_encode($properties);
            $product->save();
            return Response::json([], 200);

        }else {
            $properties = json_decode($product->properties,true);
            unset($properties[$attr_id]);
            $product->properties = json_encode($properties);
            $product->save();
            return Response::json(['delete' => true], 200);
        }
    }
}
