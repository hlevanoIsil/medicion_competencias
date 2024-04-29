<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithProperties;


class InscripcionesExport implements FromView, WithProperties
{
    protected array $datos;
    protected array $params;
    public function properties(): array
    {
        return [
            'creator' => 'Harson Manager',
            'title' => 'Estudiantes pendientes de generar EA',
            'manager' => 'Harson University',
            'company' => 'Harson University',
        ];
    }

    public function view(): View
    {
        return view('reports.inscripciones')
            ->with('datos', $this->datos)
            ->with('params', $this->params);
    }
    /**
     * Set the value of datos
     *
     * @return self
     */
    public function setDatos(array $datos)
    {
        $this->datos = $datos;
        return $this;
    }

    /**
     * 
     *
     * @return self
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }
}
