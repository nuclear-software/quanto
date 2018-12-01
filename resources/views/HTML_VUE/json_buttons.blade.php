{!!"\t\t\t\t"!!}<div class="form-group">
{!!"\t\t\t\t"!!}	<div class="pull-right">
{!!"\t\t\t\t"!!}		<a role="button" type="button" v-on:click="add{!!up_camel_case($name)!!}" class="btn btn-primary btn-xs">
{!!"\t\t\t\t"!!}			<span class="glyphicon-plus glyphicon"></span>
{!!"\t\t\t\t"!!}		</a>
{!!"\t\t\t\t"!!}		<a role="button" v-on:click="remove{!!up_camel_case($name)!!}" v-show="{!!$name!!}.length > {!!$required?1:0!!}" class="btn btn-danger btn-xs">
{!!"\t\t\t\t"!!}			<span class="glyphicon-minus glyphicon"></span>
{!!"\t\t\t\t"!!}		</a>
{!!"\t\t\t\t"!!}	</div>
{!!"\t\t\t\t"!!}	<label>{{title_case($name)}}</label>
{!!"\t\t\t\t"!!}</div>