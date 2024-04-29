<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; 

class Events extends Model
{
    use HasFactory;
  
    protected $connection = 'oracle'; 
    protected $table = 'ISIL.TAB_EVENTO'; 
    protected $primaryKey = 'ID_EVENTO';

    public function getTipoEvento()
     {
 
         $query = "select id, upper(descripcion) as name 
                   from isil.tab_tipo_evento where estado = 1 order by descripcion";
         return DB::connection('oracle')->select($query);
      }
 
    public static function list($request){

        /** Parametros */ 
        $tipoEvento_code = $request['tipoEvento_code'];
        $nombre = $request['nombre'];
        $anio = $request['anio'];
        /** */

        $pdo = DB::connection('oracle')->getPdo();
        $stmt = $pdo->prepare("BEGIN ISIL.SP_ISMA_EVENTO_LISTAR_X_CRITERIOS(:tipoEvento_code,:nombre,:anio,:cursor); END;");
        $stmt->bindParam(':tipoEvento_code', $tipoEvento_code, \PDO::PARAM_STR);
        $stmt->bindParam(':nombre',$nombre, \PDO::PARAM_STR);
        $stmt->bindParam(':anio',  $anio, \PDO::PARAM_STR); 
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT); 
       
        $stmt->execute();
        oci_execute($cursor);
        $result = oci_fetch_all($cursor, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        // Convertir las claves a minúsculas
        $data = array_map('array_change_key_case', $data);
        // Obtener la cantidad de registros
        $cantidadRegistros = count($data);
        return [
            "data" => $data, "rows" => $cantidadRegistros
        ];        
    }

    public static function listReport($request){

        /** Parametros */ 
        $tipoEvento_code = $request['tipoEvento_code'];
        $nombre = $request['nombre'];
        $anio = $request['anio'];
        $nivel_code = $request['nivel_code'];
        $anio_egreso = $request['anio_egreso'];
        $ccarr = $request['ccarr'];
        /** */

        $pdo = DB::connection('oracle')->getPdo();
        $stmt = $pdo->prepare("BEGIN ISIL.SP_ISMA_EVENTO_REPORTE(:tipoEvento_code,:nombre,:anio,:nivel_code,:ccarr,:anio_egreso,:cursor); END;");
        $stmt->bindParam(':tipoEvento_code', $tipoEvento_code, \PDO::PARAM_STR);
        $stmt->bindParam(':nombre',$nombre, \PDO::PARAM_STR);
        $stmt->bindParam(':anio',  $anio, \PDO::PARAM_STR); 
        $stmt->bindParam(':nivel_code',  $nivel_code, \PDO::PARAM_STR); 
        $stmt->bindParam(':ccarr',  $ccarr, \PDO::PARAM_STR); 
        $stmt->bindParam(':anio_egreso',  $anio_egreso, \PDO::PARAM_STR); 
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT); 
       
        $stmt->execute();
        oci_execute($cursor);
        $result = oci_fetch_all($cursor, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        // Convertir las claves a minúsculas
        $data = array_map('array_change_key_case', $data);
        // Obtener la cantidad de registros
        $cantidadRegistros = count($data);
        return [
            "data" => $data, "rows" => $cantidadRegistros
        ];        
    }

    public function registrar($request)
    { 
        try{ 
            DB::connection('oracle')->select('CALL  ISIL.SP_ISMA_EVENTO_INSERT(?, ?, ?, ?, ?,?,?,?)', [ 
             $request['nombre'],
             $request['fecha_r'],
             $request['id_tipo_evento'],
             $request['direccion'],
             $request['hora'],
             $request['id_modalidad'],
             $request['observacion'],
             $request['user']
         ]);

        }catch(\Exception $e){
           return ("No se pudo generar evento");
        }
    }

    public function listarCorreosE($request)
    {  
 
        /** Parametros */ 
        $id = $request['id_evento']; 
        /** */

        $pdo = DB::connection('oracle')->getPdo();
        $stmt = $pdo->prepare("BEGIN ISIL.SP_ISMA_EVENTO_LISTAR_CORREO_EGRESADOS(:id,:cursor); END;");
        $stmt->bindParam(':id', $id, \PDO::PARAM_STR); 
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT); 

        $stmt->execute();
        oci_execute($cursor);
        $result = oci_fetch_all($cursor, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW); 
        $data = array_map('array_change_key_case', $data); 
        $cantidadRegistros = count($data);
        return [
            "data" => $data, "rows" => $cantidadRegistros
        ];        
    }

    public function agregarEgresados($request)
    { 
        //dd($request);
        try{ 
            DB::connection('oracle')->select('CALL ISIL.SP_ISMA_EVENTO_EGRESADOS_INSERT(?, ?, ?, ?)', [ 
             $request['id'],  
             $request['term_code'],
             $request['carre_code'], 
             $request['user']
         ]);

        }catch(\Exception $e){
           return ("No se pudo agregar egresados");
        }
    }

    public static function getEgresadosEvento($request){

         /** Parametros */ 
         $id = $request['id'];
         $flginscrito = $request['flginscrito'];
         $flgasistio = $request['flgasistio'];
         $flgtodos = $request['flgtodos'];
         /** */
 
         $pdo = DB::connection('oracle')->getPdo();
         $stmt = $pdo->prepare("BEGIN ISIL.SP_ISMA_EVENTO_EGRESADOS_LISTAR_X_CRITERIOS(:id,:flginscrito,:flgasistio, :flgtodos, :cursor); END;");
         $stmt->bindParam(':id', $id, \PDO::PARAM_STR);
         $stmt->bindParam(':flginscrito', $flginscrito, \PDO::PARAM_STR);
         $stmt->bindParam(':flgasistio', $flgasistio, \PDO::PARAM_STR); 
         $stmt->bindParam(':flgtodos', $flgtodos, \PDO::PARAM_STR); 
         $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT); 
        
         $stmt->execute();
         oci_execute($cursor);
         $result = oci_fetch_all($cursor, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW); 
         $data = array_map('array_change_key_case', $data); 
         $cantidadRegistros = count($data);
         return [
             "data" => $data, "rows" => $cantidadRegistros
         ];      
 
    }

    public static function getCarrerasEvento($request){
         /** Parametros */ 
         $id = $request['id'];  
 
         $pdo = DB::connection('oracle')->getPdo();
         $stmt = $pdo->prepare("BEGIN ISIL.SP_ISMA_EVENTO_CARRERAS_X_ID(:id, :cursor); END;");
         $stmt->bindParam(':id', $id, \PDO::PARAM_STR); 
         $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT); 
        
         $stmt->execute();
         oci_execute($cursor);
         $result = oci_fetch_all($cursor, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW); 
         $data = array_map('array_change_key_case', $data); 
         $cantidadRegistros = count($data);
         return [
             "data" => $data, "rows" => $cantidadRegistros
         ];     


    }

    public function registraAsis($request)
    { 
         
        try{ 
            DB::connection('oracle')->select('CALL  ISIL.SP_ISMA_EVENTO_EGRESADO_ASISTENCIA_INSERT(?, ?, ?)', [ 
             $request['id_evento'],
             $request['pidm'], 
             $request['user']
         ]);

        }catch(\Exception $e){
           return ("No se pudo generar asistencia a evento");
        }
    }

    public function registraAsisMasivo($request)
    { 
         
        try{ 
            DB::connection('oracle')->select('CALL  ISIL.SP_ISMA_EVENTO_ASISTENTES_CARGA_ARCHIVO(?, ?, ?, ?)', [ 
             $request['id_evento'],
             $request['dni'], 
             $request['nombre_archivo'],
             $request['user']
         ]);

        }catch(\Exception $e){
           return ("No se pudo generar asistencia a evento");
        }
    }

    
    public function registraInscritosMasivo($request)
    { 
         
        try{ 
            DB::connection('oracle')->select('CALL  ISIL.SP_ISMA_EVENTO_INSCRITOS_CARGA_ARCHIVO(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [ 
             $request['id_evento'],
             $request['dni'], 
             $request['nombre_archivo'],
             $request['user'],
             $request['idSituacion'], 
             $request['idEmpresa'], 
             $request['idPuesto'], 
             $request['idTiempo'],
             $request['modalidad'],
             $request['sueldo_actual'],
             $request['sueldo_antes']
         ]);

        }catch(\Exception $e){
           return ("No se pudo generar inscipcion masiva a evento");
        }
    }

    public static function getEgresadosAdiciona($request){
        $page = $request['page'];
        $itemsPerPage = $request['itemsPerPage'];
        $init = ($page - 1) * $itemsPerPage;
        
        $query = "select e.pidm pidm, 
                    spriden_id dni,
                    e.id_evento,
                    (spriden_last_name || ', ' || spriden_first_name) fullname, 
                    (spriden_id|| ' - ' ||spriden_last_name || ', ' || spriden_first_name) namedni,
                    e.periodo_egr periodo_egr, 
                    c.stvmajr_desc carrera, 
                    e.ccarr ccarr,
                    swinscrito,
                    swasistio
            from isil.tab_evento_egresados e inner join
                spriden s on s.spriden_pidm = e.pidm inner join
                stvmajr c on c.stvmajr_code = e.ccarr
            where nvl(s.spriden_change_ind,'A') not in ('I','N') and e.estado = 1 
                  and swinscrito=0 and swasistio = 0"; 

        if(!empty($request['id'])) {
            $query.= " and e.id_evento = '".$request['id']."' "; 
        }  
        $query.= " order by spriden_last_name"; 
        $count = "select count(*) total from (".$query.")";
 
        return ["data" => DB::connection('oracle')->select($query) , 
                'rows' => DB::connection('oracle')->select($count)[0]->total ];
    }

    public function addEgresadoAsis($request)
    { 
         //dd($request);
        try{ 
            DB::connection('oracle')->select('CALL  ISIL.SP_ISMA_EVENTO_ADICIONAR_EGRESADO(?, ?, ?)', [ 
             $request['id'],
             $request['pidm_e'], 
             $request['user']
         ]);

        }catch(\Exception $e){
           return ("No se pudo adicionar asistente");
        }
    }

    public static function rptEgresadosEvent($request){
        
         /** Parametros */ 
        // dd($request);
         $cod_term = $request['cod_term'];
         $ccarr = $request['ccarr'];
         $nivel_code = $request['nivel_code'];
         $anio_egreso = $request['anio_egreso']; 
         $doc_identidad = $request['doc_identidad']; 
         $last_name = $request['last_name'];
         $anio_actualiza = $request['anio_actualiza'];
         $id_situ = $request['id_situ'];
         /** */
         $pdo = DB::connection('oracle')->getPdo();
         $stmt = $pdo->prepare("BEGIN ISIL.SP_ISMA_EGRESADO_CONTACTABILIDAD_REPORTE(:cod_term,:ccarr,:nivel_code,:anio_egreso, 
                                                                                    :doc_identidad,:last_name,:anio_actualiza,
                                                                                    :id_situ,:cursor); END;");
         $stmt->bindParam(':cod_term',$cod_term, \PDO::PARAM_STR);
         $stmt->bindParam(':ccarr', $ccarr, \PDO::PARAM_STR);
         $stmt->bindParam(':nivel_code', $nivel_code, \PDO::PARAM_STR);
         $stmt->bindParam(':anio_egreso', $anio_egreso, \PDO::PARAM_STR); 
         $stmt->bindParam(':doc_identidad', $doc_identidad, \PDO::PARAM_STR);
         $stmt->bindParam(':last_name', $last_name, \PDO::PARAM_STR); 
         $stmt->bindParam(':anio_actualiza', $anio_actualiza, \PDO::PARAM_STR); 
         $stmt->bindParam(':id_situ', $id_situ, \PDO::PARAM_STR); 
         $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT);
     
         $stmt->execute();
         oci_execute($cursor);
         $result = oci_fetch_all($cursor, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);
         $data = array_map('array_change_key_case', $data); 
         $cantidadRegistros = count($data);
         return [
             "data" => $data, "rows" => $cantidadRegistros
         ];   
    }

    public static function listEgrNoEncontrados($request){
        $query = "select dni, observacion  from  
                   isil.tab_evento_archivo_asistentes
                  where nombre_archivo='".$request['nombre_archivo']."'";
 
        return DB::connection('oracle')->select($query);
    }

    public function limpiarExcel($request)
    { 
        try{ 
            $query = "DELETE FROM isil.tab_evento_archivo_asistentes
            WHERE nombre_archivo='".$request['nombre_archivo']."'";

        return DB::connection('oracle')->select($query); 

        }catch(\Exception $e){
           return ("No se pudo limpiar tabla");
        }
    }

    public static function listArchivoInscritos($request){
        $query = "select dni, observacion  from  
                   isil.tab_evento_archivo_inscritos
                  where nombre_archivo='".$request['nombre_archivo']."'";
 
        return DB::connection('oracle')->select($query);
    }

    public function limpiarExcelInscritos($request)
    { 
        try{ 
            $query = "DELETE FROM isil.tab_evento_archivo_inscritos
            WHERE nombre_archivo='".$request['nombre_archivo']."'";

        return DB::connection('oracle')->select($query);


        }catch(\Exception $e){
           return ("No se pudo limpiar tabla");
        }
    }

    public function eliminarEvento($request)
    { 
        try{ 
            $query = "UPDATE  isil.tab_evento SET estado= 0
            WHERE id_evento='".$request['id']."'";

        return DB::connection('oracle')->select($query);


        }catch(\Exception $e){
           return ("No se pudo limpiar tabla");
        }
    } 
    
    public function quitarCarrera($request)
    { 
        try{ 
            $query = "UPDATE  isil.tab_evento_egresados SET estado = 0
                      WHERE id_evento='".$request['id_evento']."'
                      AND CCARR='".$request['ccarr']."'
                      AND PERIODO_EGR='".$request['periodo_egr']."'";

        return DB::connection('oracle')->select($query);


        }catch(\Exception $e){
           return ("No se pudo limpiar tabla");
        }
    }
    
    
    public function reporteEventoInscAsis($request)
    {
        /** Parametros */ 
        $evento = $request['nombre'];
        $anio = $request['anio'];
        $nivel_code = $request['nivel_code'];
        $ccarr = $request['ccarr'];
        $anio_egreso = $request['anio_egreso']; 
        $swInscrito = $request['swInscrito'];
        $swAsistente = $request['swAsistente'];
        /** */
        $pdo = DB::connection('oracle')->getPdo();
        $stmt = $pdo->prepare("BEGIN ISIL.SP_ISMA_EVENTO_REPORTE_TOTAL_EGRESADOS(:nombre,:anio,:nivel_code,:ccarr, 
                                                                                 :anio_egreso,:swInscrito,:swAsistente,:cursor); END;");
        $stmt->bindParam(':nombre',$evento, \PDO::PARAM_STR);
        $stmt->bindParam(':anio',  $anio, \PDO::PARAM_STR);
        $stmt->bindParam(':nivel_code', $nivel_code, \PDO::PARAM_STR);
        $stmt->bindParam(':ccarr', $ccarr, \PDO::PARAM_STR);
        $stmt->bindParam(':anio_egreso', $anio_egreso, \PDO::PARAM_STR);
        $stmt->bindParam(':swInscrito', $swInscrito, \PDO::PARAM_STR);
        $stmt->bindParam(':swAsistente', $swAsistente, \PDO::PARAM_STR); 
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT | \PDO::PARAM_INPUT_OUTPUT);
    
        // Ejecutar la llamada al procedimiento almacenado
        $stmt->execute();
    
        oci_execute($cursor);
        $result = oci_fetch_all($cursor, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);
    
       // return response()->json(['data' => $data]);

        return [
            "data" => $data
        ];
    } 
}
