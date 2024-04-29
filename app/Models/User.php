<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'dni',
        'username',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


     public static function listaUsuarios($request)
    {

        $query =   User::select('users.*')
                    ->addSelect(DB::RAW('roles.name as role_name'))
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->where('users.enabled','=',1);

        if(!empty($request['rol_id'])) {
            $query->where('users.role_id','=',$request['rol_id']);
        } 
        if(!empty($request['dni'])) {  
            $query->where('users.dni', 'like', '%' . $request['dni'] . '%');
        } 


        $query = $query->orderby('id', 'desc')->get();

        //dd($query);
        return  $query;
     }

}
