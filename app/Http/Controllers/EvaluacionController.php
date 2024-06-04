<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class EvaluacionController extends Controller
{
    protected $_uploadFolder = 'notas_alumnos';

    public function __construct()
    {
        if (!file_exists($this->_uploadFolder)) {
            mkdir($this->_uploadFolder, 0777, true);
        }
    }
    public static function lisRubricasXcurso(Request $request)
    {
        //dd($request['cod_curso']);
        $curso = $request['cod_curso'];
        $nrc = $request['nrc'];
        $periodo = '202310';
        //dd($curso);
        //dd($nrc);
        //dd($periodo);
        try {
            $pdo = DB::connection('oracle')->getPdo();
            $stmt = $pdo->prepare("BEGIN ISIL.SP_MEDICIONCOMP_RUBRICAS_DET_X_CURSO(:periodo, :curso, :nrc, :cursor); END;");
            $stmt->bindParam(':periodo', $periodo, \PDO::PARAM_STR);
            $stmt->bindParam(':curso', $curso, \PDO::PARAM_STR);
            $stmt->bindParam(':nrc', $nrc, \PDO::PARAM_STR);
            $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT);
            $stmt->execute();
            oci_execute($cursor);

            oci_fetch_all($cursor, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            oci_free_statement($cursor);
            $stmt->closeCursor();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        //dd($data);
        $crit_cols = array_column($data, 'NUM_CRITERIO');
        //dd($data);
        $criterios = [];
        foreach ($crit_cols as $crit) {
            if ($crit) {
                foreach ($data as $row) {
                    if ($row['NUM_CRITERIO'] == $crit) {

                        $criterios[$crit]['subheader'] = 'Elige una calificación:';
                        $criterios[$crit]['PUN_EXC']['PUNT'] = (float)$row['PUN_EXC'];
                        $criterios[$crit]['PUN_EXC']['DESCR'] = "EXCELENTE";
                        $criterios[$crit]['PUN_EXC']['COD'] = 1;

                        $criterios[$crit]['PUN_BUE']['PUNT'] = (float)$row['PUN_BUE'];
                        $criterios[$crit]['PUN_BUE']['DESCR'] = "BUENO";
                        $criterios[$crit]['PUN_BUE']['COD'] = 2;

                        $criterios[$crit]['PUN_ENPR']['PUNT'] = (float)$row['PUN_ENPR'];
                        $criterios[$crit]['PUN_ENPR']['DESCR'] = "EN PROCESO";
                        $criterios[$crit]['PUN_ENPR']['COD'] = 3;

                        $criterios[$crit]['PUN_DEFI']['PUNT'] = (float)$row['PUN_DEFI'];
                        $criterios[$crit]['PUN_DEFI']['DESCR'] = "DEFICIENTE";
                        $criterios[$crit]['PUN_DEFI']['COD'] = 4;

                        $criterios[$crit]['PUN_INSAT']['PUNT'] = (float)$row['PUN_INSAT'];
                        $criterios[$crit]['PUN_INSAT']['DESCR'] = "INSATISFACTORIO";
                        $criterios[$crit]['PUN_INSAT']['COD'] = 5;
                    }
                }
                // $found_key = array_search($crit, array_column($data, 'NUM_CRITERIO'));
                // dd($found_key);
            }
        }
        //dd($criterios);
        return [
            'data' => $data,
            'criterios' => $criterios,
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
        $curso = $request['cod_curso'];
        $periodo = '202310';
        $grupo = $request['grupo'] ?? null;
        //$nrc = '1001';

        $pdo = DB::connection('oracle')->getPdo();
        $stmt = $pdo->prepare("BEGIN ISIL.SP_MEDICIONCOMP_LISTAR_ALUMNOS_NOTAS_X_NRC(:periodo, :curso, :nrc, :grupo, :cursor); END;");
        $stmt->bindParam(':periodo', $periodo, \PDO::PARAM_STR);
        $stmt->bindParam(':curso', $curso, \PDO::PARAM_STR);
        $stmt->bindParam(':nrc', $nrc, \PDO::PARAM_STR);
        $stmt->bindParam(':grupo', $grupo, \PDO::PARAM_STR);
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT);
        $stmt->execute();
        oci_execute($cursor);

        oci_fetch_all($cursor, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        oci_free_statement($cursor);
        $stmt->closeCursor();

        $return = [];
        $num_criterios = 5;
        $count = 0;
        $coment_grupal = "";
        foreach ($data as $fila) {
            //dd($fila);
            $return[$count]['NOMBRES'] = $fila['NOMBRES'];

            for ($i = 1; $i <= $num_criterios; $i++) {
                //$return[$count]['NOMBRES'] = $fila['NOMBRES'];

                //$return[$count]['criterio_' . $i]  = isset($fila['NOTA_CRIT_' . $i]) ? (float)$fila['NOTA_CRIT_' . $i] : null;
                $return[$count]['notas'][$i]  = isset($fila['NOTA_CRIT_' . $i]) ? (float)$fila['NOTA_CRIT_' . $i] : null;
            }
            $return[$count]['comments']  = $fila['COMENTARIOS'];
            $return[$count]['PIDM']  = $fila['PIDM'];

            $coment_grupal  = $fila['COMENTARIO_GRUPAL'];

            $count++;
        }
        //dd($return);
        return [
            "data" => $return,
            'rows' => count($data),
            'coment_grupal' => $coment_grupal
        ];
    }

    public static function saveIndividual(Request $request)
    {
        //dd($request);
        try {
            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_SAVE_NOTA_INDIVIDUAL(?, ?, ?, ?, ?, ?, ?)', [
                Auth()->user()->dni,
                $request->pidm_alumno,
                $request->nrc,
                $request->cod_curso,
                $request->periodo,
                $request->criterio,
                $request->cod_nota
                //&$status,
                //&$message
            ]);
        } catch (\Exception $e) {
            // dd($e);
        }
        $ret['mensaje'] = "Registro correcto";
        return response($ret, Response::HTTP_OK);
    }

    public static function saveGrupal(Request $request)
    {
        //dd($request);
        try {
            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_SAVE_NOTA_GRUPAL(?, ?, ?, ?, ?, ?, ?)', [
                Auth()->user()->dni,
                $request->grupo,
                $request->nrc,
                $request->cod_curso,
                $request->periodo,
                $request->criterio,
                $request->cod_nota
                //&$status,
                //&$message
            ]);
        } catch (\Exception $e) {
            //dd($e);
        }
        $ret['mensaje'] = "Registro correcto";
        return response($ret, Response::HTTP_OK);
    }

    public static function saveCommentIndividual(Request $request)
    {
        //dd($request);
        try {
            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_SAVE_COMENTARIOS(?, ?, ?, ?, ?, ?)', [
                1,
                $request->periodo,
                $request->pidm_alumno,
                $request->nrc,
                null,
                $request->comment
            ]);
        } catch (\Exception $e) {
            // dd($e);
        }
        $ret['mensaje'] = "Registro correcto";
        return response($ret, Response::HTTP_OK);
    }

    public static function saveCommentGrupal(Request $request)
    {
        //dd($request);
        try {
            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_SAVE_COMENTARIOS(?, ?, ?, ?, ?, ?)', [
                2,
                $request->periodo,
                null,
                $request->nrc,
                $request->grupo,
                $request->commentGrupal
            ]);
        } catch (\Exception $e) {
            // dd($e);
        }
        $ret['mensaje'] = "Registro correcto";
        return response($ret, Response::HTTP_OK);
    }

    public static function previewPdf(Request $request)
    {
        $detalles = [];

        $view = view('evaluacion.notas_individual', compact(
            'detalles',
        ))->render();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        return $pdf->stream('enrollment.pdf');
    }
    public function viewPdf(Request $request)
    {
        //dd($request);
        $periodo = $request['periodo'];
        $nrc = $request['nrc'];
        $curso = $request['cod_curso'];
        $grupo = $request['grupo'];
        $dni = $request['spriden_id'] != "TODOS" ? $request['spriden_id'] : null;
        $apellidos = $request['last_name'] != "TODOS" ? $request['last_name'] : null;
        $nombres = $request['first_name'] != "TODOS" ? $request['first_name'] : null;
        //dd($dni);
        /*$grupo = null;
        $dni = null;
        $apellidos = null;
        $nombres = null;*/
        //dd($curso);
        //dd($nrc);
        //dd($periodo);
        try {
            $pdo = DB::connection('oracle')->getPdo();
            $stmt = $pdo->prepare("BEGIN ISIL.SP_MEDICIONCOMP_REPORTE_NOTAS(:periodo, :nrc, :curso, :grupo, :dni, :apellidos, :nombres, :cursor); END;");
            $stmt->bindParam(':periodo', $periodo, \PDO::PARAM_STR);
            $stmt->bindParam(':nrc', $nrc, \PDO::PARAM_STR);
            $stmt->bindParam(':curso', $curso, \PDO::PARAM_STR);
            $stmt->bindParam(':grupo', $grupo, \PDO::PARAM_STR);
            $stmt->bindParam(':dni', $dni, \PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $apellidos, \PDO::PARAM_STR);
            $stmt->bindParam(':nombres', $nombres, \PDO::PARAM_STR);
            $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT);
            $stmt->execute();
            oci_execute($cursor);

            oci_fetch_all($cursor, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            oci_free_statement($cursor);
            $stmt->closeCursor();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        $pidms = array_column($data, 'SPRIDEN_PIDM');
        $pidms = array_unique($pidms);
        //dd($data);
        $datos = [];

        foreach ($pidms as $pidm) {

            $notas = [];
            $cont = 0;
            $notaTotal = 0;
            foreach ($data as $fila) {
                //dd($fila);

                if ($fila['SPRIDEN_PIDM'] == $pidm) {
                    $datos[$pidm]["NOMBRES"] = $fila['FULLNAME'];
                    $datos[$pidm]["GRUPO"] = $fila['GRUPO_NUM'];
                    $datos[$pidm]["COMENTARIOS"] = $fila['COMENTARIOS'];

                    $notas[$cont]['NUM_CRITERIO'] = "Criterio " . $fila['NUM_CRITERIO'];
                    $notas[$cont]['NOM_CRITERIO'] = $fila['NOM_CRITERIO'];
                    $notas[$cont]['DESCR_NOTA'] = $fila['DESCR_NOTA'];
                    $notas[$cont]['NOTA'] = (float)$fila['NOTA'];
                    $notaTotal += (float)$fila['NOTA'];

                    $datos[$pidm]["NOTAS"] = $notas;
                    $datos[$pidm]["NOTA_FINAL"] = $notaTotal;
                    $cont++;
                }
            }
        }

        $curso = $request['curso'];
        $nrc = $request['nrc'];
        $horarios = $request['horario'];
        $modalidad = $request['mod_sede'];
        $view = view('evaluacion.notas', compact(
            'datos',
            'curso',
            'nrc',
            'horarios',
            'modalidad'
        ))->render();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        $nombre = "resultado_de_notas.pdf";
        file_put_contents($this->_uploadFolder . "/" . $nombre, $pdf->output());

        return response([], Response::HTTP_OK);
    }
}
