<?php

namespace App\Models;

use CodeIgniter\Model;

class HistorialUfModel extends Model
{
    protected $table = 'historial_uf';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fecha', 'valor'];
}