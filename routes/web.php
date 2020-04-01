<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Spatie\Sitemap\SitemapGenerator;

Route::match(['get','post'],'/', 'HomeController@index');


Route::get('sitemap', function(){
    SitemapGenerator::create('http://http://localhost/coronainindia/')->writeToFile('sitemap.xml');
    return 'sitemap created';
});


/*--------------- Website Url ------------------------*/

Route::match(['get','post'],'/{controller}/{action?}/{params?}', function ($controller, $action='index', $params='') {    
    $GLOBALS['action_param'] = $action;
    $GLOBALS['action_cntrl'] = $controller;
    // echo $controller;echo $action; echo $params; exit;
    $params = explode('/', $params);
    $app    = app();

    if(strpos($controller,'-') !==false){
        $strControllerName = implode('', array_map('ucwords', explode('-',$controller)));
    }else{
        $strControllerName = ucwords($controller);
    }

    $controller = $app->make("\App\Http\Controllers\\". $strControllerName .'Controller' );
    return $controller->callAction($action, $params);
    
})->where('params', '[A-Za-z0-9/]+');
