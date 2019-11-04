<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class company extends Model
{  
protected $table = 'business';
    protected $primaryKey = 'idbusiness';
    protected $fillable = [
        'company_name',
        'address',
        'nit',
        'phone',
        'logo',
    ];
}
