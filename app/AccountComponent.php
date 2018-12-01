<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountComponent extends Model
{
    use SoftDeletes;
    
    protected $table = 'account_components';
    protected $fillable= ['account_id', 'estado'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];

    
        
        
        
    public function account()
    {
        return $this->belongsTo('App\Account');
    }
    
    public function productReferences()
    {
        return $this->hasMany('App\ProductReference');
    }
            
}