<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\User;

class UserController extends Controller
{

	/**
     * Instantiate a new controller instance.
     *
     * @return  void
     */
    public function __construct()
    {
        $this->middleware('permission:users_create')->only(['store','create']);
        $this->middleware('permission:users_read')->only(['index','show']);
        $this->middleware('permission:users_update')->only(['update','edit']);
        $this->middleware('permission:users_delete')->only(['delete']);
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
            'users_read'=> $user->hasPermissionTo('users_read'),
            'users_update'=> $user->hasPermissionTo('users_update'),
            'users_delete'=> $user->hasPermissionTo('users_delete')
        ];
        $users = User::all()->map(function($item) use($permissions){
            $items= [
                [   
                    'permission'=>'users_read',
                    'name'=>'Show',
                    'action'=>route('users.show',['id'=>$item->id]),
                    'icon'=>'fa fa-eye',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'users_update',
                    'name'=>'Edit',
                    'action'=>route('users.edit',['id'=>$item->id]),
                    'icon'=>'fa fa-edit',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'users_delete',
                    'name'=>'Delete', 
                    'data_id'=>$item->id,
                    'class_modal'=>'delete-modal',
                    'icon'=>'fa fa-trash-o',
                ],
            ];

            return[
                
                $item->id,
                $item->name,
                
                $item->email,
                view('settings.permissions', [
                    'items'=>$items,
                    'permissions'=>$permissions
                ])->render()
            ];
        })->all();
        return response()->json(['data'=> $users ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        $columns = "['Id','Name', 'Email', 'Actions']";
        $link = 'users.dt';
        return view('admin.users.index', compact(['columns','link']));

    	//$users = User::all();
        //return view('admin.users.index', compact(['users']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        $roles= Role::orderBy('name')->get()->mapWithKeys(function($item){
            return [ $item->id => title_case(str_replace('_' , ' ', $item->name)) ];
        })->toJson();
        return view('admin.users.create', compact(['roles']));
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

	        'name'=> 'required|string|max:191|min:3',
            'email'=> 'required|email|unique:users,email',
            'roles'=> 'required|array',
            'roles.*'=> 'exists:roles,id' 
            
	    ]);
        $input= $request->all();
        $input['password']= bcrypt('123456');
        $user = User::create($input);

        $user->roles()->sync($request->roles);

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_created');
		
		return redirect()->route('users.index')->with('alert', $alert);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\User  $user
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        if (! $request->old()) {
            $input_old= $user->toArray();
            $input_old['roles']= $user->roles()->pluck('id')->toJson();
            $request->replace($input_old);
            $request->flash();
        }
        $roles= Role::orderBy('name')->get()->mapWithKeys(function($item){
            return [ $item->id => $item->name ];
        })->toJson();
        return view('admin.users.show', compact(['user','roles']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    \App\User  $user
     * @return  \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
    	if (! $request->old()) {
            $input_old= $user->toArray();
            $input_old['roles']= $user->roles()->pluck('id')->toArray();
            $request->replace($input_old);
            $request->flash();
        }
        $roles= Role::orderBy('name')->get()->mapWithKeys(function($item){
            return [ $item->id => $item->name ];
        })->toJson();
    	return view('admin.users.edit', compact(['user','roles']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    \App\User  $user
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([

	        'name'=> 'required|string|max:191|min:3',
            'email'=> 'required|email|unique:users,email,'.$user->id,
            'roles'=> 'required|array',
            'roles.*'=> 'exists:roles,id'
            
	    ]);

        $user->fill($request->all())->save();
        $user->roles()->sync($request->roles);

    	$alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_updated');;
	
		return redirect()->route('users.index')->with('alert', $alert);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    \App\User  $user
     * @return  \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_deleted');

        return redirect()->route('users.index')->with('alert', $alert);
    }
}