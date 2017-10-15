<?php

namespace App\Transformers;

use App\Models\Enterprise\Business;
use League\Fractal;
use Carbon\Carbon;
use App\Models\Enterprise\GLN;
use App\Models\Enterprise\Product;
use App\Models\Enterprise\ProductDistributor;
use App\Models\Enterprise\Brole;
class BusinessTransformer extends Fractal\TransformerAbstract
{
    public function transform(Business $business)
    {
        $logo = null;
        $listGroup = Brole::all()->toArray();
        if ($business->logo) {
            $logo = get_image_url($business->logo, 'thumb_small');
        }
        $group = null;
        $quota = 0;
        $totalProduct = 0;

        if($business->roles()){
            $group = $business->roles()->pluck('id')->toArray();
//            foreach($business->roles as $role){
//                $group = $group.','.$role->name;
//            }
            $listGroup = array_combine(range(1, count($listGroup)), array_values($listGroup));
            $listGroup[0] = ['id' => 0,'name' => 'Không có'];
            for($i = 0;$i < count($listGroup);$i++){
                if(in_array($listGroup[$i]['id'],$group)){
                    $listGroup[$i]['selected'] = true;
                    break;
                }
            }
            if(isset($business->roles()->first()->quota)){
                $role = $business->roles()->first();
                $gln = $business->gln()->where('status', GLN::STATUS_APPROVED)->get();
                $gln = $gln->lists('id')->toArray();
                $quota = $role->quota;
                $countSx = Product::whereIn('gln_id', $gln)->where('is_quota',1)->count();
                $countPP = ProductDistributor::where('business_id',$business->id)->where('status',ProductDistributor::STATUS_ACTIVATED)->where('is_quota',1)->count();
                $totalProduct = $countPP+$countSx;
            }

        }
        $start_date = '';
        if($business->start_date!='0000-00-00 00:00:00'){
            $start_date = Carbon::parse($business->start_date)->format('d-m-Y');
        }
        $end_date = '';
        if($business->end_date!='0000-00-00 00:00:00'){
            $end_date = Carbon::parse($business->end_date)->format('d-m-Y');
        }

        return [
            'id'    => $business->id,
            'name'  => $business->name,
            'logo'  => $logo,
            'listGroup' => $listGroup,
            'group' => $group,
            'start_date' =>$start_date,
            'end_date' =>  $end_date,
            'manager_by' => $business->manager_id,
            'status'    => Business::$statusTexts[$business->status],
            'created_at'    => Carbon::createFromTimestamp(strtotime($business->created_at))->format('d-m-Y'),
            'isActivated'    => $business->status == Business::STATUS_ACTIVATED,
            'isDeactivated'    => $business->status == Business::STATUS_DEACTIVATED,
            'isPendingActivation'    => $business->status == Business::STATUS_PENDING_ACTIVATION,
            'links'    => [
                'self' => route('Staff::Management::business@show', [$business->id]),
                'edit' => route('Staff::Management::business@edit', [$business->id]),
                'approve' => route('Staff::Management::business@approve', [$business->id]),
                'disapprove' => route('Staff::Management::business@disapprove', [$business->id]),
                'delete' => route('Staff::Management::business@delete', [$business->id]),
                'product' =>  route('Staff::Management::product@productByBusiness', [$business->id]),
            ],
            'productExist' => $totalProduct.'/'.$quota
        ];
    }
}
