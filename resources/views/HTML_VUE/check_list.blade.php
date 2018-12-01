<div class="form-group">
    <label>{{title_case(str_replace('_', ' ',$input['name']))}}</label>
  	<div class="box box-primary">
		<div class="box-header">
		    <i class="ion ion-clipboard"></i>
		    <h3 class="box-title">Check list</h3>
		</div>
	    <div class="box-body">
		    <ul class="todo-list">
		    	@foreach($input['options'] as $item)
		        <li>
	              	<span class="handle">
		                <i class="fa fa-ellipsis-v"></i>
		                <i class="fa fa-ellipsis-v"></i>
	              	</span>
		         
		          	<input type="checkbox" name="{{$input['name'].'[]'}}" value="{{$item[$input['value']]}}" {{old($input['name'])?(in_array($item[$input['value']], old($input['name']))?'checked':''):''}}>
		          
		          	<span class="text">{{strtoupper($item[$input['display']])}}</span>
		      	</li>
		      	@endforeach		      
		    </ul>
	  	</div> 	
	</div>
</div>
