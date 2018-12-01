@php echo '<?php'; @endphp

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
@if($model['flag_images'])

use Image;
use Storage;
@endif

class {!!$model['class_name']!!} extends Model
{
    use SoftDeletes;
    
    protected $table = '{!!$model['table']!!}';
    protected $fillable= {!! $model['fillable'] !!};
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = {!! $model['cast'] !!};

    @foreach($model['columns'] as $column)

    @if($column['element']=='image_upload')

    public function set{!! ucfirst(camel_case($column['name']))!!}Attribute($value)
    {   
        if ($value) {
            $image='';
            if ( starts_with($value, url('/img-cache')) ) {
                $image= class_basename($value);
            }else{
                $img= Image::make($value);
                $extension= getExtensionToMimeType($img->mime());
                do {
                    $name = str_random(30).'.'.$extension;

                } while ( Storage::exists('images/'.$name) );

                $path= 'images/'.$name;
                Storage::put($path, (string) $img->encode());
                //$img->save($path);
                $image= $name;
            }
            $this->attributes['{!!$column['name']!!}']= $image;
        }
    }

    public function get{{ ucfirst(camel_case($column['name']))}}Attribute($value)
    {        
        return url('img-cache/original/'.$value);
    }
    @elseif($column['element']=='images_upload')

    public function set{!! ucfirst(camel_case($column['name']))!!}Attribute($value)
    {   
        if ($value && is_array($value) ) {
            $images=[];

            foreach ($value as $image) {
                if ( starts_with($image, url('/img-cache')) ) {
                    $images[]= class_basename($image);
                }else{
                    $img= Image::make($image);
                    $extension= getExtensionToMimeType($img->mime());
                    do {
                        $name = str_random(30).'.'.$extension;

                    } while ( Storage::exists('images/'.$name) );

                    $path= 'images/'.$name;
                    Storage::put($path, (string) $img->encode());
                    //$img->save($path);
                    $images[]= $name;
                }
            }

            $this->attributes['{!!$column['name']!!}']=  json_encode($images);
        }
    }

    public function get{!! ucfirst(camel_case($column['name']))!!}Attribute($value)
    {
        $values=json_decode($value, true)?:[];
        
        $images=[];
        foreach ($values as $image) {
            $images[]= url('img-cache/original/'.$image);
        }
        
        return $images;
    }
    @endif
    @endforeach

    @if($model['relationships'])
    @foreach($model['relationships'] as $relationship)

    public function {!!$relationship['name']!!}()
    {
        return $this->{!!$relationship['type']!!}({!!$relationship['args']!!});
    }
    @endforeach
    @endif
    
}