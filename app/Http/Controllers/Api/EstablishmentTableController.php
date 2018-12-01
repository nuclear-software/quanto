<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EstablishmentTable;

class EstablishmentTableController extends Controller
{

	/**
     * Instantiate a new controller instance.
     *
     * @return  void
     */
    public function __construct()
    {
        $this->middleware('permission:establishment_tables_create')->only(['store']);
        $this->middleware('permission:establishment_tables_read')->only(['index','show']);
        $this->middleware('permission:establishment_tables_update')->only(['update']);
        $this->middleware('permission:establishment_tables_delete')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
    	$establishment_tables = EstablishmentTable::all();
        return response()->json($establishment_tables);
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

	        'establishment_id'=> 'required|exists:establishments,id',
            'name'=> 'required|string|max:191',
            
	    ]);

	    $establishment_table = EstablishmentTable::create($request->all());
		
		return response()->json($establishment_table);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\EstablishmentTable  $establishment_table
     * @return  \Illuminate\Http\Response
     */
    public function show(EstablishmentTable $establishment_table)
    {
        return response()->json($establishment_table);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    \App\EstablishmentTable  $establishment_table
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, EstablishmentTable $establishment_table)
    {
        $validatedData = $request->validate([

	        'establishment_id'=> 'required|exists:establishments,id',
            'name'=> 'required|string|max:191',
            
	    ]);

    	$establishment_table->fill($request->all())->save();
        
        return response()->json($establishment_table);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    \App\EstablishmentTable  $establishment_table
     * @return  \Illuminate\Http\Response
     */
    public function destroy(EstablishmentTable $establishment_table)
    {
        $establishment_table->delete();

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_deleted');

        return response()->json($alert);
    }
}