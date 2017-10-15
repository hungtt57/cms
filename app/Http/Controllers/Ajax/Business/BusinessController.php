<?php

namespace App\Http\Controllers\Ajax\Business;

use App\Models\Enterprise\ProductDistributorTemp;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Enterprise\Product;
use App\Models\Icheck\Product\Category;
use App\Models\Icheck\Product\AttrDynamic;
use Auth;
use App\Models\Icheck\Product\Product as Product2;
use App\Models\Icheck\Product\AttrValue;
class BusinessController extends Controller
{


    public function ajaxGetAttributes(Request $request)
    {
        $category_id = $request->input('id');
        $category = Category::find($category_id);
        $result = [];
        if ($category) {
            $attributes = $category->attributes;
            if ($attributes) {
                $attributes = explode(',', $attributes);
                $attrs = AttrDynamic::whereIn('id', $attributes)->get();
                foreach ($attrs as $attr) {
                    $result[$attr->id] = $this->template($attr);
                }
            }
        }
        return json_encode($result);
    }
    public function template($attr)
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
                $string = '<div class="row" data-count="1" id="'.$attr->id.'"><div class="col-md-3"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-9">
                        <select  name="properties['.$attr->id.'][]" class="select-border-color border-warning js-attr">
                                                   '.$s.'
                                                    </select>
                                                </div>
                                            </div>
                       ';
            }
            if(trim($attr->type) == 'multiple'){
                $value = $attr->enum;
                $value = explode(',',$value);
                $s = '';
                foreach ($value as $v){
                    $s .=   '<option value="'.$v.'">'.$v.'</option>';
                }
                $string = '<div class="row" data-count="1" id="'.$attr->id.'"><div class="col-md-3"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-9">
                        <select name="properties['.$attr->id.'][]" class="select-border-color border-warning js-attr"  multiple="multiple">
                                                   '.$s.'
                                                    </select>
                                                </div>
                                            </div>
                       ';
            }
        }else{
            $string = '<div class="row" data-count="1" id="'.$attr->id.'"><div class="col-md-3"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-9">
                        <input  maxlength="25" name="properties['.$attr->id.'][]" type="text" class="form-control ">
                        
                                                  
                                                </div>
                                            </div>
                       ';
        }

        return $string;
    }



    //get exis atributes
    public function ajaxGetAttributesByCategory(Request $request){
        $id = $request->input('id');
        $product = Product::find($id);
        $properties = json_decode($product->properties,true);

        $selected = $request->input('selected');
        $result = [];
        $att_array = [];
        if(empty($properties) or empty($product)){
            return json_encode($result);
        }
        $categories = Category::whereIn('id',$selected)->get();
        if($categories){
            foreach ($categories as $category){
                $attributes = $category->attributes;
                if($attributes){
                    $attributes = explode(',', $attributes);
                    foreach ($attributes as $at) {
                        if(isset($att_array[$at])){
                            $att_array[$at] = intval($att_array[$at]) + 1;
                        }else{
                            $att_array[$at] = 1;
                        }
                    }
                }

            }
        }
        if($att_array){
            foreach ($att_array as $key => $value) {
                $property = AttrDynamic::find($key);
                if($property){
                    $result[$property->id] = $this->templateEdit($property,$value,$properties);
                }
            }
        }
        return json_encode($result);
    }
    public function templateEdit($attr,$count = 1,$properties)
    {
        $string = null;
        if($attr->enum){
            if(trim($attr->type) == 'single'){
                $value = $attr->enum;
                $value = explode(',',$value);
                $s = '';
                foreach ($value as $v){
                    if(isset($properties[$attr->id])){
                        if(in_array($v,$properties[$attr->id])){
                            $s .=   '<option value="'.$v.'" selected>'.$v.'</option>';
                        }
                    }else{
                        $s .=   '<option value="'.$v.'">'.$v.'</option>';
                    }

                }
                $string = '<div class="row" data-count="'.$count.'" id="'.$attr->id.'"><div class="col-md-6"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-6">
                        <select  name="properties['.$attr->id.'][]" class="select-border-color border-warning js-attr">
                                                   '.$s.'
                                                    </select>
                                                </div>
                                            </div>
                       ';
            }
            if(trim($attr->type) == 'multiple'){
                $value = $attr->enum;
                $value = explode(',',$value);
                $s = '';
                foreach ($value as $v){
                    if(isset($properties[$attr->id])){
                        if(in_array($v,$properties[$attr->id])){
                            $s .=   '<option value="'.$v.'" selected>'.$v.'</option>';
                        }
                    }else{
                        $s .=   '<option value="'.$v.'">'.$v.'</option>';
                    }
                }
                $string = '<div class="row" data-count="'.$count.'" id="'.$attr->id.'"><div class="col-md-6"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-6">
                        <select name="properties['.$attr->id.'][]" class="select-border-color border-warning js-attr"  multiple="multiple">
                                                   '.$s.'
                                                    </select>
                                                </div>
                                            </div>
                       ';
            }
        }else{
            $t = '';
            if(isset($properties[$attr->id])){
                $t = 'value="'.$properties[$attr->id][0].'"';
            }
            $string = '<div class="row" data-count="'.$count.'" id="'.$attr->id.'"><div class="col-md-6"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-6">
                        <input maxlength="25" name="properties['.$attr->id.'][]" '.$t.' type="text" class=" form-control">
                        
                                                  
                                                </div>
                                            </div>
                       ';
        }

        return $string;
    }

    // get attributes by product distributor
    public function ajaxGetAttributesByEditProductDistributor(Request $request){
        $gtin_code = $request->input('gtin_code');
        $product = ProductDistributorTemp::where('business_id',Auth::user()->id)->where('gtin_code',$gtin_code)->where('status',ProductDistributorTemp::STATUS_PENDING_APPROVAL)->first();
        $selected = $request->input('selected');
        $result = [];
        $att_array = [];
        if(empty($product)){
            $product = Product2::where('gtin_code',$gtin_code)->first();
            $attr_value = AttrValue::where('product_id',$product->id)->get();
            if(empty($attr_value)){
                return json_encode($result);
            }
            foreach ($attr_value as $key => $value) {
                $property = AttrDynamic::find($value->attribute_id);
                if($property){
                    $p = explode(',',$value->content);
                    if($p){
                        $properties[$property->id] = $p;
                    }
                }
            }
        }else{
            $properties = json_decode($product->properties,true);
        }


        if(empty($properties) or empty($product)){
            return json_encode($result);
        }
        $categories = Category::whereIn('id',$selected)->get();
        if($categories){
            foreach ($categories as $category){
                $attributes = $category->attributes;
                if($attributes){
                    $attributes = explode(',', $attributes);
                    foreach ($attributes as $at) {
                        if(isset($att_array[$at])){
                            $att_array[$at] = intval($att_array[$at]) + 1;
                        }else{
                            $att_array[$at] = 1;
                        }
                    }
                }

            }
        }
        if($att_array){
            foreach ($att_array as $key => $value) {
                $property = AttrDynamic::find($key);
                if($property){
                    $result[$property->id] = $this->templateEdit($property,$value,$properties);
                }
            }
        }
        return json_encode($result);
    }
}
