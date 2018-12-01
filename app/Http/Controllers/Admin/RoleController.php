<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

	/**
     * Instantiate a new controller instance.
     *
     * @return  void
     */
    public function __construct()
    {
        $this->middleware('permission:roles_create')->only(['store','create']);
        $this->middleware('permission:roles_read')->only(['index','show']);
        $this->middleware('permission:roles_update')->only(['update','edit']);
        $this->middleware('permission:roles_delete')->only(['delete']);
    }

    /**
     * Display a listing of the resource in json format.
     *
     * @return  \Illuminate\Http\Response
     */
    public function toDatatable(Request $request)
    {
        $user= $request->user();
        $permissions=[
            'roles_read'=> $user->hasPermissionTo('roles_read'),
            'roles_update'=> $user->hasPermissionTo('roles_update'),
            'roles_delete'=> $user->hasPermissionTo('roles_delete')
        ];
        $roles = Role::all()->map(function($item) use($permissions){
            $items= [                
                [   
                    'permission'=>'roles_read',
                    'name'=>'Show',
                    'action'=>route('roles.show',['id'=>$item->id]),
                    'icon'=>'fa fa-eye',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'roles_update',
                    'name'=>'Edit',
                    'action'=>route('roles.edit',['id'=>$item->id]),
                    'icon'=>'fa fa-edit',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'roles_delete',
                    'name'=>'Delete', 
                    'data_id'=>$item->id,
                    'class_modal'=>'delete-modal',
                    'icon'=>'fa fa-trash-o',
                ],
            ];

            return[
                
                $item->id,
                $item->name,
                
                                view('settings.permissions', [
                    'items'=>$items,
                    'permissions'=>$permissions
                ])->render()
            ];
        })->all();
        return response()->json(['data'=> $roles ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        
        $columns = "['Id','Name','Actions']";
        $link = 'roles.dt';
        return view('admin.roles.index', compact(['columns','link']));
        
    	//$roles = Role::all();
        //return view('admin.roles.index', compact(['roles']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions= Permission::orderBy('name')->get()->mapWithKeys(function($item){
            return [ $item->id => title_case(str_replace('_' , ' ', $item->name)) ];
        })->toJson();
        return view('admin.roles.create', compact(['permissions']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([

            'name'=> 'required|string',
            'permissions'=> 'required|array',
            'permissions.*'=> 'exists:permissions,id'
            
	    ]);

        $role = Role::create($request->only(['name']));        
        $role->permissions()->sync($request->permissions);

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_created');
		
		return redirect()->route('roles.index')->with('alert', $alert);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\Role  $role
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, Role $role)
    {
        $permissions= Permission::orderBy('name')->get()->mapWithKeys(function($item){
            return [ $item->id => title_case(str_replace('_' , ' ', $item->name)) ];
        })->toJson();

        if (! $request->old()) {
            $input_old= $role->toArray();
            $input_old['permissions']= $role->permissions()->pluck('id')->toArray();
            $request->replace($input_old);
            $request->flash();
        }

        return view('admin.roles.show', compact(['role','permissions']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    \App\Role  $role
     * @return  \Illuminate\Http\Response
     */
    public function edit(Request $request, Role $role)
    {
        $permissions= Permission::orderBy('name')->get()->mapWithKeys(function($item){
            return [ $item->id => title_case(str_replace('_' , ' ', $item->name)) ];
        })->toJson();

    	if (! $request->old()) {
            $input_old= $role->toArray();
            $input_old['permissions']= $role->permissions()->pluck('id')->toArray();
            $request->replace($input_old);
            $request->flash();
            // print_r($input_old);
            // die();
        }

    	return view('admin.roles.edit', compact(['role','permissions']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    \App\Role  $role
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $validatedData = $request->validate([

            'name'=> 'required|string',
            'permissions'=> 'required|array',
            'permissions.*'=> 'exists:permissions,id'
            
	    ]);

    	$role->fill($request->only(['name']))->save();
        $role->permissions()->sync($request->permissions);

    	$alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_updated');;
	            
        return redirect()->route('roles.index')->with('alert', $alert);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    \App\Role  $role
     * @return  \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_deleted');

        return redirect()->route('roles.index')->with('alert', $alert);
    }
}