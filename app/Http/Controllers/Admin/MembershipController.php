<?php
namespace App\Http\Controllers\Admin;

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
        $this->middleware('permission:memberships_create')->only(['store','create']);
        $this->middleware('permission:memberships_read')->only(['index','show']);
        $this->middleware('permission:memberships_update')->only(['update','edit']);
        $this->middleware('permission:memberships_delete')->only(['delete']);
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
            'memberships_read'=> $user->hasPermissionTo('memberships_read'),
            'memberships_update'=> $user->hasPermissionTo('memberships_update'),
            'memberships_delete'=> $user->hasPermissionTo('memberships_delete')
        ];
        $memberships = Membership::all()->map(function($item) use($permissions){
            $items= [                
                [   
                    'permission'=>'memberships_read',
                    'name'=>'Show',
                    'action'=>route('memberships.show',['id'=>$item->id]),
                    'icon'=>'fa fa-eye',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'memberships_update',
                    'name'=>'Edit',
                    'action'=>route('memberships.edit',['id'=>$item->id]),
                    'icon'=>'fa fa-edit',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'memberships_delete',
                    'name'=>'Delete', 
                    'data_id'=>$item->id,
                    'class_modal'=>'delete-modal',
                    'icon'=>'fa fa-trash-o',
                ],
            ];

            return[
                
                $item->id,
                $item->company->name,
                
                $item->user_quantity,
                
                $item->table_quantity,
                
                $item->establishment_quantity,
                
                $item->expiry_date,
                
                                view('settings.permissions', [
                    'items'=>$items,
                    'permissions'=>$permissions
                ])->render()
            ];
        })->all();
        return response()->json(['data'=> $memberships ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        
        $columns = "['Id','Company Id', 'User Quantity', 'Table Quantity', 'Establishment Quantity', 'Expiry Date','Actions']";
        $link = 'memberships.dt';
        return view('admin.memberships.index', compact(['columns','link']));
        
    	//$memberships = Membership::all();
        //return view('admin.memberships.index', compact(['memberships']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.memberships.create');
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

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_created');
		
		return redirect()->route('memberships.index')->with('alert', $alert);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\Membership  $membership
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, Membership $membership)
    {
        if (! $request->old()) {
            $request->replace($membership->toArray());        
            $request->flash();
        }

        return view('admin.memberships.show', compact(['membership']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    \App\Membership  $membership
     * @return  \Illuminate\Http\Response
     */
    public function edit(Request $request, Membership $membership)
    {
    	if (! $request->old()) {
            $request->replace($membership->toArray());        
            $request->flash();
        }

    	return view('admin.memberships.edit', compact(['membership']));
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

    	$alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_updated');;
	            
        return redirect()->route('memberships.index')->with('alert', $alert);

                
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

        return redirect()->route('memberships.index')->with('alert', $alert);
    }
}