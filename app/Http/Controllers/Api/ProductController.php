<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{

	/**
     * Instantiate a new controller instance.
     *
     * @return  void
     */
    public function __construct()
    {
        $this->middleware('permission:products_create')->only(['store']);
        $this->middleware('permission:products_read')->only(['index','show']);
        $this->middleware('permission:products_update')->only(['update']);
        $this->middleware('permission:products_delete')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
    	$products = Product::all();
        return response()->json($products);
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
            'image'=> 'required|string',
            'price'=> 'required|numeric',
            
	    ]);

	    $product = Product::create($request->all());
		
		return response()->json($product);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\Product  $product
     * @return  \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    \App\Product  $product
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([

	        'company_id'=> 'required|exists:companies,id',
            'name'=> 'required|string|max:191',
            'image'=> 'required|string',
            'price'=> 'required|numeric',
            
	    ]);

    	$product->fill($request->all())->save();
        
        return response()->json($product);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    \App\Product  $product
     * @return  \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_deleted');

        return response()->json($alert);
    }
}