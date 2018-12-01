<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Image;
use Storage;

class Product extends Model
{
    use SoftDeletes;
    
    protected $table = 'products';
    protected $fillable= ['company_id', 'name', 'image', 'price'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];

    
        
        
    
    public function setImageAttribute($value)
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
            $this->attributes['image']= $image;
        }
    }

    public function getImageAttribute($value)
    {        
        return url('img-cache/original/'.$value);
    }
        
        
        
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
            
}