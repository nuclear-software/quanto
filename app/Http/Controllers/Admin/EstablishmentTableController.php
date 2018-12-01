<?php
namespace App\Http\Controllers\Admin;

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
        $this->middleware('permission:establishment_tables_create')->only(['store','create']);
        $this->middleware('permission:establishment_tables_read')->only(['index','show']);
        $this->middleware('permission:establishment_tables_update')->only(['update','edit']);
        $this->middleware('permission:establishment_tables_delete')->only(['delete']);
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
            'establishment_tables_read'=> $user->hasPermissionTo('establishment_tables_read'),
            'establishment_tables_update'=> $user->hasPermissionTo('establishment_tables_update'),
            'establishment_tables_delete'=> $user->hasPermissionTo('establishment_tables_delete')
        ];
        $establishment_tables = EstablishmentTable::all()->map(function($item) use($permissions){
            $items= [                
                [   
                    'permission'=>'establishment_tables_read',
                    'name'=>'Show',
                    'action'=>route('establishment_tables.show',['id'=>$item->id]),
                    'icon'=>'fa fa-eye',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'establishment_tables_update',
                    'name'=>'Edit',
                    'action'=>route('establishment_tables.edit',['id'=>$item->id]),
                    'icon'=>'fa fa-edit',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'establishment_tables_delete',
                    'name'=>'Delete', 
                    'data_id'=>$item->id,
                    'class_modal'=>'delete-modal',
                    'icon'=>'fa fa-trash-o',
                ],
            ];

            return[
                
                $item->id,
                $item->establishment->company->name,
                $item->establishment->name,
                
                $item->name,
                
                                view('settings.permissions', [
                    'items'=>$items,
                    'permissions'=>$permissions
                ])->render()
            ];
        })->all();
        return response()->json(['data'=> $establishment_tables ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        
        $columns = "['Id','Company','Establishment Id', 'Name','Actions']";
        $link = 'establishment_tables.dt';
        return view('admin.establishment_tables.index', compact(['columns','link']));
        
    	//$establishment_tables = EstablishmentTable::all();
        //return view('admin.establishment_tables.index', compact(['establishment_tables']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.establishment_tables.create');
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

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_created');
		
		return redirect()->route('establishment_tables.index')->with('alert', $alert);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\EstablishmentTable  $establishment_table
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, EstablishmentTable $establishment_table)
    {
        if (! $request->old()) {
            $request->replace($establishment_table->toArray());        
            $request->flash();
        }

        return view('admin.establishment_tables.show', compact(['establishment_table']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    \App\EstablishmentTable  $establishment_table
     * @return  \Illuminate\Http\Response
     */
    public function edit(Request $request, EstablishmentTable $establishment_table)
    {
    	if (! $request->old()) {
            $request->replace($establishment_table->toArray());        
            $request->flash();
        }

    	return view('admin.establishment_tables.edit', compact(['establishment_table']));
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

    	$alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_updated');;
	            
        return redirect()->route('establishment_tables.index')->with('alert', $alert);

                
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

        return redirect()->route('establishment_tables.index')->with('alert', $alert);
    }
}