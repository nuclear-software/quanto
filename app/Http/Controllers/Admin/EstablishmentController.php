<?php
namespace App\Http\Controllers\Admin;

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
        $this->middleware('permission:establishments_create')->only(['store','create']);
        $this->middleware('permission:establishments_read')->only(['index','show']);
        $this->middleware('permission:establishments_update')->only(['update','edit']);
        $this->middleware('permission:establishments_delete')->only(['delete']);
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
            'establishments_read'=> $user->hasPermissionTo('establishments_read'),
            'establishments_update'=> $user->hasPermissionTo('establishments_update'),
            'establishments_delete'=> $user->hasPermissionTo('establishments_delete')
        ];
        $establishments = Establishment::all()->map(function($item) use($permissions){
            $items= [                
                [   
                    'permission'=>'establishments_read',
                    'name'=>'Show',
                    'action'=>route('establishments.show',['id'=>$item->id]),
                    'icon'=>'fa fa-eye',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'establishments_update',
                    'name'=>'Edit',
                    'action'=>route('establishments.edit',['id'=>$item->id]),
                    'icon'=>'fa fa-edit',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'establishments_delete',
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
                
                $item->location,
                
                view('settings.permissions', [
                    'items'=>$items,
                    'permissions'=>$permissions
                ])->render()
            ];
        })->all();
        return response()->json(['data'=> $establishments ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        
        $columns = "['Id','Company Id', 'Name', 'Location','Actions']";
        $link = 'establishments.dt';
        return view('admin.establishments.index', compact(['columns','link']));
        
    	//$establishments = Establishment::all();
        //return view('admin.establishments.index', compact(['establishments']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.establishments.create');
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

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_created');
		
		return redirect()->route('establishments.index')->with('alert', $alert);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\Establishment  $establishment
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, Establishment $establishment)
    {
        if (! $request->old()) {
            $request->replace($establishment->toArray());        
            $request->flash();
        }

        return view('admin.establishments.show', compact(['establishment']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    \App\Establishment  $establishment
     * @return  \Illuminate\Http\Response
     */
    public function edit(Request $request, Establishment $establishment)
    {
    	if (! $request->old()) {
            $request->replace($establishment->toArray());        
            $request->flash();
        }

    	return view('admin.establishments.edit', compact(['establishment']));
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

    	$alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_updated');;
	            
        return redirect()->route('establishments.index')->with('alert', $alert);

                
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

        return redirect()->route('establishments.index')->with('alert', $alert);
    }
}