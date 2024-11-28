<?php

namespace App\Controllers;

class IndicadoresController extends BaseController
{
    public function index()
    {
        // Carga la vista de indicadores
        return view('indicadores_index');
    }
}