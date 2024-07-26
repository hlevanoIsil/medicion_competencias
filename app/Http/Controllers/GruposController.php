<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Actividad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use stdClass;

class GruposController extends Controller
{

    public static function listNrcsDocente(Request $request)
    {
        $data_nrc = [];
        $json_nrcs = Storage::disk('local')->get('nrc_docente.json');
        $nrc_docentes = json_decode($json_nrcs, true)['nrc_docentes'];

        //DNI DE PRUEBA CON NRCS = 06016500
        $dni = Auth()->user()->dni;
        //$dni = '07732571';
        $periodo = $request->session()->get('periodo');
        //$dni = '10253131';

        $nrc_docentes = array_filter($nrc_docentes, function ($nrc) use ($dni) {
            return $nrc['DNI'] == $dni;
        });
        foreach ($nrc_docentes as $item) {
            array_push($data_nrc, $item);
        }

        $pdo = DB::connection('oracle')->getPdo();
        $stmt = $pdo->prepare("BEGIN ISIL.SP_MEDICIONCOMP_LISTAR_NRC_X_DOCENTES(:periodo, :dni, :cursor); END;");
        $stmt->bindParam(':periodo', $periodo, \PDO::PARAM_STR);
        $stmt->bindParam(':dni', $dni, \PDO::PARAM_STR);
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT);
        $stmt->execute();
        oci_execute($cursor);

        oci_fetch_all($cursor, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        oci_free_statement($cursor);
        $stmt->closeCursor();
        
        $collection1 = collect($data);
        $collection2 = collect($data_nrc);
        $intersected = $collection1->intersectByKeys($collection2, function ($item1, $item2) {
            return $item1['NRC'] <=> $item2['NRC'];
        });
        $result = $intersected->values()->all();

        $actividad = Actividad::getLast();
        //dd($actividad['fecha_hora']);
        return [
            "data" => $result,
            'rows' => count($result),
            'actividad' => $actividad
        ];
    }
    public static function listNrcsJurados(Request $request)
    {
        //DNI DE PRUEBA CON NRCS = 06016500
        $dni = Auth()->user()->dni;

        //$dni = '09588929';
        $periodo = $request->session()->get('periodo');


        $pdo = DB::connection('oracle')->getPdo();
        $stmt = $pdo->prepare("BEGIN ISIL.SP_MEDICIONCOMP_LISTAR_NRC_X_JURADO(:periodo, :dni, :cursor); END;");
        $stmt->bindParam(':periodo', $periodo, \PDO::PARAM_STR);
        $stmt->bindParam(':dni', $dni, \PDO::PARAM_STR);
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT);
        $stmt->execute();
        oci_execute($cursor);

        oci_fetch_all($cursor, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        oci_free_statement($cursor);
        $stmt->closeCursor();
        //dd($data);
        return [
            "data" => $data,
            'rows' => count($data)
        ];
    }
    public static function listGrupos(Request $request)
    {
        //dd($request['nrc']);
        //dd($request);
        /* $page = $request['page'];
        $itemsPerPage = $request['itemsPerPage'];
        $init = ($page - 1) * $itemsPerPage;
        */
        //registra actividad
        Actividad::saveActividad('Listar grupos');
        /** Parametros */
        $nrc = $request['nrc'];

        $periodo = $request->session()->get('periodo');
        //$nrc = '1001';

        $pdo = DB::connection('oracle')->getPdo();
        $stmt = $pdo->prepare("BEGIN ISIL.SP_MEDICIONCOMP_LISTAR_GRUPOS_X_NRC(:periodo, :nrc, :cursor); END;");
        $stmt->bindParam(':periodo', $periodo, \PDO::PARAM_STR);
        $stmt->bindParam(':nrc', $nrc, \PDO::PARAM_STR);
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT);
        $stmt->execute();
        oci_execute($cursor);

        oci_fetch_all($cursor, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        oci_free_statement($cursor);
        $stmt->closeCursor();

        $grupos = [];
        $cont = 0;
        $grupos[$cont]['nom_grupo'] = "";
        $grupos[$cont]['cod_grupo'] = "";
        $cont++;
        foreach ($data as $dato) {
            $grupos[$cont]['nom_grupo'] = $dato['GRUPO'];
            $grupos[$cont]['cod_grupo'] = $dato['GRUPO_SOLO'];
            $cont++;
        }

        return [
            "data" => $data,
            "grupos" => $grupos,
            'rows' => count($data)
        ];
    }

    public static function listAlumnos(Request $request)
    {
        Actividad::saveActividad('Listar alumnos');
        //dd($request);
        /* $page = $request['page'];
        $itemsPerPage = $request['itemsPerPage'];
        $init = ($page - 1) * $itemsPerPage;
        */
        /** Parametros */
        //$periodo = $request['periodo'];
        $nrc = $request['nrc'];
        $periodo = $request->session()->get('periodo');

        $grupo = $request['grupo'] ?? null;
        $dni = $request['dni'] ?? null;
        //$nrc = '1001';

        $pdo = DB::connection('oracle')->getPdo();
        $stmt = $pdo->prepare("BEGIN ISIL.SP_MEDICIONCOMP_LISTAR_ALUMNOS_X_NRC(:periodo, :nrc, :grupo, :dni, :cursor); END;");
        $stmt->bindParam(':periodo', $periodo, \PDO::PARAM_STR);
        $stmt->bindParam(':nrc', $nrc, \PDO::PARAM_STR);
        $stmt->bindParam(':grupo', $grupo, \PDO::PARAM_STR);
        $stmt->bindParam(':dni', $dni, \PDO::PARAM_STR);
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT);
        $stmt->execute();
        oci_execute($cursor);

        oci_fetch_all($cursor, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        oci_free_statement($cursor);
        $stmt->closeCursor();

        $dnis = [];
        $apellidos = [];
        $nombres = [];
        $cont = 0;
        $dnis[$cont]['dni'] = "TODOS";
        $apellidos[$cont]['apellido'] = "TODOS";
        $nombres[$cont]['nombre'] = "TODOS";
        $cont++;
        foreach ($data as $fila) {
            $dnis[$cont]['dni'] = $fila['DNI'];
            $apellidos[$cont]['apellido'] = $fila['SPRIDEN_LAST_NAME'];
            $nombres[$cont]['nombre'] = $fila['SPRIDEN_FIRST_NAME'];
            $cont++;
        }
        //dd($dnis);
        //$dnis = array_column($data, 'DNI', 'dnis');
        //$apellidos = array_column($data, 'SPRIDEN_LAST_NAME');
        //$nombres = array_column($data, 'SPRIDEN_FIRST_NAME');

        return [
            "data" => $data,
            "dnis" => $dnis,
            "apellidos" => $apellidos,
            "nombres" => $nombres,
            'rows' => count($data)
        ];
    }

    public static function agregarAlumnos(Request $request)
    {
        if ($request->pidm == "") {
            return response(['msj' => "Seleccione estudiante", 'cod' => 1], 422);
        }

        //dd($request);
        Actividad::saveActividad('Agregar alumno');

        try {
            $status = null;
            $message = null;

            //$lang = $request->language == 'E' ? 1 : 2;
            $lang = $request->language == 2;


            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_AGREGAR_ALUMNO_GRUPO(?, ?, ?, ?, ?)', [
                $request->grupo,
                $request->nrc, // carrera
                $request->session()->get('periodo'), // periodo
                $request->pidm,
                1
                //&$status,
                //&$message
            ]);
        } catch (\Exception $e) {
            //dd ($e);
        }
        $ret['mensaje'] = "Se agregó al estudiante correctamente";
        return response($ret, Response::HTTP_OK);
    }

    public static function agregarMasivo(Request $request)
    {
        //dd($request->nrc);
        Actividad::saveActividad('Agrega alumnos masivamente');

        foreach ($request['elegidos'] as $pidm) {
            //dd($pidm['pidm']);
            try {
                $status = null;
                $message = null;

                //$lang = $request->language == 'E' ? 1 : 2;
                $lang = $request->language == 2;


                DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_AGREGAR_ALUMNO_GRUPO(?, ?, ?, ?, ?)', [
                    $request->grupo_masivo,
                    $request->nrc, // carrera
                    $request->session()->get('periodo'), // periodo
                    $pidm['pidm'],
                    1
                    //&$status,
                    //&$message
                ]);
            } catch (\Exception $e) {
                //dd ($e);
            }
        }
        $ret['mensaje'] = "Se agregaron los estudiantes al grupo seleccionado correctamente";
        return response($ret, Response::HTTP_OK);
    }

    public static function eliminarAlumnoGrupo(Request $request)
    {
        Actividad::saveActividad('eliminar alumno de grupo');
        //dd($request);
        try {
            $status = null;
            $message = null;

            //$lang = $request->language == 'E' ? 1 : 2;
            $lang = $request->language == 2;


            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_AGREGAR_ALUMNO_GRUPO(?, ?, ?, ?, ?)', [
                $request->grupo,
                $request->nrc, // carrera
                $request->session()->get('periodo'), // periodo
                $request->pidmDrop,
                0
                //&$status,
                //&$message
            ]);
        } catch (\Exception $e) {
            //dd ($e);
        }
        $ret['mensaje'] = "Se eliminó al estudiante correctamente";
        return response($ret, Response::HTTP_OK);
    }
    public static function eliminarGrupo(Request $request)
    {
        //dd($request);
        Actividad::saveActividad('Elimina grupo');

        try {
            $status = null;
            $message = null;

            //$lang = $request->language == 'E' ? 1 : 2;
            $lang = $request->language == 2;


            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_GROUP_DELETE(?, ?, ?)', [
                $request->itemGrupoDrop,
                $request->nrc, // carrera
                $request->session()->get('periodo'), // periodo
            ]);
        } catch (\Exception $e) {
            //dd ($e);
        }
        $ret['mensaje'] = "Se eliminó el grupo correctamente";
        return response($ret, Response::HTTP_OK);
    }

    public static function generarGrupos(Request $request)
    {
        //dd($request['totGrupos']);
        //if ((int)$request['num_grupos'] < 1 || (int)$request['num_grupos'] > 50) {
        if ((int)$request['num_grupos'] < $request['totGrupos'] || (int)$request['num_grupos'] > 50) {
            return response(['msj' => "El número de grupos a crear debe ser un dígito entre " . $request['totGrupos'] . "y 50", 'cod' => 1], 422);
        }
        Actividad::saveActividad('Generar grupo');

        try {
            $status = null;
            $message = null;

            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_CREAR_GRUPOS(?, ?, ?)', [
                $request->num_grupos,
                $request->nrc, // carrera
                $request->session()->get('periodo'), // periodo
            ]);
        } catch (\Exception $e) {
            //dd($e);
        }
        $ret['mensaje'] = "Se generaron los grupos correctamente";
        return response($ret, Response::HTTP_OK);
    }
}
