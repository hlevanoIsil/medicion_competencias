<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Role;
use App\Models\Template;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{

    public function userbydni($dni)
    {
        
        $response = User::select('dni','name', 'role_id')->where('dni', $dni)->with('role')->first();

        if(is_null($response)) {
          return response(null, Response::HTTP_OK);      
        }
        
        return response($response->jsonSerialize(), Response::HTTP_OK);   
    }

    public function store(Request $request)
    {
        $data = User::listaUsuarios($request);
       
       // console.log("$request::"+$request);

        return response($data, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {

        try{
           // dd('update');
           /* $user = Auth::user();

            DB::connection('mysql')->select('CALL  usp_update_user(?, ?, ?)', [
                $request['id'],
                $request['dni'],
                $request['role_id'],
                $user->username
            ]);

            $entity = User::find($id);

            $entity->update($request->all());

            return response([], Response::HTTP_OK);*/

            $entity = User::find($id);

            $entity->update($request->only('role_id', 'username'));

            $entity->role_name = Role::find($request->role_id)->name;
    
            return response($entity->jsonSerialize(), Response::HTTP_OK);

        }catch(Exception $e){
           //return ("No se pudo acceder al registro/actualización");
        } 
        
    }

}