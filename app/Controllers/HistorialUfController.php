<?php

namespace App\Controllers;

use App\Models\HistorialUfModel;

class HistorialUfController extends BaseController
{
    public function index()
    {
        // Carga la vista de historial UF
        return view('historial_uf');
    }

    public function get()
    {
        $model = new HistorialUfModel();

        // Sincronizar automáticamente con la API
        $this->syncWithAPI();

        // Obtener todos los registros de la base de datos
        $data = $model->findAll();

        return $this->response->setJSON($data);
    }

    public function getById($id)
    {
        $model = new HistorialUfModel();
        $registro = $model->find($id);

        if ($registro) {
            return $this->response->setJSON($registro);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Registro no encontrado'], 404);
    }

    public function create()
    {
        $model = new HistorialUfModel();
        $data = [
            'fecha' => $this->request->getPost('fecha'),
            'valor' => $this->request->getPost('valor'),
        ];
        // Inserta un nuevo registro
        $model->insert($data);
        return $this->response->setJSON(['status' => 'success']);
    }

    public function edit($id)
    {
        $model = new HistorialUfModel();
        // Busca un registro por su ID
        $data = $model->find($id);
        return $this->response->setJSON($data);
    }

    public function update($id)
    {
        $model = new \App\Models\HistorialUfModel();

        // Captura los datos enviados desde el cliente
        $fecha = $this->request->getPost('fecha');
        $valor = $this->request->getPost('valor');

        // Validación básica
        if (empty($fecha) || empty($valor)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
        }

        // Actualiza el registro en la base de datos
        $model->update($id, [
            'fecha' => $fecha,
            'valor' => $valor
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }



    public function delete($id)
    {
        $model = new HistorialUfModel();
        // Elimina el registro especificado por ID
        $model->delete($id);
        return $this->response->setJSON(['status' => 'success']);
    }

    private function syncWithAPI()
    {
        $model = new HistorialUfModel();
        $apiUrl = 'https://mindicador.cl/api/uf';

        try {
            // Verificar si ya existen datos para hoy
            $hoy = date('Y-m-d');
            $existeHoy = $model->where('fecha', $hoy)->first();
            if ($existeHoy) {
                return; // No sincronizar si ya hay datos para hoy
            }

            // Consumir la API
            $response = file_get_contents($apiUrl);
            $data = json_decode($response, true);

            if (isset($data['serie'])) {
                $batchInsert = []; // Array para inserciones en lote

                foreach ($data['serie'] as $item) {
                    $fecha = substr($item['fecha'], 0, 10); // Formato YYYY-MM-DD
                    $valor = $item['valor'];

                    // Verificar si ya existe un registro para esta fecha
                    $existingRecord = $model->where('fecha', $fecha)->first();
                    if (!$existingRecord) {
                        $batchInsert[] = ['fecha' => $fecha, 'valor' => $valor];
                    }
                }

                // Insertar en lote si hay nuevos registros
                if (!empty($batchInsert)) {
                    $model->insertBatch($batchInsert);
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al sincronizar con la API: ' . $e->getMessage());
        }
    }
}
