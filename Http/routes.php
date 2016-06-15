<?php

Route::group(['middleware' => ['web']], function () {
	Route::group( array("prefix" => "admin"), function() {

		Route::get("/", function() { return Redirect::to("admin/login"); });

		Route::any("/login", array( "as" => "admin/users/login", "uses" => "Users\UserController@login"));
		Route::any("/logout", array( "as" => "admin/users/logout", "uses" => "Users\UserController@logout"));

    	//Route::group(array("before" => "auth.dcms"), function() {
    	Route::group(['middleware' => 'auth:dcms'], function() {
        //DASHBOARD - CMS HOME
    		Route::any("dashboard", array( "as" => "admin/dashboard", "uses" => "Dashboard\DashboardController@dashboard"));

    		//USERS
    		Route::group(array("before"=>"admin.dcms"), function() {
    			Route::resource('users','Users\UserController');
    			Route::any('users/api/table', array('as'=>'admin/users/api/table', 'uses' => 'Users\UserController@getDatatable'));
    		});

    		//PROFILE
    		Route::any("profile", array( "as" => "admin/profile", "uses" => "Users\UserController@profile"));
    		Route::any("profile/edit", array( "as" => "admin/profile/edit", "uses" => "Users\UserController@updateProfile"));

    		//SETTINGS - SET UP EXTRA LANGUAGES
    		Route::group( array("prefix" => "settings","before"=>"admin.dcms"), function() {
    			//COUNTRIES
    			Route::group(array("prefix" => "countries"), function() {
    				Route::any('api/table', array('as'=>'admin/settings/countries/api/table', 'uses' => 'Settings\CountryController@getDatatable'));
    			});
    			Route::resource('countries','Settings\CountryController');

    			//LANGUAGES
    			Route::group(array("prefix" => "languages","before"=>"admin.dcms"), function() {
    				Route::any('api/table', array('as'=>'admin/settings/languages/api/table', 'uses' => 'Settings\LanguageController@getDatatable'));
    			});
    			Route::resource('languages','Settings\LanguageController');

    		});
    		Route::any('settings','Settings\SettingController@index');

      });

  });
});

?>
