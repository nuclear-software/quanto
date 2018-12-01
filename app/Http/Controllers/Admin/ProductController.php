<?php
namespace App\Http\Controllers\Admin;

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
        $this->middleware('permission:products_create')->only(['store','create']);
        $this->middleware('permission:products_read')->only(['index','show']);
        $this->middleware('permission:products_update')->only(['update','edit']);
        $this->middleware('permission:products_delete')->only(['delete']);
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
            'products_read'=> $user->hasPermissionTo('products_read'),
            'products_update'=> $user->hasPermissionTo('products_update'),
            'products_delete'=> $user->hasPermissionTo('products_delete')
        ];
        $products = Product::all()->map(function($item) use($permissions){
            $items= [                
                [   
                    'permission'=>'products_read',
                    'name'=>'Show',
                    'action'=>route('products.show',['id'=>$item->id]),
                    'icon'=>'fa fa-eye',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'products_update',
                    'name'=>'Edit',
                    'action'=>route('products.edit',['id'=>$item->id]),
                    'icon'=>'fa fa-edit',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'products_delete',
                    'name'=>'Delete', 
                    'data_id'=>$item->id,
                    'class_modal'=>'delete-modal',
                    'icon'=>'fa fa-trash-o',
                ],
            ];

            return[
                
                $item->id,
                $item->company->name,
                
                $item->name,
                
                $item->image,
                
                $item->price,
                
                                view('settings.permissions', [
                    'items'=>$items,
                    'permissions'=>$permissions
                ])->render()
            ];
        })->all();
        return response()->json(['data'=> $products ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        
        $columns = "['Id','Company Id', 'Name', 'Image', 'Price','Actions']";
        $link = 'products.dt';
        return view('admin.products.index', compact(['columns','link']));
        
    	//$products = Product::all();
        //return view('admin.products.index', compact(['products']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.products.create');
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

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_created');
		
		return redirect()->route('products.index')->with('alert', $alert);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\Product  $product
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, Product $product)
    {
        if (! $request->old()) {
            $request->replace($product->toArray());        
            $request->flash();
        }

        return view('admin.products.show', compact(['product']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    \App\Product  $product
     * @return  \Illuminate\Http\Response
     */
    public function edit(Request $request, Product $product)
    {
    	if (! $request->old()) {
            $request->replace($product->toArray());        
            $request->flash();
        }

    	return view('admin.products.edit', compact(['product']));
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

    	$alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_updated');;
	            
        return redirect()->route('products.index')->with('alert', $alert);

                
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

        return redirect()->route('products.index')->with('alert', $alert);
    }
}