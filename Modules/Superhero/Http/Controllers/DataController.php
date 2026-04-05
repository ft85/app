<?php

namespace Modules\Superhero\Http\Controllers;

use App\TaxRate;
use App\User;
use App\Utils\ModuleUtil;
use App\Utils\Util;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Menu;
use Modules\Project\Entities\Project;
use Modules\Project\Entities\ProjectTask;
use Modules\Project\Entities\ProjectTransaction;
use Modules\Project\Utils\ProjectUtil;

class DataController extends Controller
{
    
    public function modifyAdminMenu()
    {
        $business_id = session()->get('user.business_id');
        $module_util = new ModuleUtil();
        $commonUtil = new Util();
        $is_admin = $commonUtil->is_admin(auth()->user(), $business_id);

      
        Menu::modify(
                'admin-sidebar-menu',
                function ($menu) {
                    $menu->url(
                        action([\Modules\Superhero\Http\Controllers\SuperheroController::class, 'index']).'?project_view=list_view',
                        'Superhero',
                        ['icon' => 'fa fa-project-diagram','style' => config('app.env') == 'demo' ? 'background-color: #e4186d !important;' : '']
                    )
                    ->order(90);
                }
            );
        
    }

    
    public function superadmin_package()
    {
        return [
            [
                'name' => 'Superhero',
                'label' => 'Superhero',
                'default' => true,
            ],
        ];
    }

    
    public function addTaxonomies()
    {
        $business_id = request()->session()->get('user.business_id');

        $module_util = new ModuleUtil();
        

        return [
            'project' => [
                'taxonomy_label' =>'RRA CATERGORY',
                'heading' => 'heading',
                'sub_heading' => 'Heading',
                'enable_taxonomy_code' => false,
                'enable_sub_taxonomy' => false,
                'navbar' => 'project::layouts.nav',
            ],
        ];
    }

    
}
