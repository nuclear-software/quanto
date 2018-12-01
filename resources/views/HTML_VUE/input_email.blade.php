<div class="form-group" :class="{'has-error': errors.has('{{ $name }}') }">
    <label for="{{ $name }}">{{$label}}</label>
    <input class="form-control input-sm"
    	   name="{{ $name }}"
    	   type="text"
           v-validate="{{$validate}}"
    	   placeholder="{{$label}}"
    	   value="{{old($name)?:''}}"
    	   autocomplete="off">
    <span class="help-block" v-if="errors.has('{{ $name }}')">{{ "{{errors.first('".$name."')".'}'.'}' }}</span>
    {{-- @include('HTML.error', ['name' => $name ]) --}}
</div>