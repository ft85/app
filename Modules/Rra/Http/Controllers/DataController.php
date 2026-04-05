<?php

namespace Modules\Hms\Http\Controllers;

use App\System;
use App\Utils\Util;
use Illuminate\Routing\Controller;
use Menu;
use App\Utils\ModuleUtil;
use App\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Modules\Hms\Entities\HmsRoomType;
use App\Utils\BusinessUtil;
use App\Utils\TransactionUtil;


class DataController extends Controller
{
      /**
     * Defines user permissions for the module.
     *
     * @return array
     */
    // public function user_permissions()
    // {
    //     return [
    //         [
    //             'value' => 'hms.manage_rooms',
    //             'label' => __('hms::lang.manage_rooms'),
    //             'default' => false,
    //         ],
    //         [
    //             'value' => 'hms.manage_price',
    //             'label' => __('hms::lang.manage_price'),
    //             'default' => false,
    //         ],
    //         [
    //             'value' => 'hms.manage_unavailable',
    //             'label' => __('hms::lang.manage_unavailable'),
    //             'default' => false,
    //         ],
    //         [
    //             'value' => 'hms.manage_extra',
    //             'label' => __('hms::lang.manage_extra'),
    //             'default' => false,
    //         ],
    //         [
    //             'value' => 'hms.manage_coupon',
    //             'label' => __('hms::lang.manage_coupon'),
    //             'default' => false,
    //         ],
    //         [
    //             'value' => 'hms.add_booking',
    //             'label' => __('hms::lang.add_booking'),
    //             'default' => false,
    //         ],
    //         [
    //             'value' => 'hms.edit_booking',
    //             'label' => __('hms::lang.edit_booking'),
    //             'default' => false,
    //         ],
    //         [
    //             'value' => 'hms.delete_booking',
    //             'label' => __('hms::lang.delete_booking'),
    //             'default' => false,
    //         ],
    //         [
    //             'value' => 'hms.manage_amenities',
    //             'label' => __('hms::lang.manage_amenities'),
    //             'default' => false,
    //         ],

    //         [
    //             'value' => 'hms.manage_settings',
    //             'label' => __('hms::lang.manage_settings'),
    //             'default' => false,
    //         ],

    //         [
    //             'value' => 'hms.add_booking_payment',
    //             'label' => __('hms::lang.add_booking_payment'),
    //             'default' => false,
    //         ],
    //         [
    //             'value' => 'hms.edit_booking_payment',
    //             'label' => __('hms::lang.edit_booking_payment'),
    //             'default' => false,
    //         ],
    //         [
    //             'value' => 'hms.delete_booking_payment',
    //             'label' => __('hms::lang.delete_booking_payment'),
    //             'default' => false,
    //         ],

    //     ];
    // }

    /**
     * Superadmin package permissions
     *
     * @return array
     */
    public function superadmin_package()
    {
        return [
            [
                'name' => 'rra',
                'label' => 'RRA',
                'default' => true,
            ],
        ];
    }


    
    
    // public function modifyAdminMenu()
    // {
    //     $module_util = new ModuleUtil();

    //     $business_id = session()->get('user.business_id');
    //     //$is_hms_enabled = (bool) $module_util->hasThePermissionInSubscription($business_id, 'hms_module');

    //     // Menu::modify('admin-sidebar-menu', function ($menu) {
    //     //         $menu->url(
    //     //             action([\Modules\Rra\Http\Controllers\RRAController::class, 'index']),
    //     //             'RRA',
    //     //             ['icon' => 'fas fa-list', 'style' => config('app.env') == 'demo' ? 'background-color: yellow !important;' : '']
    //     //         )->order(200);
    //     //  });


    //     Menu::modify('admin-sidebar-menu', function ($menu) {
    //         $menu->dropdown('Label', function ($sub) {
    //             $sub->url(action('\Modules\Rra\Http\Controllers\YourController@index'), 'Label', [
    //                 'icon' => 'fa fa-list'
    //             ]);
    //         });
    //     });
        
        
        

       
    // }

    public function modifyAdminMenu()
    {
        
        $business_id = session()->get('user.business_id');
        
            Menu::modify('admin-sidebar-menu', function ($menu) {
                $menu->url(
                    action([\Modules\Hms\Http\Controllers\RRAController::class, 'index']),
                    __('hms::lang.hms'),
                    ['icon' => 'fas fa-hotel', 'style' => config('app.env') == 'demo' ? 'background-color: yellow !important;' : '']
                )->order(70);
            });
        
          
    }

      
    // public function addTaxonomies()
    // {
    //     $module_util = new ModuleUtil();
    //     $business_id = request()->session()->get('user.business_id');

    //     $output = [
    //         'amenities' => [],
    //     ];
    
    //         if (auth()->user()->can('hms.manage_amenities')) {
    //             $output['amenities'] = [
    //                 'taxonomy_label' => __('hms::lang.amenity'),
    //                 'heading' => __('hms::lang.amenities'),
    //                 'sub_heading' => __('hms::lang.amenities'),
    //                 'enable_taxonomy_code' => false,
    //                 'enable_sub_taxonomy' => false,
    //                 'heading_tooltip' => __('hms::lang.amenity_help_text'),
    //                 'navbar' => 'hms::layouts.nav',
    //             ];
    //         }
    //     return $output;
    // }

    


    
 
}
