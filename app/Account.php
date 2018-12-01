<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;
    
    protected $table = 'accounts';
    protected $fillable= ['establishment_table_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];

    
        
        
    public function establishmentTable()
    {
        return $this->belongsTo('App\EstablishmentTable');
    }
    
    public function accountComponents()
    {
        return $this->hasMany('App\AccountComponent');
    }
            
}