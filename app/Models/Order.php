<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $primaryKey = 'id';
    protected $fillable = ['table_number','order_type','notes',
    'order_status','gross_amount','status','snap_token'];

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
