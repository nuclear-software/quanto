<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ProductReference;

class ProductReferenceController extends Controller
{

	/**
     * Instantiate a new controller instance.
     *
     * @return  void
     */
    public function __construct()
    {
        $this->middleware('permission:product_references_create')->only(['store']);
        $this->middleware('permission:product_references_read')->only(['index','show']);
        $this->middleware('permission:product_references_update')->only(['update']);
        $this->middleware('permission:product_references_delete')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
    	$product_references = ProductReference::all();
        return response()->json($product_references);
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

	        'account_component_id'=> 'required|exists:account_components,id',
            'product_id'=> 'required|exists:products,id',
            'quantity'=> 'required|integer',
            
	    ]);

	    $product_reference = ProductReference::create($request->all());
		
		return response()->json($product_reference);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\ProductReference  $product_reference
     * @return  \Illuminate\Http\Response
     */
    public function show(ProductReference $product_reference)
    {
        return response()->json($product_reference);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    \App\ProductReference  $product_reference
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, ProductReference $product_reference)
    {
        $validatedData = $request->validate([

	        'account_component_id'=> 'required|exists:account_components,id',
            'product_id'=> 'required|exists:products,id',
            'quantity'=> 'required|integer',
            
	    ]);

    	$product_reference->fill($request->all())->save();
        
        return response()->json($product_reference);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    \App\ProductReference  $product_reference
     * @return  \Illuminate\Http\Response
     */
    public function destroy(ProductReference $product_reference)
    {
        $product_reference->delete();

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_deleted');

        return response()->json($alert);
    }
}