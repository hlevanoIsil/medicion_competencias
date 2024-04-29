<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
     'name'];

    public static function getRoles()
     {
 
         $query = "select id, upper(name) as name from roles order by name";
  
        // dd($query);
         return DB::connection('mysql')->select($query);
 
      }

}
