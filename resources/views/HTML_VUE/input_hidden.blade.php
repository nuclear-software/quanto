
    <input name="{{ $input['name'] }}" type="hidden" value="{{old($input['name'])}}">
    @include('HTML.error', ['name' => $input['name'] ])
