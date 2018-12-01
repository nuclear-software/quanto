<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Establishment extends Model
{
    use SoftDeletes;
    
    protected $table = 'establishments';
    protected $fillable= ['company_id', 'name', 'location'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];

    
        
        
        
        
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    
    public function establishmentTables()
    {
        return $this->hasMany('App\EstablishmentTable');
    }
            
}