
Route::get('{!!$route['name']!!}/dt','{!!$route['controller']."@@toDatatable"!!}')->name('{!!$route['name']!!}.dt');
Route::resource('{!!$route['name']!!}', '{!!$route['controller']!!}');
