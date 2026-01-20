<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErpProduct extends Model
{
    protected $connection = 'sqlsrv_erp'; // conexión al ERP
    protected $table = 'VIEW_ECOMMERCE_ARTICULOS_UNIFICADO'; // nombre exacto de la vista
    public $timestamps = false; // si la vista no tiene created_at/updated_at

    protected $primaryKey = null;
    public $incrementing = false;
}
