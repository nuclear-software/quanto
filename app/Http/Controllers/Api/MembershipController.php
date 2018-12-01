<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Membership;

class MembershipController extends Controller
{

	/**
     * Instantiate a new controller instance.
     *
     * @return  void
     */
    public function __construct()
    {
        $this->middleware('permission:memberships_create')->only(['store']);
        $this->middleware('permission:memberships_read')->only(['index','show']);
        $this->middleware('permission:memberships_update')->only(['update']);
        $this->middleware('permission:memberships_delete')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
    	$memberships = Membership::all();
        return response()->json($memberships);
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

	        'company_id'=> 'required|exists:companies,id',
            'user_quantity'=> 'required|integer',
            'table_quantity'=> 'required|integer',
            'establishment_quantity'=> 'required|integer',
            'expiry_date'=> 'required|date_format:"Y-m-d"',
            
	    ]);

	    $membership = Membership::create($request->all());
		
		return response()->json($membership);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\Membership  $membership
     * @return  \Illuminate\Http\Response
     */
    public function show(Membership $membership)
    {
        return response()->json($membership);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    \App\Membership  $membership
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, Membership $membership)
    {
        $validatedData = $request->validate([

	        'company_id'=> 'required|exists:companies,id',
            'user_quantity'=> 'required|integer',
            'table_quantity'=> 'required|integer',
            'establishment_quantity'=> 'required|integer',
            'expiry_date'=> 'required|date_format:"Y-m-d"',
            
	    ]);

    	$membership->fill($request->all())->save();
        
        return response()->json($membership);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    \App\Membership  $membership
     * @return  \Illuminate\Http\Response
     */
    public function destroy(Membership $membership)
    {
        $membership->delete();

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_deleted');

        return response()->json($alert);
    }
}