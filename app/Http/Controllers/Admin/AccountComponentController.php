<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AccountComponent;

class AccountComponentController extends Controller
{

	/**
     * Instantiate a new controller instance.
     *
     * @return  void
     */
    public function __construct()
    {
        $this->middleware('permission:account_components_create')->only(['store','create']);
        $this->middleware('permission:account_components_read')->only(['index','show']);
        $this->middleware('permission:account_components_update')->only(['update','edit']);
        $this->middleware('permission:account_components_delete')->only(['delete']);
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
            'account_components_read'=> $user->hasPermissionTo('account_components_read'),
            'account_components_update'=> $user->hasPermissionTo('account_components_update'),
            'account_components_delete'=> $user->hasPermissionTo('account_components_delete')
        ];
        $account_components = AccountComponent::all()->map(function($item) use($permissions){
            $items= [                
                [   
                    'permission'=>'account_components_read',
                    'name'=>'Show',
                    'action'=>route('account_components.show',['id'=>$item->id]),
                    'icon'=>'fa fa-eye',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'account_components_update',
                    'name'=>'Edit',
                    'action'=>route('account_components.edit',['id'=>$item->id]),
                    'icon'=>'fa fa-edit',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'account_components_delete',
                    'name'=>'Delete', 
                    'data_id'=>$item->id,
                    'class_modal'=>'delete-modal',
                    'icon'=>'fa fa-trash-o',
                ],
            ];

            return[
                
                $item->id,
                $item->account_id,
                
                $item->estado,
                
                                view('settings.permissions', [
                    'items'=>$items,
                    'permissions'=>$permissions
                ])->render()
            ];
        })->all();
        return response()->json(['data'=> $account_components ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        
        $columns = "['Id','Account Id', 'Estado','Actions']";
        $link = 'account_components.dt';
        return view('admin.account_components.index', compact(['columns','link']));
        
    	//$account_components = AccountComponent::all();
        //return view('admin.account_components.index', compact(['account_components']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.account_components.create');
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

	        'account_id'=> 'required|exists:accounts,id',
            'estado'=> 'required|integer',
            
	    ]);

	    $account_component = AccountComponent::create($request->all());

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_created');
		
		return redirect()->route('account_components.index')->with('alert', $alert);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\AccountComponent  $account_component
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, AccountComponent $account_component)
    {
        if (! $request->old()) {
            $request->replace($account_component->toArray());        
            $request->flash();
        }

        return view('admin.account_components.show', compact(['account_component']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    \App\AccountComponent  $account_component
     * @return  \Illuminate\Http\Response
     */
    public function edit(Request $request, AccountComponent $account_component)
    {
    	if (! $request->old()) {
            $request->replace($account_component->toArray());        
            $request->flash();
        }

    	return view('admin.account_components.edit', compact(['account_component']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    \App\AccountComponent  $account_component
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, AccountComponent $account_component)
    {
        $validatedData = $request->validate([

	        'account_id'=> 'required|exists:accounts,id',
            'estado'=> 'required|integer',
            
	    ]);

    	$account_component->fill($request->all())->save();

    	$alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_updated');;
	            
        return redirect()->route('account_components.index')->with('alert', $alert);

                
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    \App\AccountComponent  $account_component
     * @return  \Illuminate\Http\Response
     */
    public function destroy(AccountComponent $account_component)
    {
        $account_component->delete();

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_deleted');

        return redirect()->route('account_components.index')->with('alert', $alert);
    }
}