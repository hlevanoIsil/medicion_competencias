<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\DB;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



//LOG IN OUT
Route::prefix('app')->group(function () {
    Route::get('redirect', function () {
        return Socialite::driver('saml2')->redirect();
    });

    Route::get('saml2/metadata', function () {
        return Socialite::driver('saml2')->getServiceProviderMetadata();
    });

    Route::post('saml2/callback', [LoginController::class, 'callbackLogin']);

    Route::get('saml2/logout', function () {
        return Socialite::driver('saml2')->logoutResponse();
    });

    Route::get('logout', function () {
        return Socialite::driver('saml2')->logoutRequest();
    });
});

Route::prefix('auth')->group(function () {

    Route::post('login', [LoginController::class, 'login'])->name("auth.login");
    Route::post('loginExterno', [LoginController::class, 'loginJurado']);
});

Route::group(['prefix' => 'auth', 'middleware' => 'auth:sanctum'], function () {

    Route::post('logout',   [LoginController::class, 'logout']);
});

Route::group(['prefix' => 'system', 'middleware' => 'auth:sanctum'], function () {
    Route::resource('menu',   \App\Http\Controllers\MenuItemController::class);
    Route::post('curtermcode', [\App\Http\Controllers\DataController::class, 'getCurrentTermcodeCT']);

    Route::post('termcode', [\App\Http\Controllers\DataController::class, 'getTermcode']);
    Route::post('getcoordinador', [\App\Http\Controllers\DataController::class, 'getCoordinador']);
    Route::get('termcodeall', [\App\Http\Controllers\DataController::class, 'getTermcodeAll']);
    Route::post('carrera', [\App\Http\Controllers\DataController::class, 'getCarrera']);
    Route::get('carreraall', [\App\Http\Controllers\DataController::class, 'getCarreraAll']);
    Route::get('reasons', [\App\Http\Controllers\DataController::class, 'getReason']);
    Route::post('periodos', [\App\Http\Controllers\DataController::class, 'getPeriodos']);
    Route::post('campanias', [\App\Http\Controllers\DataController::class, 'getCampanias']);
    Route::post('termcodeTeacher', [\App\Http\Controllers\DataController::class, 'getTermcodeTeacher']);
});

Route::group(['prefix' => 'grupos', 'middleware' => 'auth:sanctum'], function () {

    Route::post('list-nrcs-docente', [\App\Http\Controllers\GruposController::class, 'listNrcsDocente']);
    Route::post('list-nrcs-jurado', [\App\Http\Controllers\GruposController::class, 'listNrcsJurados']);
    Route::post('list-grupos', [\App\Http\Controllers\GruposController::class, 'listGrupos']);
    Route::post('list-alumnos', [\App\Http\Controllers\GruposController::class, 'listAlumnos']);

    Route::post('agregar-alumno', [\App\Http\Controllers\GruposController::class, 'agregarAlumnos']);
    Route::post('agregar-grupo-masivo', [\App\Http\Controllers\GruposController::class, 'agregarMasivo']);

    Route::post('eliminar-alumno-grupo', [\App\Http\Controllers\GruposController::class, 'eliminarAlumnoGrupo']);
    Route::post('eliminar-grupo', [\App\Http\Controllers\GruposController::class, 'eliminarGrupo']);
    Route::post('generar-grupos', [\App\Http\Controllers\GruposController::class, 'generarGrupos']);
});

Route::group(['prefix' => 'evaluacion', 'middleware' => 'auth:sanctum'], function () {

    Route::post('list-rubricas-x-curso', [\App\Http\Controllers\EvaluacionController::class, 'lisRubricasXcurso']);
    Route::get('descarga-rubricas-x-curso', [\App\Http\Controllers\EvaluacionController::class, 'descargaRubricasXcurso']);

    Route::post('list-alumnos', [\App\Http\Controllers\EvaluacionController::class, 'listAlumnos']);
    Route::post('alumno-save-individual', [\App\Http\Controllers\EvaluacionController::class, 'saveIndividual']);
    Route::post('alumno-save-grupal', [\App\Http\Controllers\EvaluacionController::class, 'saveGrupal']);
    Route::post('comentario-save-individual', [\App\Http\Controllers\EvaluacionController::class, 'saveCommentIndividual']);
    Route::post('comentario-save-grupal', [\App\Http\Controllers\EvaluacionController::class, 'saveCommentGrupal']);

    Route::get('preview-pdf', [\App\Http\Controllers\EvaluacionController::class, 'previewPdf']);
    Route::post('view-pdf', [\App\Http\Controllers\EvaluacionController::class, 'viewPdf']);
});

Route::get('/{any}', [ApplicationController::class, 'index'])->where('any', '.*');
