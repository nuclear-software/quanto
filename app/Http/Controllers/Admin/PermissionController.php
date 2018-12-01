<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

	/**
     * Instantiate a new controller instance.
     *
     * @return  void
     */
    public function __construct()
    {
        $this->middleware('permission:permissions_create')->only(['store','create']);
        $this->middleware('permission:permissions_read')->only(['index','show']);
        $this->middleware('permission:permissions_update')->only(['update','edit']);
        $this->middleware('permission:permissions_delete')->only(['delete']);
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
            'permissions_read'=> $user->hasPermissionTo('permissions_read'),
            'permissions_update'=> $user->hasPermissionTo('permissions_update'),
            'permissions_delete'=> $user->hasPermissionTo('permissions_delete')
        ];
        $permissions = Permission::all()->map(function($item) use($permissions){
            $items= [                
                [   
                    'permission'=>'permissions_read',
                    'name'=>'Show',
                    'action'=>route('permissions.show',['id'=>$item->id]),
                    'icon'=>'fa fa-eye',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'permissions_update',
                    'name'=>'Edit',
                    'action'=>route('permissions.edit',['id'=>$item->id]),
                    'icon'=>'fa fa-edit',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'permissions_delete',
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
        return response()->json(['data'=> $permissions ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        
        $columns = "['Id','Name','Actions']";
        $link = 'permissions.dt';
        return view('admin.permissions.index', compact(['columns','link']));
        
    	//$permissions = Permission::all();
        //return view('admin.permissions.index', compact(['permissions']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.permissions.create');
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

	        'name'=> 'required',
            
	    ]);

	    $permission = Permission::create($request->all());

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_created');
		
		return redirect()->route('permissions.index')->with('alert', $alert);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\Permission  $permission
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, Permission $permission)
    {
        if (! $request->old()) {
            $request->replace($permission->toArray());        
            $request->flash();
        }

        return view('admin.permissions.show', compact(['permission']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    \App\Permission  $permission
     * @return  \Illuminate\Http\Response
     */
    public function edit(Request $request, Permission $permission)
    {
    	if (! $request->old()) {
            $request->replace($permission->toArray());        
            $request->flash();
        }

    	return view('admin.permissions.edit', compact(['permission']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    \App\Permission  $permission
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $validatedData = $request->validate([

	        'name'=> 'required',
            
	    ]);

    	$permission->fill($request->all())->save();

    	$alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_updated');;
	            
        return redirect()->route('permissions.index')->with('alert', $alert);

            }

    /**
     * Remove the specified resource from storage.
     *
     * @param    \App\Permission  $permission
     * @return  \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_deleted');

        return redirect()->route('permissions.index')->with('alert', $alert);
    }
}