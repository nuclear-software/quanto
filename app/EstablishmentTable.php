<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstablishmentTable extends Model
{
    use SoftDeletes;
    
    protected $table = 'establishment_tables';
    protected $fillable= ['establishment_id', 'name'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];

    
        
        
        
    public function establishment()
    {
        return $this->belongsTo('App\Establishment');
    }
            
}