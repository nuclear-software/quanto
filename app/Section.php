<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'sections';
    protected $fillable= ['display_name', 'icon', 'route', 'permission'];
    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];
    protected $dates= ['created_at', 'updated_at', 'deleted_at'];
}
