<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\MenuItem;
use App\Models\UserSystem;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{

    public function callbackLogin(Request $request)
    {

        $user = Socialite::driver('saml2')->stateless()->user(); //->stateless()

        //dd($user);
        if (is_null($user)) return redirect($_ENV["APP_URL"] . "/login");

        $id = $user->id;

        $dni = explode("@", $id);

        if (isset($dni[0])) {
            $userExists = User::where('dni', $dni[0])
                ->first();

            if ($userExists) {
                if ($userExists->datos_actualizados == 0) {
                    User::where('dni', $dni[0])
                        ->update([
                            'name' => $user->last_name . ' ' . $user->first_name,
                            'first_name' => $user->first_name,
                            'last_name' => html_entity_decode($user->last_name),
                            'email' => $user->email,
                            'update_data' => 1,
                        ]);
                }
                $token = $userExists->createToken("auth_token")->plainTextToken;
                return redirect($_ENV["APP_URL"] . "/login/" . $userExists->dni . "/" . $token);
            }
        }

        return redirect($_ENV["APP_URL"] . "/app/logout");
    }

    public function login(Request $request)
    {

        $userExists = User::where('dni', $request['id'])->first();

        if (is_null($userExists)) {
            return $this->unauthorized($request);
        }

        $token = explode("|", $request['password']);

        if (!isset($token[0])) {
            return $this->unauthorized($request);
        }

        $tokenable = DB::table('personal_access_tokens')
            ->where('id', $token[0])
            ->where('token', hash('sha256', $token[1]))
            ->first();

        if (is_null($tokenable)) return $this->unauthorized($request);

        if ($userExists->id == $tokenable->tokenable_id) {
            auth::guard('web')->loginUsingId($userExists->id);
        } else {
            return $this->unauthorized($request);
        }

        $user = auth::guard('web')->user();

        $user->role = Role::find($user->role_id)->name;

        $menu = new MenuItem();

        $response = [
            'token' => $request['password'],
            'user' =>  $user,
            'menu' => $menu->getItemsByRole($user->role_id)
        ];

        return response($response, Response::HTTP_OK);
    }


    public function logout(Request $request)
    {

        if (auth()->check()) {
            //$token=explode("|", $request['userToken']);
            //auth()->user()->tokens()->where('id', $token[0])->delete();
            auth()->user()->tokens()->delete();
            auth()->guard('web')->logout();
        }

        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    public function unauthorized(Request $request)
    {
        //return response()->json(['error' => 'No Autorizado'], 401);
        $response = ['message' => 'No Autorizado'];
        return response($response, Response::HTTP_UNAUTHORIZED);
    }
}
