<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\AclUserGroupToAccess;
use App\Subscribe; //model class
use App\ContactInfo;
use App\SocialNetwork;
use App\Hotline;
use App\CompanyInformation;
use App\Speciality;
use App\FooterMenu;
use App\WhToLocalWhManager;
use App\TmToWarehouse;
use App\ProductTransferMaster;
use App\Order;
use App\Delivery;
use App\Menu;
use App\Product;
use DB;
use Route;
use Common;
use Auth;
use Helper;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        view()->composer('*', function ($view) {

            // if (Auth::check()) {
                $currentControllerFunction = Route::currentRouteAction();
                $controllerName = $currentCont = '';
                if (!empty($currentControllerFunction[1])) {
                    $currentCont = preg_match('/([a-z]*)@/i', request()->route()->getActionName(), $currentControllerFunction);
                    $controllerName = str_replace('controller', '', strtolower($currentControllerFunction[1]));
                }

                $view->with([
                    'controllerName' => $controllerName,

                ]);
            // }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        //
    }

}
