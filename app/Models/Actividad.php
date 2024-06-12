<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Actividad extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $primaryKey = 'dni_docente';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'actividad';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dni_docente',
        'fecha_hora',
        'accion'
    ];


    public static function saveActividad($accion)
    {

        Actividad::updateOrCreate(
            [
                'dni_docente'   => Auth()->user()->dni
            ],
            [
                'fecha_hora' => date("Y-m-d H:i:s"),
                'accion' => $accion
            ]
        );
    }

    public static function getLast()
    {
        $rs = Actividad::where('dni_docente', Auth()->user()->dni)->first();

        $fecha = "";
        if ($rs) {
            $fecha = Carbon::createFromFormat('Y-m-d H:i:s', $rs->fecha_hora)->format('d, M Y ');
            $fecha .= " a las " . Carbon::createFromFormat('Y-m-d H:i:s', $rs->fecha_hora)->format('H:i');
        }
        return $fecha;
    }
}
