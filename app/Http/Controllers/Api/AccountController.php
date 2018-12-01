<?php
namespace App\Http\Controllers\Api;

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
        $this->middleware('permission:accounts_create')->only(['store']);
        $this->middleware('permission:accounts_read')->only(['index','show']);
        $this->middleware('permission:accounts_update')->only(['update']);
        $this->middleware('permission:accounts_delete')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
    	$accounts = Account::all();
        return response()->json($accounts);
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
		
		return response()->json($account);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\Account  $account
     * @return  \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        return response()->json($account);
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
        
        return response()->json($account);
        
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

        return response()->json($alert);
    }
}