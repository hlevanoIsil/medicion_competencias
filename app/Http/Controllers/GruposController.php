<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GruposController extends Controller
{

    public static function listNrcsDocente(Request $request)
    {

        $periodo = $request['periodo'];
        //DNI DE PRUEBA CON NRCS = 06016500
        $dni = Auth()->user()->dni;
        $periodo = '202310';
        //$dni = '10253131';

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
        return [
            "data" => $data,
            'rows' => count($data)
        ];
    }
    public static function listNrcsJurados(Request $request)
    {

        $periodo = $request['periodo'];
        //DNI DE PRUEBA CON NRCS = 06016500
        $dni = Auth()->user()->dni;
        //$dni = '09588929';
        $periodo = '202310';

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
        /** Parametros */
        $periodo = $request['periodo'];
        $nrc = $request['nrc'];

        $periodo = '202310';
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
        //dd($request);
        /* $page = $request['page'];
        $itemsPerPage = $request['itemsPerPage'];
        $init = ($page - 1) * $itemsPerPage;
        */
        /** Parametros */
        $periodo = $request['periodo'];
        $nrc = $request['nrc'];
        $periodo = '202310';
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
        //dd($request);
        try {
            $status = null;
            $message = null;

            //$lang = $request->language == 'E' ? 1 : 2;
            $lang = $request->language == 2;


            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_AGREGAR_ALUMNO_GRUPO(?, ?, ?, ?, ?)', [
                $request->grupo,
                $request->nrc, // carrera
                $request->periodo, // periodo
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
                    $request->periodo, // periodo
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
        //dd($request);
        try {
            $status = null;
            $message = null;

            //$lang = $request->language == 'E' ? 1 : 2;
            $lang = $request->language == 2;


            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_AGREGAR_ALUMNO_GRUPO(?, ?, ?, ?, ?)', [
                $request->grupo,
                $request->nrc, // carrera
                $request->periodo, // periodo
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
        try {
            $status = null;
            $message = null;

            //$lang = $request->language == 'E' ? 1 : 2;
            $lang = $request->language == 2;


            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_GROUP_DELETE(?, ?, ?)', [
                $request->itemGrupoDrop,
                $request->nrc, // carrera
                $request->periodo, // periodo
            ]);
        } catch (\Exception $e) {
            //dd ($e);
        }
        $ret['mensaje'] = "Se eliminó el grupo correctamente";
        return response($ret, Response::HTTP_OK);
    }

    public static function generarGrupos(Request $request)
    {
        if ((int)$request['num_grupos'] < 2 || (int)$request['num_grupos'] > 8) {
            return response(['msj' => "El número de grupos a crear debe ser un dígito entre 2 y 8", 'cod' => 1], 422);
        }
        try {
            $status = null;
            $message = null;

            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_CREAR_GRUPOS(?, ?, ?)', [
                $request->num_grupos,
                $request->nrc, // carrera
                $request->periodo, // periodo
            ]);
        } catch (\Exception $e) {
            //dd($e);
        }
        $ret['mensaje'] = "Se generaron los grupos correctamente";
        return response($ret, Response::HTTP_OK);
    }
}
