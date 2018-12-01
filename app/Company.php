<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    
    protected $table = 'companies';
    protected $fillable= ['name'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];
        
    public function establishments()
    {
        return $this->hasMany('App\Establishment');
    }
    
    public function products()
    {
        return $this->hasMany('App\Product');
    }
    
    public function membership()
    {
        return $this->hasOne('App\Membership');
    }
            
}