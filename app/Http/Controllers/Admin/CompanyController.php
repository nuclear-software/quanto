<?php
namespace App\Http\Controllers\Admin;

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
        $this->middleware('permission:companies_create')->only(['store','create']);
        $this->middleware('permission:companies_read')->only(['index','show']);
        $this->middleware('permission:companies_update')->only(['update','edit']);
        $this->middleware('permission:companies_delete')->only(['delete']);
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
            'companies_read'=> $user->hasPermissionTo('companies_read'),
            'companies_update'=> $user->hasPermissionTo('companies_update'),
            'companies_delete'=> $user->hasPermissionTo('companies_delete')
        ];
        $companies = Company::all()->map(function($item) use($permissions){
            $items= [                
                [   
                    'permission'=>'companies_read',
                    'name'=>'Show',
                    'action'=>route('companies.show',['id'=>$item->id]),
                    'icon'=>'fa fa-eye',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'companies_update',
                    'name'=>'Edit',
                    'action'=>route('companies.edit',['id'=>$item->id]),
                    'icon'=>'fa fa-edit',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'companies_delete',
                    'name'=>'Delete', 
                    'data_id'=>$item->id,
                    'class_modal'=>'delete-modal',
                    'icon'=>'fa fa-trash-o',
                ],
            ];

            return[
                
                $item->id,
                $item->name,
                
                                view('settings.permissions', [
                    'items'=>$items,
                    'permissions'=>$permissions
                ])->render()
            ];
        })->all();
        return response()->json(['data'=> $companies ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        
        $columns = "['Id','Name','Actions']";
        $link = 'companies.dt';
        return view('admin.companies.index', compact(['columns','link']));
        
    	//$companies = Company::all();
        //return view('admin.companies.index', compact(['companies']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.companies.create');
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

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_created');
		
		return redirect()->route('companies.index')->with('alert', $alert);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\Company  $company
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, Company $company)
    {
        if (! $request->old()) {
            $request->replace($company->toArray());        
            $request->flash();
        }

        return view('admin.companies.show', compact(['company']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    \App\Company  $company
     * @return  \Illuminate\Http\Response
     */
    public function edit(Request $request, Company $company)
    {
    	if (! $request->old()) {
            $request->replace($company->toArray());        
            $request->flash();
        }

    	return view('admin.companies.edit', compact(['company']));
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

    	$alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_updated');;
	            
        return redirect()->route('companies.index')->with('alert', $alert);

                
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

        return redirect()->route('companies.index')->with('alert', $alert);
    }
}