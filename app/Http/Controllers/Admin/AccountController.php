<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;

class AccountController extends Controller
{

	/**
     * Instantiate a new controller instance.
     *
     * @return  void
     */
    public function __construct()
    {
        $this->middleware('permission:accounts_create')->only(['store','create']);
        $this->middleware('permission:accounts_read')->only(['index','show']);
        $this->middleware('permission:accounts_update')->only(['update','edit']);
        $this->middleware('permission:accounts_delete')->only(['delete']);
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
            'accounts_read'=> $user->hasPermissionTo('accounts_read'),
            'accounts_update'=> $user->hasPermissionTo('accounts_update'),
            'accounts_delete'=> $user->hasPermissionTo('accounts_delete')
        ];
        $accounts = Account::all()->map(function($item) use($permissions){
            $items= [                
                [   
                    'permission'=>'accounts_read',
                    'name'=>'Show',
                    'action'=>route('accounts.show',['id'=>$item->id]),
                    'icon'=>'fa fa-eye',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'accounts_update',
                    'name'=>'Edit',
                    'action'=>route('accounts.edit',['id'=>$item->id]),
                    'icon'=>'fa fa-edit',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'accounts_delete',
                    'name'=>'Delete', 
                    'data_id'=>$item->id,
                    'class_modal'=>'delete-modal',
                    'icon'=>'fa fa-trash-o',
                ],
            ];

            return[
                
                $item->id,
                $item->establishment_table_id,
                
                                view('settings.permissions', [
                    'items'=>$items,
                    'permissions'=>$permissions
                ])->render()
            ];
        })->all();
        return response()->json(['data'=> $accounts ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        
        $columns = "['Id','Establishment Table Id','Actions']";
        $link = 'accounts.dt';
        return view('admin.accounts.index', compact(['columns','link']));
        
    	//$accounts = Account::all();
        //return view('admin.accounts.index', compact(['accounts']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.accounts.create');
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

	        'establishment_table_id'=> 'required|exists:establishment_tables,id',
            
	    ]);

	    $account = Account::create($request->all());

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_created');
		
		return redirect()->route('accounts.index')->with('alert', $alert);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\Account  $account
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, Account $account)
    {
        if (! $request->old()) {
            $request->replace($account->toArray());        
            $request->flash();
        }

        return view('admin.accounts.show', compact(['account']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    \App\Account  $account
     * @return  \Illuminate\Http\Response
     */
    public function edit(Request $request, Account $account)
    {
    	if (! $request->old()) {
            $request->replace($account->toArray());        
            $request->flash();
        }

    	return view('admin.accounts.edit', compact(['account']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    \App\Account  $account
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        $validatedData = $request->validate([

	        'establishment_table_id'=> 'required|exists:establishment_tables,id',
            
	    ]);

    	$account->fill($request->all())->save();

    	$alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_updated');;
	            
        return redirect()->route('accounts.index')->with('alert', $alert);

                
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    \App\Account  $account
     * @return  \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        $account->delete();

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_deleted');

        return redirect()->route('accounts.index')->with('alert', $alert);
    }
}