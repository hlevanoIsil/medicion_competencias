<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class GruposController extends Controller
{

    public static function listNrcsDocente(Request $request)
    {
        /* $page = $request['page'];
        $itemsPerPage = $request['itemsPerPage'];
        $init = ($page - 1) * $itemsPerPage;
        */
        /** Parametros */
        //dd("aaa");
        $periodo = $request['periodo'];
        $pidm = $request['pidm'];
        $request->session()->put('pidm_docente', '72255');

        //$pidm = '175380';
        $pidm = $request->session()->get('pidm_docente');

        $periodo = '202320';

        $pdo = DB::connection('oracle')->getPdo();
        $stmt = $pdo->prepare("BEGIN ISIL.SP_MEDICIONCOMP_LISTAR_NRC_X_DOCENTES(:periodo, :pidm, :cursor); END;");
        $stmt->bindParam(':periodo', $periodo, \PDO::PARAM_STR);
        $stmt->bindParam(':pidm', $pidm, \PDO::PARAM_STR);
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

        $periodo = '202320';
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
        return [
            "data" => $data,
            'rows' => count($data)
        ];
    }

    public static function listAlumnos(Request $request)
    {
        /* $page = $request['page'];
        $itemsPerPage = $request['itemsPerPage'];
        $init = ($page - 1) * $itemsPerPage;
        */
        /** Parametros */
        $periodo = $request['periodo'];
        $nrc = $request['nrc'];
        $periodo = '202320';
        $grupo = $request['grupo'] ?? null;
        //$nrc = '1001';

        $pdo = DB::connection('oracle')->getPdo();
        $stmt = $pdo->prepare("BEGIN ISIL.SP_MEDICIONCOMP_LISTAR_ALUMNOS_X_NRC(:periodo, :nrc, :grupo, :cursor); END;");
        $stmt->bindParam(':periodo', $periodo, \PDO::PARAM_STR);
        $stmt->bindParam(':nrc', $nrc, \PDO::PARAM_STR);
        $stmt->bindParam(':grupo', $grupo, \PDO::PARAM_STR);
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
}
