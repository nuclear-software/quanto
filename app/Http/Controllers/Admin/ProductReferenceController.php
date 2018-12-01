<?php
namespace App\Http\Controllers\Admin;

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
        $this->middleware('permission:product_references_create')->only(['store','create']);
        $this->middleware('permission:product_references_read')->only(['index','show']);
        $this->middleware('permission:product_references_update')->only(['update','edit']);
        $this->middleware('permission:product_references_delete')->only(['delete']);
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
            'product_references_read'=> $user->hasPermissionTo('product_references_read'),
            'product_references_update'=> $user->hasPermissionTo('product_references_update'),
            'product_references_delete'=> $user->hasPermissionTo('product_references_delete')
        ];
        $product_references = ProductReference::all()->map(function($item) use($permissions){
            $items= [                
                [   
                    'permission'=>'product_references_read',
                    'name'=>'Show',
                    'action'=>route('product_references.show',['id'=>$item->id]),
                    'icon'=>'fa fa-eye',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'product_references_update',
                    'name'=>'Edit',
                    'action'=>route('product_references.edit',['id'=>$item->id]),
                    'icon'=>'fa fa-edit',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'product_references_delete',
                    'name'=>'Delete', 
                    'data_id'=>$item->id,
                    'class_modal'=>'delete-modal',
                    'icon'=>'fa fa-trash-o',
                ],
            ];

            return[
                
                $item->id,
                $item->account_component_id,
                
                $item->product_id,
                
                $item->quantity,
                
                                view('settings.permissions', [
                    'items'=>$items,
                    'permissions'=>$permissions
                ])->render()
            ];
        })->all();
        return response()->json(['data'=> $product_references ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        
        $columns = "['Id','Account Component Id', 'Product Id', 'Quantity','Actions']";
        $link = 'product_references.dt';
        return view('admin.product_references.index', compact(['columns','link']));
        
    	//$product_references = ProductReference::all();
        //return view('admin.product_references.index', compact(['product_references']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product_references.create');
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

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_created');
		
		return redirect()->route('product_references.index')->with('alert', $alert);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\ProductReference  $product_reference
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, ProductReference $product_reference)
    {
        if (! $request->old()) {
            $request->replace($product_reference->toArray());        
            $request->flash();
        }

        return view('admin.product_references.show', compact(['product_reference']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    \App\ProductReference  $product_reference
     * @return  \Illuminate\Http\Response
     */
    public function edit(Request $request, ProductReference $product_reference)
    {
    	if (! $request->old()) {
            $request->replace($product_reference->toArray());        
            $request->flash();
        }

    	return view('admin.product_references.edit', compact(['product_reference']));
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

    	$alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_updated');;
	            
        return redirect()->route('product_references.index')->with('alert', $alert);

                
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

        return redirect()->route('product_references.index')->with('alert', $alert);
    }
}