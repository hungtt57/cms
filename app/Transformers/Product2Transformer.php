<?php

namespace App\Transformers;


use App\Models\Icheck\Product\Product;
use League\Fractal;
use App\Models\Icheck\Product\Category;
use App\Models\Icheck\Product\AttrDynamic;

class Product2Transformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'vendor', 'categories'
    ];
    public $dataCategories;

    public function __construct($categories)
    {

        $this->dataCategories = $categories;
    }

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'vendor', 'categories'
    ];

    public function transform(Product $product)
    {


        $a = [
            'id' => $product->id,
            'name' => $product->product_name,
            'barcode' => $product->gtin_code,
            'image' => get_image_url($product->image_default, 'thumb_small'),
            'price' => $product->price_default,
            'created_at' => ($product->createdAt) ? $product->createdAt->toIso8601String() : '',
            'updated_at' => ($product->updatedAt) ? $product->updatedAt->toIso8601String() : '',
            'scan_count' => $product->scan_count,
            'view_count' => $product->view_count,
            'comment_count' => $product->comment_count,
            'like_count' => $product->like_count,
            'share_count' => $product->share_count,
            'vote_count' => $product->vote_good_count + $product->vote_normal_count + $product->vote_bad_count,
            'report_count' => $product->reports->count(),
            'seller_count' => $product->seller_count,
            'features' => ($product->features) ? $product->features : '',
            'attributes' => [],
            'images' => [],
            'currency' => ($product->currency) ? $product->currency->symbol : 'đ',
            'links' => [
                'edit' => route('Staff::Management::product2@edit', [$product->id]),
                'update' => route('Staff::Management::product2@update', [$product->id]),
                'approve' => route('Staff::Management::product2@approve', [$product->id]),
                'delete' => route('Staff::Management::product2@delete', [$product->id]),
                'inline' => route('Staff::Management::product2@inline', [$product->id]),
                'relate' => route('Staff::Management::relateProduct@index', ['gtin_code' => $product->gtin_code]),
            ],
            'isActivated' => $product->status == Product::STATUS_APPROVED,
            'isDeactivated' => $product->status == Product::STATUS_DISAPPROVED,
            'isPendingActivation' => $product->status == Product::STATUS_PENDING_APPROVAL,
            'status' => Product::$statusTexts[$product->status],
            'isBusiness' => !$product->verify_owner == Product::BUSINESS_VERIFY_OWNER,
//            'isBusiness' => 1,
            'selectedCategories' => $product->categories()->get()->lists('id')->toArray(),
            'listCategories' => $this->dataCategories,
            'mapped' => ($product->mapped == 1) ? 'Yes' : 'No',
        ];

        if (count($product->attributes)) {
            foreach ($product->attributes as $attr) {
                $a['attributes']['a' . $attr->id] = $attr->pivot->content;
            }
        }

        $images = [];

        if ($product->image_default) {
            $images[] = ['url' => get_image_url($product->image_default), 'prefix' => $product->image_default];
        }

        if ($product->pproduct && isset($product->pproduct->attachments)) {

            foreach ($product->pproduct->attachments as $value) {

                if (isset($value['type'])) {
                    if ($value['type'] == 'image') {
                        $images[] = ['url' => get_image_url($value['link']), 'prefix' => $value['link']];

                    }
                }
            }

        }

        $a['images'] = $images;

        //render template
        //name
        if ($a['isBusiness']) {
            $a['renderName'] = '<textarea type="text" class="form-control editable" ';
            $a['renderName'] .= 'data-gtin="' . $a["barcode"] . '"';
            $a['renderName'] .= 'data-url="' . $a['links']["inline"] . '" data-attr="name"';
            $a['renderName'] .= '>'. $a['name'].'</textarea>';
        } else {
            $a['renderName'] = $a['name'];
        }

        //images
        $a['renderImage'] = ' <ul class="aimages list-inline">';
        if ($a['isBusiness']) {
            foreach ($a['images'] as $image) {
                $a['renderImage'] .= '<li><a href="' . $image['url'] . '" class="aimage" data-image="' . $image['prefix'] . '" target="_blank"><img src="' . $image['url'] . '" width="50"/></a> <a href="#" class="rmfile">x</a> </li>';
            }
        } else {
            foreach ($a['images'] as $image) {
                $a['renderImage'] .= '<li><a href="' . $image['url'] . '" class="aimage" data-image="' . $image['prefix'] . '" target="_blank"><img src="' . $image['url'] . '" width="50"/></a></li>';
            }
        }
        $a['renderImage'] .= '</ul>';
        if ($a['isBusiness']) {
            $a['renderImage'] .= '<input type="file" class="fileaaa" style="display:none" data-gtin="' . $a['barcode'] . '" data-url="' . $a['links']['inline'] . '" data-attr="img"/> <a href="#" class="addFile">Thêm</a>';
        }
        //price
        if ($a['isBusiness']) {
            $a['renderPrice'] = ' <input type="text" class="form-control editable price" data-gtin="' . $a['barcode'] . '" data-url="' . $a['links']['inline'] . '" data-attr="price" value="' . $a['price'] . '"><span class="currency">'.$a['currency'].'</span>';
        } else {
            $a['renderPrice'] = $a['price'].$a['currency'];
        }
        //gln
        $a['renderGln'] = '';
        $vendorData = $product->vendor2;
        if ($vendorData) {
            $a['renderGln'] = '<a href="?gln=' . $vendorData->gln_code . '">' . $vendorData->gln_code . '<br>(' . $vendorData->name . ') </a>';
        }
        //thong tin
        $a['renderA1'] = $a['renderA1'] = '<textarea class="form-control editable ckeditor" data-gtin="' . $a['barcode'] . '" data-url="' . $a['links']["inline"] . '" data-attr="description"></textarea>';
        if (isset($a['attributes']['a1'])) {
            if ($a['isBusiness']) {

                $a['renderA1'] = '<textarea class="form-control editable ckeditor" data-gtin="' . $a['barcode'] . '" data-url="' . $a['links']["inline"] . '" data-attr="description"> ' . $a['attributes']['a1'] . '</textarea>';

            } else {
                $a['renderA1'] = $a['attributes']['a1'];
            }
        }


        //drop down
        $a['renderDropDown'] = ' <div class="dropdown"> <button id="product-' . $a['id'] . '-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link"> <i class="icon-more2"></i> </button>';
        $a['renderDropDown'] .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="product-' . $a['id'] . '-actions">';
        if ($a['isBusiness']) {
            $a['renderDropDown'] .= '<li><a href="' . $a['links']['edit'] . '"><i class="icon-pencil5"></i> Sửa</a> </li>';
        }
        $a['renderDropDown'] .= '<li><a href="' . $a['links']['relate'] . '"><i class="icon-pencil5"></i> Sản phẩm liên quan</a></li> </ul></div>';

        $a['renderProperties'] = '';

        if ($product->properties()->count() > 0) {
            $a['renderProperties'] = $this->getAttributesByProduct($a['selectedCategories'], $product);
        }


        return $a;
    }

    /**
     * Include Author
     *
     * @return League\Fractal\ItemResource
     */
    public function includeVendor(Product $product)
    {
        $vendor = $product->vendor2;

        return $this->item($vendor, new VendorTransformer);
    }

    /**
     * Include Author
     *
     * @return League\Fractal\ItemResource
     */
    public function includeCategories(Product $product)
    {
        $categories = $product->categories;

        return $this->collection($categories, new CategoryTransformer);
    }

    public function getAttributesByProduct($selected, $product)
    {

        $attr_value = $product->properties()->get();
        $result = '';
        $att_array = [];
        if (empty($attr_value)) {
            return '';
        }
        $categories = Category::whereIn('id', $selected)->get();
        if ($categories) {
            foreach ($categories as $category) {
                $attributes = $category->attributes;
                if ($attributes) {
                    $attributes = explode(',', $attributes);
                    foreach ($attributes as $at) {
                        if (isset($att_array[$at])) {
                            $att_array[$at] = intval($att_array[$at]) + 1;
                        } else {
                            $att_array[$at] = 1;
                        }
                    }
                }

            }
        }

        if ($attr_value) {
            foreach ($attr_value as $key => $value) {
                $property = AttrDynamic::find($value->attribute_id);
                if ($property) {
                    $properties = explode(',', $value->content);
                    $count = 1;
                    if (isset($attr_array[$property->id])) {
                        $count = $attr_array[$property->id];
                    }
                    $result .= $this->templateEdit($property, $count, $properties,$product->id);
                }
            }
        }
        return $result;
    }

    public function templateEdit($attr, $count = 1, $properties,$productId)
    {
        $string = null;
        if ($attr->enum) {
            if (trim($attr->type) == 'single') {
                $value = $attr->enum;
                $value = explode(',', $value);
                $s = '';
                foreach ($value as $v) {

                    if (in_array($v, $properties)) {
                        $s .= '<option value="' . $v . '" selected>' . $v . '</option>';

                    } else {
                        $s .= '<option value="' . $v . '">' . $v . '</option>';
                    }

                }
                $string = '<div class="row" id="'.$productId.$attr->id.'" data-count="' . $count . '" data-id="' . $attr->id . '"><div class="col-md-5"><label for="country" class="control-label text-semibold">' . $attr->title . '</label></div><div class="col-md-7">
                        <select  class="select-border-color border-warning js-attr properties-product">
                                                   ' . $s . '
                                                    </select>
                                                </div>
                                            </div>
                       ';
            }
            if (trim($attr->type) == 'multiple') {
                $value = $attr->enum;
                $value = explode(',', $value);
                $s = '';
                foreach ($value as $v) {

                    if (in_array($v, $properties)) {
                        $s .= '<option value="' . $v . '" selected>' . $v . '</option>';
                    } else {
                        $s .= '<option value="' . $v . '">' . $v . '</option>';
                    }
                }
                $string = '<div class="row" id="'.$productId.$attr->id.'" data-count="' . $count . '" data-id="' . $attr->id . '"><div class="col-md-5"><label for="country" class="control-label text-semibold">' . $attr->title . '</label></div><div class="col-md-7">
                        <select " class="select-border-color border-warning js-attr  properties-product"  multiple="multiple">
                                                   ' . $s . '
                                                    </select>
                                                </div>
                                            </div>
                       ';
            }
        } else {
            $t = '';

            if (isset($properties)) {
                $properties = implode(',', $properties);
                $t = 'value="' . $properties . '"';
            }
            $string = '<div class="row" id="'.$productId.$attr->id.'" data-count="' . $count . '" data-id="' . $attr->id . '"><div class="col-md-6"><label for="country" class="control-label text-semibold">' . $attr->title . '</label></div><div class="col-md-6">
                        <input maxlength="25"  ' . $t . ' type="text" class="form-control  properties-product">
                        
                                                  
                                                </div>
                                            </div>
                       ';
        }

        return $string;
    }

}
