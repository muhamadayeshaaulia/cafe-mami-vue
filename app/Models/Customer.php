<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Schema;

class Customer extends Model
{
    //
    use HasFactory;

    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable =[
        'name','no_telp','adderss'
    ];

}
