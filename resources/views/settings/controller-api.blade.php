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
        $this->middleware('permission:{!!$controller['model_plural_name']!!}_create')->only(['store']);
        $this->middleware('permission:{!!$controller['model_plural_name']!!}_read')->only(['index','show']);
        $this->middleware('permission:{!!$controller['model_plural_name']!!}_update')->only(['update']);
        $this->middleware('permission:{!!$controller['model_plural_name']!!}_delete')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	${!!$controller['model_plural_name']!!} = {!!$controller['model']!!}::all();
        return response()->json(${!!$controller['model_plural_name']!!});
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
		
		return response()->json(${!!$controller['model_single_name']!!});
    }

    /**
     * Display the specified resource.
     *
     * @param  \{!!$controller['model_path']!!}  ${!!$controller['model_single_name']!!}
     * @return \Illuminate\Http\Response
     */
    public function show({!!$controller['model']!!} ${!!$controller['model_single_name']!!})
    {
        return response()->json(${!!$controller['model_single_name']!!});
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
        
        return response()->json(${!!$controller['model_single_name']!!});
        
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

        return response()->json($alert);
    }
}