<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchasebacks extends Model
{
    protected $fillable=[
    	'dates','invoice_id','supplier_id','product_id','qantity','price','micro_time'
    ];
}
