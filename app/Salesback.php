<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salesback extends Model
{
    protected $fillable=[
    	'invoice_id','dates','customer_id','category_id','product_id','qantity','price','micro_time'
    ];
}
