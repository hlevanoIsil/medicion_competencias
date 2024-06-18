<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Data;

class DataController extends Controller
{
    public function getCurrentTermcodeCT(Request $request)
    {
        return $request->session()->get('periodo');
    }

    public function getTermcodeTeacher(Request $request)
    {
        $data = Data::horariosDocente($request['pidm']);
        return response($data, Response::HTTP_OK);
    }

    public function getTermcode(Request $request)
    {
        $data = Data::getTermcode($request);
        return response($data, Response::HTTP_OK);
    }

    public function getTermcodeAll(Request $request)
    {
        $data = Data::getTermcodeAll($request);
        return response($data, Response::HTTP_OK);
    }

    public function getCarrera(Request $request)
    {
        $data = Data::getCarrera($request);
        return response($data, Response::HTTP_OK);
    }

    public function getCarreraAll()
    {
        $data = Data::getCarreraAll();
        return response($data, Response::HTTP_OK);
    }

    public function getReason()
    {
        $data = Data::getReason();
        return response($data, Response::HTTP_OK);
    }

    public function getPeriodos()
    {
        $data = Data::getPeriodos();
        return response($data, Response::HTTP_OK);
    }

    public function getCampanias(Request $request)
    {
        $data = Data::getCampanias($request['periodo']);
        return response($data, Response::HTTP_OK);
    }

    public function getCoordinador(Request $request)
    {

        $data = Data::getCoordinador($request);

        return response($data, Response::HTTP_OK);
    }

    public function getCarreraAllByNivel(Request $request)
    {
        $data = Data::getCarreraAllByNivel($request);
        return response($data, Response::HTTP_OK);
    }

    public function getTermcodeyear(Request $request)
    {
        $data = Data::getTermcodeyear($request);
        return response($data, Response::HTTP_OK);
    }
    public function getMaestras(Request $request)
    {
        $data = Data::getMaestras($request);
        return response($data, Response::HTTP_OK);
    }
    public function getColegios(Request $request)
    {
        $data = Data::getColegios($request['colegio']);
        return response($data, Response::HTTP_OK);
    }
}
