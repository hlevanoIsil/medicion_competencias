<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\UserSystem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MenuItemController extends Controller
{
    public function index()
    {
        $user = auth::user();

        $menu = New MenuItem();
        $response = $menu->getItemsByRole($user->role_id);

        return response($response->jsonSerialize(), Response::HTTP_OK);   
    }

}
