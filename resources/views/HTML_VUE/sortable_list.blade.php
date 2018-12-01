<div class="box box-primary">
	<div class="box-header">
	    <h3 class="box-title">{!!isset($label)?$label:'Order List'!!}</h3>
	</div>
	<div class="box-body">
	    <draggable v-model="{!!$options!!}" :options="{draggable:'li'}" element="ul" class="todo-list">
	        <li v-for="(element, key) in {!!$options!!}">
	            <span class="handle">
	                <i class="fa fa-ellipsis-v"></i>
	                <i class="fa fa-ellipsis-v"></i>
	            </span>
	            <input type="checkbox" 
	            	   :name="{!! $name !!}+'[]'"
	            	   :value="element.{!!isset($item_value)?$item_value:'id'!!}" 
	            	   v-model="{!!$model!!}"
	            	   v-validate="'{!!isset($required)?'required':''!!}'">
        	   	@php 
        	   		$item_text= isset($item_text) ? $item_text:'id'; 
        	   	@endphp

	            <span class="text">{!! "{{element.".$item_text.'}'.'}' !!}</span>
	        </li>
	    </draggable>
	    <input v-if="{!!$model!!}.length==0" type="hidden" :name="{!! $name !!}" v-validate="'{!!isset($required)?'required':''!!}'">
	</div>
	<div class="box-footer">		
		<div v-if="{!!$model!!}.length>0" class="form-group" :class="{'has-error': errors.has({!! $name."+'[]'" !!}) }">
		    <span class="help-block" v-if="errors.has({!!$name."+'[]'"!!})">{!! "{{errors.first(".$name."+'[]')".'}'.'}' !!}</span>
		</div>
		<div class="form-group" :class="{'has-error': errors.has({!!$name!!}) }">
		    <span class="help-block" v-if="errors.has({!!$name!!})">{!! "{{errors.first(".$name.")".'}'.'}' !!}</span>
		</div>
	</div>
</div>
