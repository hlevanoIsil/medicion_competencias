<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Data extends Model
{
    use HasFactory;


    public static function horariosDocente($pidm)
    {
        $query = "sELECT  PERIODO id, PERIODO name FROM (
            select UNIQUE (PHP_TERM_CODE) PERIODO from isil.tab_profesor_horas_periodo where SUBSTR(PHP_TERM_CODE ,-2) IN ('00','10','20') AND PHP_PIDM =" . $pidm . " 
            UNION
            select UNIQUE (PHP_TERM_CODE)  PERIODO from isil.tab_profesor_horas_periodo where SUBSTR(PHP_TERM_CODE ,-2) NOT IN ('00','10','20') AND PHP_PIDM =" . $pidm . "
            ) WHERE PERIODO IS NOT NULL order by periodo desc";
        return DB::connection('oracle')->select($query);
    }

    public static function getTermcode($request)

    {

        $query = "select 1 orden,cperiodo id,cperiodo||' '||dperiodo name
                    from ISIL.TAB_PERIODOS_NIVEL
                    where cnivel='" . $request['nivel_code'] . "'  and
                        cestad='S'
                  UNION
                 select 2 orden,cperiodo id,cperiodo||' '||dperiodo name
                    from ISIL.TAB_PERIODOS_NIVEL
                    where cnivel='" . $request['nivel_code'] . "'  and
                            cestad='A'
                    order by 1 ,2 desc";

        //dd($query);
        return DB::connection('oracle')->select($query);
    }

    public static function getTermcodeAll($request)
    {

        $query = " select term_code id,concat(term_code,' ',term_desc) name, levl_code lvel
                    from terms
                    where  enabled=1
                    order by id desc";

        //dd($query);
        return DB::select($query);
    }

    public static function getCarrera($request)
    {

        $query = "select distinct sgbstdn_majr_code_1 id,stvmajr_desc name
                        from sgbstdn
                        inner join stvmajr on stvmajr_code=sgbstdn_majr_code_1
                        where sgbstdn_levl_code='" . $request['nivel_code'] . "' and
                              sgbstdn_term_code_eff='" . $request['term_code'] . "'";

        //dd($query);
        return DB::connection('oracle')->select($query);
    }
    public static function getCoordinador($request)
    {

        $query = "select UNIQUE coordinador id, coordinador name from ISIL.tab_coordinadores where periodo='" . $request['term_code'] . "'";

        return DB::connection('oracle')->select($query);
    }
    public static function getCarreraAll()
    {

        $query = "select SRBRICC_MAJR_CODE id, SRBRICC_ACAD_PROG_NAME name, SRBRICC_LEVL_CODE as levl_code
                     from SRBRICC";

        return DB::connection('oracle')->select($query);
    }

    public static function getReason()
    {

        $query = "select TRIM(CITEM) as id,TRIM(DITEM) as name
                        from isil.tab_general
                        where ctabla='003'
                        ORDER BY 1";

        return DB::connection('oracle')->select($query);
    }


    public static function getPeriodos()
    {
        $query = " select distinct substr(CAMPANA,1,6) periodo
                from isil.tab_pla_campana
                where substr(CAMPANA,5,2) in ('00','10','20')
            --union
            --select ' TODO'
            --    from Dual
            order by 1 DESC";

        return DB::connection('oracle')->select($query);
    }

    public static function getCampanias($periodo = null)
    {
        $cadd = '';
        if (isset($periodo)) {
            $cadd = " and substr(CAMPANA,1,6)= '" . $periodo . "' ";
        }
        $query = "select CAMPANA Campania FROM ISIL.TAB_PLA_CAMPANA
            where 1 = 1 " . $cadd . "
                union
                select ' TODO'
                from Dual
                order by 1 desc";
        return DB::connection('oracle')->select($query);
    }

    public function getCarreraAllByNivel($request)
    {

        $query = "select distinct ccarrera id, stvmajr_desc name
            from isil.tab_mallas_carreras
            inner join STVMAJR on STVMAJR_CODE=ccarrera
            where cperiodo=(select max(cperiodo)
                            from isil.tab_mallas_carreras) ";
        if (!empty($request['nivel_code'])) {
            $query .= " and substr(ccarrera,1,1) = '" . $request['nivel_code'] . "' ";
        }
        return DB::connection('oracle')->select($query);
    }

    public function getTermcodeyear($request)
    {

        $query = "select distinct id, name from(
                select substr(cperiodo, 1, 4) id,substr(cperiodo, 1, 4) name
                        from ISIL.TAB_PERIODOS_NIVEL
                        where  cnivel='" . $request['nivel_code'] . "'  and
                            cestad='S'
                        UNION
                select substr(cperiodo, 1, 4) id,substr(cperiodo, 1, 4) name
                        from ISIL.TAB_PERIODOS_NIVEL
                        where  cnivel='" . $request['nivel_code'] . "' and
                                cestad='A'
                                ) e
                 order by 1 desc  FETCH NEXT 20 ROWS ONLY";

        //dd($query);
        return DB::connection('oracle')->select($query);
    }

    public function getMaestras($request)
    {
        $idTabla = $request['idTabla'];
        /** */
        $pdo = DB::connection('oracle')->getPdo();
        $stmt = $pdo->prepare("BEGIN ISIL.SP_ISMA_MAESTRAS_LISTAR_X_TABLA(:idTabla,:cursor); END;");
        $stmt->bindParam(':idTabla', $idTabla, \PDO::PARAM_STR);
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

    public  function getColegios($colegio)
    {

        $pdo = DB::connection('oracle')->getPdo();
        $stmt = $pdo->prepare("BEGIN ISIL.SP_ISMA_COLEGIOS_LISTAR(:colegio,:cursor); END;");
        $stmt->bindParam(':colegio', $colegio, \PDO::PARAM_STR);
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
}
