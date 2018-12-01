<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
    use SoftDeletes;
    
    protected $table = 'memberships';
    protected $fillable= ['company_id', 'user_quantity', 'table_quantity', 'establishment_quantity', 'expiry_date'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];
    
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
            
}