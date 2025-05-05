<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacrion extends Model
{
    //
    use HasFactory;
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'cashier_id', 'customer_id', 'invoice', 'cash', 'change', 'discount', 'grand_total'
    ];
}
