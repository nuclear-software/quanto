<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Company;

class CompanyController extends Controller
{

	/**
     * Instantiate a new controller instance.
     *
     * @return  void
     */
    public function __construct()
    {
        // $this->middleware('permission:companies_create')->only(['store']);
        // $this->middleware('permission:companies_read')->only(['index','show']);
        // $this->middleware('permission:companies_update')->only(['update']);
        // $this->middleware('permission:companies_delete')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
    	$companies = Company::all();
        return response()->json($companies);
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

	        'name'=> 'required|string|max:191',
            
	    ]);

	    $company = Company::create($request->all());
		
		return response()->json($company);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\Company  $company
     * @return  \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    \App\Company  $company
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $validatedData = $request->validate([

	        'name'=> 'required|string|max:191',
            
	    ]);

    	$company->fill($request->all())->save();
        
        return response()->json($company);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    \App\Company  $company
     * @return  \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_deleted');

        return response()->json($alert);
    }
}