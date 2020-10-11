<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    Protected $fillable=[
    	'dates','customer_id','total_item','discount','vat','labour_cost','total_payable','total','micro_time'
    ];
}
