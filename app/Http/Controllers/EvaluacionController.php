<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Actividad;
use App\Models\User;
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
        Actividad::saveActividad('Lista rubricas');

        $curso = $request['cod_curso'];
        $nrc = $request['nrc'];
        $periodo = $request->session()->get('periodo');

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
            //dd($e->getMessage());
        }

        $criterios = [];
        if (isset($data)) {
            $crit_cols = array_column($data, 'NUM_CRITERIO');
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

                            $criterios[$crit]['PUN_NP']['PUNT'] = "NP";
                            $criterios[$crit]['PUN_NP']['DESCR'] = "No se presentó";
                            $criterios[$crit]['PUN_NP']['COD'] = 6;

                            $criterios[$crit]['PUN_X']['PUNT'] = "X";
                            $criterios[$crit]['PUN_X']['DESCR'] = "Eliminar";
                            $criterios[$crit]['PUN_X']['COD'] = null;
                        }
                    }
                }
            }
        }
        //dd($criterios);
        return [
            'data' => $data ?? [],
            'criterios' => $criterios,
            'rows' =>  isset($data) ? count($data) : 0
        ];
    }

    public static function descargaRubricasXcurso(Request $request)
    {
        Actividad::saveActividad('Descarga rubricas');

        $curso = $request['cod_curso'];
        $nrc = $request['nrc'];
        $horario = $request['horario'];
        $sede = $request['sede'];
        $nomcurso = $request['curso'];

        $periodo = $request->session()->get('periodo');

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
            //dd($e->getMessage());
        }

        //dd($data);

        $view = view('evaluacion.rubrica', compact(
            'data',
            'curso',
            'nrc',
            'horario',
            'sede',
            'nomcurso',
            'periodo'

        ))->render();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view)->setPaper('a4', 'landscape');
        return $pdf->download("Detalle de Rúbrica - NRC " . $nrc . ".pdf");
    }

    public static function listAlumnos(Request $request)
    {

        Actividad::saveActividad('Lista alumnos por nrc');
        //dd($request);
        /* $page = $request['page'];
        $itemsPerPage = $request['itemsPerPage'];
        $init = ($page - 1) * $itemsPerPage;
        */
        /** Parametros */
        $nrc = $request['nrc'];
        $curso = $request['cod_curso'];
        $periodo = $request->session()->get('periodo');

        $grupo = $request['grupo'] ?? null;

        $dni_docente = "";
        $dni_jurado = "";

        if (auth()->user()->role_id == 2) {
            $dni_docente = auth()->user()->dni;
        } elseif (auth()->user()->role_id == 3) {
            $dni_jurado = auth()->user()->dni;
        }
        //$nrc = '1001';

        $pdo = DB::connection('oracle')->getPdo();

        try {
            $stmt = $pdo->prepare("BEGIN ISIL.SP_MEDICIONCOMP_LISTAR_ALUMNOS_NOTAS_X_NRC(:periodo, :curso, :nrc, :grupo, :dni_docente, :dni_jurado, :cursor); END;");
            $stmt->bindParam(':periodo', $periodo, \PDO::PARAM_STR);
            $stmt->bindParam(':curso', $curso, \PDO::PARAM_STR);
            $stmt->bindParam(':nrc', $nrc, \PDO::PARAM_STR);
            $stmt->bindParam(':grupo', $grupo, \PDO::PARAM_STR);
            $stmt->bindParam(':dni_docente', $dni_docente, \PDO::PARAM_STR);
            $stmt->bindParam(':dni_jurado', $dni_jurado, \PDO::PARAM_STR);
            $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT);
            $stmt->execute();
            oci_execute($cursor);

            oci_fetch_all($cursor, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            oci_free_statement($cursor);
            $stmt->closeCursor();
        } catch (\Exception $e) {
            //dd($e);
        }


        $return = [];
        $num_criterios = $request['totCriterios'];
        $count = 0;
        $coment_grupal = "";
        foreach ($data as $fila) {
            //dd($fila);
            $return[$count]['NOMBRES'] = $fila['NOMBRES'];

            for ($i = 1; $i <= $num_criterios; $i++) {
                $return[$count]['notas'][$i] = null;
                if (isset($fila['NOTA_CRIT_' . $i])) {
                    if ($fila['NOTA_CRIT_' . $i] == "NP") {
                        $return[$count]['notas'][$i] = $fila['NOTA_CRIT_' . $i];
                    } else {
                        $return[$count]['notas'][$i] = (float)$fila['NOTA_CRIT_' . $i];
                    }
                }
                //$return[$count]['notas'][$i]  = isset($fila['NOTA_CRIT_' . $i]) ? (float)$fila['NOTA_CRIT_' . $i] : null;
            }

            $return[$count]['PIDM']  = $fila['PIDM'];

            if (auth()->user()->role_id == 2) {
                $coment_grupal  = $fila['COMENTARIO_GRUPAL'];
                $return[$count]['comments']  = $fila['COMENTARIOS'];
            } elseif (auth()->user()->role_id == 3) {
                $coment_grupal  = $fila['COMENTARIO_JURADO_GRUPAL'];
                $return[$count]['comments']  = $fila['COMENTARIOS_JURADO'];
            }



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
        Actividad::saveActividad('Guarda nota individual');

        $dni_docente = "";
        $dni_jurado = "";

        if (auth()->user()->role_id == 2) {
            $dni_docente = auth()->user()->dni;
        } elseif (auth()->user()->role_id == 3) {
            $dni_jurado = auth()->user()->dni;
        }

        try {
            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_SAVE_NOTA_INDIVIDUAL(?, ?, ?, ?, ?, ?, ?, ?)', [
                $dni_docente,
                $dni_jurado,
                $request->pidm_alumno,
                $request->nrc,
                $request->cod_curso,
                $request->session()->get('periodo'),
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

    public static function saveGrupal(Request $request)
    {
        //dd(Auth()->user()->dni);
        Actividad::saveActividad('Guardar nota grupal');

        $dni_docente = "";
        $dni_jurado = "";

        if (auth()->user()->role_id == 2) {
            $dni_docente = auth()->user()->dni;
        } elseif (auth()->user()->role_id == 3) {
            $dni_jurado = auth()->user()->dni;
        }

        try {
            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_SAVE_NOTA_GRUPAL(?, ?, ?, ?, ?, ?, ?, ?)', [
                $dni_docente,
                $dni_jurado,
                $request->grupo,
                $request->nrc,
                $request->cod_curso,
                $request->session()->get('periodo'),
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
        Actividad::saveActividad('Guarda comentario individual');

        try {
            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_SAVE_COMENTARIOS(?, ?, ?, ?, ?, ?, ?, ?)', [
                1,
                $request->session()->get('periodo'),
                $request->pidm_alumno,
                $request->nrc,
                $request->grupo,
                $request->comment,
                auth()->user()->role_id,
                auth()->user()->dni
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
        Actividad::saveActividad('Guardar comment grupal');

        try {
            DB::connection('oracle')->select('CALL ISIL.SP_MEDICIONCOMP_SAVE_COMENTARIOS(?, ?, ?, ?, ?, ?, ?, ?)', [
                2,
                $request->session()->get('periodo'),
                null,
                $request->nrc,
                $request->grupo,
                $request->commentGrupal,
                auth()->user()->role_id,
                auth()->user()->dni
            ]);
        } catch (\Exception $e) {
            //dd($e);
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
        //ESTO SOLO LO VE EL DOCENTE

        //---------------------------------------------------------------------------------------------------------
        Actividad::saveActividad('Ver PDF');

        //OBTIENE JURADOS
        try {
            $dni_docente = auth()->user()->dni;
            $nrc = $request['nrc'];
            $pdo = DB::connection('oracle')->getPdo();
            $stmt = $pdo->prepare("BEGIN ISIL.SP_MEDICIONCOMP_LISTAR_JURADOS_X_NRC(:dni_docente, :nrc, :cursor); END;");
            $stmt->bindParam(':dni_docente', $dni_docente, \PDO::PARAM_STR);
            $stmt->bindParam(':nrc', $nrc, \PDO::PARAM_STR);
            $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT);
            $stmt->execute();
            oci_execute($cursor);

            oci_fetch_all($cursor, $jurados, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            oci_free_statement($cursor);
            $stmt->closeCursor();
        } catch (\Exception $e) {
            //dd($e->getMessage());
        }

        //OBTIENE NOTAS
        $periodo = $request->session()->get('periodo');
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
            //dd($e->getMessage());
        }

        $pidms = array_column($data, 'SPRIDEN_PIDM');
        $pidms = array_unique($pidms);
        //dd($data);
        $datos = [];

        foreach ($pidms as $pidm) {

            $notas = [];
            $cont = 0;
            $notaTotal = 0;
            $sumaNota = [];
            foreach ($data as $fila) {

                if ($fila['SPRIDEN_PIDM'] == $pidm) {

                    if (!isset($flgJurado1[$pidm])) {
                        $flgJurado1[$pidm] = false;
                    }
                    if (!isset($flgJurado2[$pidm])) {
                        $flgJurado2[$pidm] = false;
                    }

                    $datos[$pidm]["DOCENTE"] = $fila['FULLNAME_DOCENTE'];
                    /*if ($fila['PIDM_DOCENTE'] != '') {
                       
                    }*/
                    //$sumaNota[$pidm][$fila['NUM_CRITERIO']] = (float)$fila['PROM_X_CRITERIO'];
                    $sumaNota[$pidm][$fila['NUM_CRITERIO']] = 0;
                    //} else {
                    $datos[$pidm]["NOMBRES"] = $fila['FULLNAME'];

                    $datos[$pidm]["GRUPO"] = $fila['GRUPO_NUM'];
                    $datos[$pidm]["COMENTARIOS"] = $fila['COMENTARIOS'];
                    $datos[$pidm]["COMENTARIO_GRUPAL"] = $fila['COMENTARIO_GRUPAL'];

                    $datos[$pidm]["COMENTARIOS_JURADO"] = $fila['COMENTARIOS_JURADO'];
                    $datos[$pidm]["COMENTARIO_JURADO_GRUPAL"] = $fila['COMENTARIO_JURADO_GRUPAL'];

                    $notas[$cont]['NUM_CRITERIO'] = "Criterio " . $fila['NUM_CRITERIO'];
                    $notas[$cont]['COD_CRITERIO'] = $fila['NUM_CRITERIO'];
                    $notas[$cont]['NOM_CRITERIO'] = $fila['NOM_CRITERIO'];
                    $notas[$cont]['DESCR_NOTA'] = $fila['DESCR_NOTA'];

                    $notas[$cont]['NOTA_DOCENTE'] = $fila['NOTA_DOCENTE'];
                    $notas[$cont]['NOTA_JURADO1'] = $fila['NOTA_JURADO1'];
                    $notas[$cont]['NOTA_JURADO2'] = $fila['NOTA_JURADO2'];

                    if ((float)$fila['NOTA_JURADO1'] > 0)  $flgJurado1[$pidm] = true;
                    if ((float)$fila['NOTA_JURADO2'] > 0)  $flgJurado2[$pidm] = true;

                    $notas[$cont]['NOM_JURADO'] = $fila['NOM_JURADO'] ?? '';
                    //$notas[$cont]['NOTA'] = (float)$fila['PROM_X_CRITERIO'];
                    //dd($fila['NOTA']);
                    //dd($fila);
                    if ($fila['NOTA'] == "NP") {
                        $notas[$cont]['NOTA'] = "NP";
                    } else {
                        $notas[$cont]['NOTA'] = $fila['NOTA']; //(float)$fila['NOTA'];
                        //$notaTotal += (float)$fila['NOTA'];
                    }


                    //$notaTotal += (float)$fila['PROM_X_CRITERIO'];
                    $notaTotal = $fila['PROM_FINAL'];
                    //}
                    $datos[$pidm]["NOTAS"] = $notas;
                    $datos[$pidm]["NOTA_FINAL"] = number_format($notaTotal, 1, '.');

                    $datos[$pidm]["flgJurado1"] = $flgJurado1[$pidm];
                    $datos[$pidm]["flgJurado2"] = $flgJurado2[$pidm];

                    $cont++;
                }
                //if ($cont > 6) dd($sumaNota);
                //dd($sumaNota);
            }
        }
        //dd($datos);
        $curso = $request['curso'];
        $nrc = $request['nrc'];
        $horarios = $request['horario'];
        $modalidad = $request['mod_sede'];
        //dd($jurados);
        $view = view('evaluacion.notas', compact(
            'datos',
            'curso',
            'nrc',
            'horarios',
            'modalidad',
            'periodo',
            'jurados'
        ))->render();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        $nombre = "resultado_de_notas.pdf";
        file_put_contents($this->_uploadFolder . "/" . $nombre, $pdf->output());

        return response([], Response::HTTP_OK);
    }
}
