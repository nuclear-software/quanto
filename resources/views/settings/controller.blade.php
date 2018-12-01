@php echo '<?php'; @endphp

namespace App\Http\Controllers{!!$controller['namespace']!!};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use {!!$controller['model_path']!!};

class {!!$controller['class_name']!!} extends Controller
{

	/**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:{!!$controller['model_plural_name']!!}_create')->only(['store','create']);
        $this->middleware('permission:{!!$controller['model_plural_name']!!}_read')->only(['index','show']);
        $this->middleware('permission:{!!$controller['model_plural_name']!!}_update')->only(['update','edit']);
        $this->middleware('permission:{!!$controller['model_plural_name']!!}_delete')->only(['delete']);
    }

    /**
     * Display a listing of the resource in json format.
     *
     * @return \Illuminate\Http\Response
     */
    public function toDatatable(Request $request)
    {
        $user= $request->user();
        $permissions=[
            '{!!$controller['model_plural_name']!!}_read'=> $user->hasPermissionTo('{!!$controller['model_plural_name']!!}_read'),
            '{!!$controller['model_plural_name']!!}_update'=> $user->hasPermissionTo('{!!$controller['model_plural_name']!!}_update'),
            '{!!$controller['model_plural_name']!!}_delete'=> $user->hasPermissionTo('{!!$controller['model_plural_name']!!}_delete')
        ];
        ${!!$controller['model_plural_name']!!} = {!!$controller['model']!!}::all()->map(function($item) use($permissions){
            $items= [                
                [   
                    'permission'=>'{!!$controller['model_plural_name']!!}_read',
                    'name'=>'Show',
                    'action'=>route('{!!$controller['model_plural_name']!!}.show',['id'=>$item->id]),
                    'icon'=>'fa fa-eye',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'{!!$controller['model_plural_name']!!}_update',
                    'name'=>'Edit',
                    'action'=>route('{!!$controller['model_plural_name']!!}.edit',['id'=>$item->id]),
                    'icon'=>'fa fa-edit',
                    'target'=>'_self',
                ],
                [
                    'permission'=>'{!!$controller['model_plural_name']!!}_delete',
                    'name'=>'Delete', 
                    'data_id'=>$item->id,
                    'class_modal'=>'delete-modal',
                    'icon'=>'fa fa-trash-o',
                ],
            ];

            return[
                
                $item->id,
                @foreach($controller['columns'] as $column)$item->{!!$column['name']!!},
                
                @endforeach
                view('settings.permissions', [
                    'items'=>$items,
                    'permissions'=>$permissions
                ])->render()
            ];
        })->all();
        return response()->json(['data'=> ${!!$controller['model_plural_name']!!} ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        @if($controller['is_unique'])

        return redirect()->route('{!!$controller['model_plural_name'].'.edit'!!}',1);
        //$columns = "{!!$controller['columns_names']!!}";
        //$link = '{!!$controller['model_plural_name']!!}.dt';
        //return view('{!!$controller['view'].'.index'!!}', compact(['columns','link']));
        @else

        $columns = "{!!$controller['columns_names']!!}";
        $link = '{!!$controller['model_plural_name']!!}.dt';
        return view('{!!$controller['view'].'.index'!!}', compact(['columns','link']));
        @endif

    	//${!!$controller['model_plural_name']!!} = {!!$controller['model']!!}::all();
        //return view('{!!$controller['view'].'.index'!!}', compact(['{!!$controller['model_plural_name']!!}']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('{!!$controller['view'].'.create'!!}');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([

	        @foreach($controller['columns'] as $column)'{!!$column['name']!!}'=> '{!!$column['validate']!!}',
            @endforeach

	    ]);

	    ${!!$controller['model_single_name']!!} = {!!$controller['model']!!}::create($request->all());

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_created');
		
		return redirect()->route('{!!$controller['route']!!}')->with('alert', $alert);
    }

    /**
     * Display the specified resource.
     *
     * @param  \{!!$controller['model_path']!!}  ${!!$controller['model_single_name']!!}
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, {!!$controller['model']!!} ${!!$controller['model_single_name']!!})
    {
        if (! $request->old()) {
            $request->replace(${!!$controller['model_single_name']!!}->toArray());        
            $request->flash();
        }

        return view('{!!$controller['view'].'.show'!!}', compact(['{!!$controller['model_single_name']!!}']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \{!!$controller['model_path']!!}  ${!!$controller['model_single_name']!!}
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, {!!$controller['model']!!} ${!!$controller['model_single_name']!!})
    {
    	if (! $request->old()) {
            $request->replace(${!!$controller['model_single_name']!!}->toArray());        
            $request->flash();
        }

    	return view('{!!$controller['view'].'.edit'!!}', compact(['{!!$controller['model_single_name']!!}']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \{!!$controller['model_path']!!}  ${!!$controller['model_single_name']!!}
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, {!!$controller['model']!!} ${!!$controller['model_single_name']!!})
    {
        $validatedData = $request->validate([

	        @foreach($controller['columns'] as $column)'{!!$column['name']!!}'=> '{!!$column['validate']!!}',
            @endforeach

	    ]);

    	${!!$controller['model_single_name']!!}->fill($request->all())->save();

    	$alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_updated');;
	    @if($controller['is_unique'])

		return redirect()->route('{!!$controller['model_plural_name'].'.edit'!!}',1)->with('alert', $alert);

        @else
        
        return redirect()->route('{!!$controller['route']!!}')->with('alert', $alert);

        @endif
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \{!!$controller['model_path']!!}  ${!!$controller['model_single_name']!!}
     * @return \Illuminate\Http\Response
     */
    public function destroy({!!$controller['model']!!} ${!!$controller['model_single_name']!!})
    {
        ${!!$controller['model_single_name']!!}->delete();

        $alert=[];
        $alert['status']= 'success';
        $alert['message']= trans('message.successfully_deleted');

        return redirect()->route('{!!$controller['route']!!}')->with('alert', $alert);
    }
}