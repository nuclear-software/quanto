<?php
namespace App\Http\Controllers\Api;

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
        $this->middleware('permission:account_components_create')->only(['store']);
        $this->middleware('permission:account_components_read')->only(['index','show']);
        $this->middleware('permission:account_components_update')->only(['update']);
        $this->middleware('permission:account_components_delete')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
    	$account_components = AccountComponent::all();
        return response()->json($account_components);
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
		
		return response()->json($account_component);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\AccountComponent  $account_component
     * @return  \Illuminate\Http\Response
     */
    public function show(AccountComponent $account_component)
    {
        return response()->json($account_component);
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
        
        return response()->json($account_component);
        
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

        return response()->json($alert);
    }
}