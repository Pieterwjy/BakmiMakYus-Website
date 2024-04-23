<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $table = 'tables';

    protected $primaryKey = 'table_id';

    protected $fillable = ['table_number','table_capacity','table_qr'];
}
