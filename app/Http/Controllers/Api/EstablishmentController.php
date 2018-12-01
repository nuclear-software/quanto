<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Establishment;

class EstablishmentController extends Controller
{

	/**
     * Instantiate a new controller instance.
     *
     * @return  void
     */
    public function __construct()
    {
        $this->middleware('permission:establishments_create')->only(['store']);
        $this->middleware('permission:establishments_read')->only(['index','show']);
        $this->middleware('permission:establishments_update')->only(['update']);
        $this->middleware('permission:establishments_delete')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
    	$establishments = Establishment::all();
        return response()->json($establishments);
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
            'name'=> 'required|string|max:191',
            'location'=> 'required|string|max:191',
            
	    ]);

	    $establishment = Establishment::create($request->all());
		
		return response()->json($establishment);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\Establishment  $establishment
     * @return  \Illuminate\Http\Response
     */
    public function show(Establishment $establishment)
    {
        return response()->json($establishment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    \App\Establishment  $establishment
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, Establishment $establishment)
    {
        $validatedData = $request->validate([

	        'company_id'=> 'required|exists:companies,id',
            'name'=> 'required|string|max:191',
            'location'=> 'required|string|max:191',
            
	    ]);

    	$establishment->fill($request->all())->save();
        
        return response()->json($establishment);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    \App\Establishment  $establishment
     * @return  \Illuminate\Http\Response
     */
    public function destroy(Establishment $establishment)
    {
        $establishment->delete();

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_deleted');

        return response()->json($alert);
    }
}