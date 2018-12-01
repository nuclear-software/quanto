<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReference extends Model
{
    use SoftDeletes;
    
    protected $table = 'product_references';
    protected $fillable= ['account_component_id', 'product_id', 'quantity'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];

    
        
        
        
        
    public function accountComponent()
    {
        return $this->belongsTo('App\AccountComponent');
    }
    
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
            
}