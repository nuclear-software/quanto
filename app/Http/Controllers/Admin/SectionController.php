<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Storage;
use App\Section;
use Artisan;

class SectionController extends Controller
{
    public function __construct()
    {
        //$this->middleware('permission:sections');
        $this->middleware('permission:sections_create')->only(['store','create']);
        $this->middleware('permission:sections_read')->only(['index','show']);
        $this->middleware('permission:sections_update')->only(['update','edit']);
        $this->middleware('permission:sections_delete')->only(['delete']);
    }

    /**
     * Display a listing of the resource in json format.
     *
     * @return \Illuminate\Http\Response
     */
    public function toDatatable(Request $request)
    {
        $user= $request->user();
        $permissions=[
            'sections_read'=> $user->hasPermissionTo('sections_read'),
            'sections_update'=> $user->hasPermissionTo('sections_update'),
            'sections_delete'=> $user->hasPermissionTo('sections_delete')
        ];
        $sections = Section::all()->map(function($item) use($permissions){
            $items= [
                [   
                    'permission'=>'sections_read',
                    'name'=>'Show',
                    'action'=>route('sections.show',['id'=>$item->id]),
                    'icon'=>'fa fa-eye',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'sections_update',
                    'name'=>'Edit',
                    'action'=>route('sections.edit',['id'=>$item->id]),
                    'icon'=>'fa fa-edit',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'sections_delete',
                    'name'=>'Delete', 
                    'data_id'=>$item->id,
                    'class_modal'=>'delete-modal',
                    'icon'=>'fa fa-trash-o',
                ],
            ];

            return[
                
                $item->id,
                $item->display_name,
                $item->icon,
                $item->route,
                $item->permission,
                view('settings.permissions', [
                    'items'=>$items,
                    'permissions'=>$permissions
                ])->render()
            ];
        })->all();
        return response()->json(['data'=> $sections ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $columns = "['id','display_name','icon','route', 'permission', 'actions']";
        $link = 'sections.dt';
        return view('admin.sections.index', compact(['columns','link']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types= config('mixedmedia.migration.column_types');
        $components= config('mixedmedia.html_components');
        $relations= config('mixedmedia.model.relationships');

        return view('admin.sections.create',['types'=>$types, 'components'=>$components, 'relationships'=>$relations]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*print_r($request->all());
        die();*/
        /*$validatedData = $request->validate([

            'section_name'=> 'required|email',
            'display_name'=> 'required|email'
            
        ]);*/
        $input= $request->all();
       	$single_name= str_slug($input['section_name'], '_');
       	$plural_name= str_plural($single_name);
        $display_name= title_case($input['display_name']);
        $model_name= studly_case( str_slug($input['section_name'], '_') );
        $table_name= str_slug($input['section_name'], '_');

        $casts= [];
        foreach ($input['attributes'] as $key => $value) {
            $input['attributes'][$key]['name']= str_slug($value['name'], '_');
            $input['attributes'][$key]['display_name']= title_case( str_replace('_', ' ', str_slug($value['name'], '_')));
            $input['attributes'][$key]['json']= json_decode(rtrim($value['json']),true);
            if ($value['element']=='json') {
                $casts[]= "'".$value['name']."' => 'array'";
            }
        }
        
        $columns= $input['attributes'];
        //print_r(compact('single_name','plural_name','display_name','columns'));
        //die();

        $view=[];
        $view['columns']= $columns;
        $view['header']= $display_name;
        $view['title']= $display_name;
        $view['is_unique']= $input['section_type']=='unica'?'true':'false';
        $view['display_name']=$display_name;

        if (in_array('index', $input['section_includes'])) {
            $view['route']= $plural_name;
            $view_index= view('settings.index', ['index'=>$view])->render();
            $path_view_index= 'resources/views/admin/'.$plural_name.'/index.blade.php';
            $view_index_flag= Storage::disk('root')->put($path_view_index, $view_index);
        }
        

        if (in_array('create', $input['section_includes'])) {
            $view['route']= $plural_name.'.store';
            $view_create= view('settings.create', ['create'=>$view])->render();
            $path_view_create= 'resources/views/admin/'.$plural_name.'/create.blade.php';
            $view_create_flag= Storage::disk('root')->put($path_view_create, $view_create);
        }


        if (in_array('edit', $input['section_includes'])) {
            $view['route']= "'".$plural_name.".update'".', $'.$single_name."->id";
            
            $view_edit= view('settings.edit', ['edit'=>$view])->render();
            $path_view_edit= 'resources/views/admin/'.$plural_name.'/edit.blade.php';
            $view_edit_flag= Storage::disk('root')->put($path_view_edit, $view_edit);
        }

        if (in_array('show', $input['section_includes'])) {
            $view['route']= $plural_name.'.store';
            $view_show= view('settings.show', ['show'=>$view])->render();
            $path_view_show= 'resources/views/admin/'.$plural_name.'/show.blade.php';
            $view_show_flag= Storage::disk('root')->put($path_view_show, $view_show);
        }


        if (in_array('route', $input['section_includes'])) {
            $route=[];
            $route['name']= $plural_name;
            $route['controller']= 'Admin\\'.$model_name.'Controller';
            $route_view= view('settings.route',['route'=>$route])->render();
            $route_path = 'routes/web.php';
            $route_flag= Storage::disk('root')->append($route_path, $route_view);
        }
        

        if (in_array('model', $input['section_includes'])) {
            $columns_names= array_pluck($columns, 'name');

            $array_types= array_pluck($columns, 'element');
            $model=[];
            $model['flag_images']= in_array('image_upload',$array_types) || in_array('images_upload',$array_types);

            $model['columns']= $columns;
            $model['class_name']= $model_name;
            $model['table']= $plural_name;
            $model['fillable'] =  "['".implode("', '", $columns_names)."']";
            $model['cast'] = "[".implode(", ", $casts)."]";

            $relationships=[];

            if ( $request->has('relationships') ) {
                $relationships= $input['relationships'];
            }          

            $model['relationships']= $relationships;

            $model_view= view('settings.model',['model' =>$model])->render();
            $model_path= 'app/'.$model_name.'.php';
            $model_flag= Storage::disk('root')->put($model_path, $model_view);
        }


        if (in_array('controller', $input['section_includes'])) {
            $columns_names= array_pluck($columns, 'name');
            $names= array_pluck($columns, 'display_name');
            
            $controller=[];
            $controller['is_unique']= $input['section_type']=='unica'?true:false;


            $controller['columns_names'] =  "['Id','".implode("', '", $names)."','Actions']";
            $controller['namespace']= '\Admin';
            $controller['model_path']= 'App\\'.$model_name;
            $controller['class_name']= $model_name.'Controller';
            $controller['model']= $model_name;
            $controller['model_plural_name']= $plural_name;
            $controller['model_single_name']= $single_name;
            $controller['view']= 'admin.'.$plural_name;
            $controller['route']= $plural_name.'.index';
            $controller['columns']= $columns;
            $controller['input']= "['".implode("', '", $columns_names)."']";;

            $controller_view= view('settings.controller',['controller'=>$controller])->render();

            $controller_path= 'app/Http/Controllers/Admin/'.$model_name.'Controller.php';
            $controller_flag= Storage::disk('root')->put($controller_path, $controller_view);
        }

        if(in_array('api', $input['section_includes'])){
            $controller=[];
            $controller['is_unique']= $input['section_type']=='unica'?true:false;

            $controller['columns_names'] =  "['Id','".implode("', '", $names)."','Actions']";
            $controller['namespace']= '\Api';
            $controller['model_path']= 'App\\'.$model_name;
            $controller['class_name']= $model_name.'Controller';
            $controller['model']= $model_name;
            $controller['model_plural_name']= $plural_name;
            $controller['model_single_name']= $single_name;
            $controller['view']= 'admin.'.$plural_name;
            $controller['route']= $plural_name.'.index';
            $controller['columns']= $columns;
            $controller['input']= "['".implode("', '", $columns_names)."']";;

            $controller_view= view('settings.controller-api',['controller'=>$controller])->render();

            $controller_path= 'app/Http/Controllers/Api/'.$model_name.'Controller.php';
            $controller_flag= Storage::disk('root')->put($controller_path, $controller_view);

            $route=[];
            $route['name']= $plural_name;
            $route['controller']= 'Api\\'.$model_name.'Controller';
            $route_view= view('settings.route-api',['route'=>$route])->render();
            $route_path = 'routes/api.php';
            $route_flag= Storage::disk('root')->append($route_path, $route_view);
        }


        if (in_array('migration', $input['section_includes'])) {
            $migration=[];
            $migration['class_name']= 'Create'.studly_case( str_slug($plural_name, '_') ).'Table';
            $migration['table']= $plural_name;
            $migration['columns']= $columns;

            $migration_view= view('settings.migration', ['migration' =>$migration] )->render();

            $timestamp = date('Y_m_d_His', time());
            $migration_path= 'database/migrations/'.$timestamp.'_create_'.$plural_name.'_table.php';
            $migration_flag= Storage::disk('root')->put($migration_path, $migration_view);

            $seeder=[];
            $seeder['class_name']= studly_case( str_slug($plural_name, '_') ).'TableSeeder';
            $seeder['model_path']= 'App\\'.$model_name;
            $seeder['table']= $plural_name;

            $seeder_view= view('settings.seeder', ['seeder' =>$seeder] )->render();
            $seeder_path= 'database/seeds/'.$seeder['class_name'].'.php';
            $seeder_flag= Storage::disk('root')->put($seeder_path, $seeder_view);

        }

        if (in_array('permissions', $input['section_includes'])) {

            $permission = Permission::firstOrCreate(['name' => $plural_name.'_create']);
            $permission = Permission::firstOrCreate(['name' => $plural_name.'_read']);
            $permission = Permission::firstOrCreate(['name' => $plural_name.'_update']);
            $permission = Permission::firstOrCreate(['name' => $plural_name.'_delete']);


            $user= $request->user();

            if (! $user->hasPermissionTo($plural_name.'_create')) {
                $user->givePermissionTo($plural_name.'_create');
            }
            if (! $user->hasPermissionTo($plural_name.'_read')) {
                $user->givePermissionTo($plural_name.'_read');
            }
            if (! $user->hasPermissionTo($plural_name.'_update')) {
                $user->givePermissionTo($plural_name.'_update');
            }
            if (! $user->hasPermissionTo($plural_name.'_delete')) {
                $user->givePermissionTo($plural_name.'_delete');
            }
        }
        //die();     
        /*$section=[];
        $section['controller']= $controller;
        $section['route']= $route;
        $section['model']= $model;
        $secrion['migration']= $migration;*/

        /*$test= Storage::disk('root')->put('sections-log/prueba.php', '<?php return '.var_export($section, true)."; \n?>" );*/

        try {
            $section= Section::firstOrCreate([
                'display_name' => $display_name,
                'route'=> $plural_name.'.index',
                'icon' => 'glyphicon glyphicon-link',
                'permission'=> $plural_name.'_read'
            ]);

            $exitCode = Artisan::call('migrate');
            
            //$response['status']= 'success';
            //$response['message']= 'successfully created section '.$section->name;

            return redirect()->route('sections.index');//redirect(''.$section->id.'/data')->with('alert', $response);

        } catch (Exception $e) {
            print_r('Fallo la creacion de la seccion contacte al administrador del sistema');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Section $section)
    {
        if (! $request->old()) {
            $request->replace($section->toArray());        
            $request->flash();
        }

        return view('admin.sections.show', compact(['section']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Section $section)
    {
        if (! $request->old()) {
            $request->replace($section->toArray());        
            $request->flash();
        }

        return view('admin.sections.edit', compact(['section']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        $validatedData = $request->validate([

            'display_name'  =>'required',
            'icon'          =>'required',
            'route'         =>'required',
            'permission'    =>'required'
        ]);

        $section->fill($request->all())->save();

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_updated');;
    
        return redirect()->route('sections.index')->with('alert', $alert);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        $section->delete();

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_deleted');

        return redirect()->route('sections.index')->with('alert', $alert);
    }

    public function createOnly(){
        return view('admin.sections.create-only');
    }

    public function storeOnly(Request $request){

        $validatedData = $request->validate([

            'display_name'  =>'required',
            'icon'          =>'required',
            'route'         =>'required',
            'permission'    =>'required'
        ]);

        $section= Section::Create($request->all());

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_created');;
    
        return redirect()->route('sections.index')->with('alert', $alert);
    }
}
