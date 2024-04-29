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
});

Route::group(['prefix' => 'auth', 'middleware' => 'auth:sanctum'], function () {

    Route::post('logout',   [LoginController::class, 'logout']);
});

Route::group(['prefix' => 'system', 'middleware' => 'auth:sanctum'], function () {
    Route::resource('menu',   \App\Http\Controllers\MenuItemController::class);
    Route::resource('mensajes', \App\Http\Controllers\InicioMensajeController::class);
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

Route::group(['prefix' => 'maintainer', 'middleware' => 'auth:sanctum'], function () {
    Route::resource('schedule', \App\Http\Controllers\ScheduleDistributionController::class);
    Route::resource('duedates', \App\Http\Controllers\DueDateController::class);
});



Route::group(['prefix' => 'nc', 'middleware' => 'auth:sanctum'], function () {
    Route::resource('generacionnc', \App\Http\Controllers\GeneracionNcController::class);
    Route::post('save', [\App\Http\Controllers\GeneracionNcController::class, 'save']);
});

Route::group(['prefix' => 'tc', 'middleware' => 'auth:sanctum'], function () {
    Route::resource('teachers', \App\Http\Controllers\TeacherController::class);
    Route::post('save', [\App\Http\Controllers\TeacherController::class, 'save']);
    Route::post('disabled', [\App\Http\Controllers\TeacherController::class, 'disabled']);
    //Route::post('update', [\App\Http\Controllers\UserController::class, 'update']);  
});

Route::group(['prefix' => 'ic', 'middleware' => 'auth:sanctum'], function () {
    Route::resource('informs', \App\Http\Controllers\InformController::class);
    Route::post('envio-masivo', [\App\Http\Controllers\InformController::class, 'envio_masivo']);
    Route::post('update-information', [\App\Http\Controllers\InformController::class, 'actualizar_informacion']);
    Route::get('view-photo/{dni}', [\App\Http\Controllers\InformController::class, 'show']);
    Route::post('files', [\App\Http\Controllers\InformController::class, 'files']);
    Route::post('store', [\App\Http\Controllers\InformController::class, 'store']);
    Route::post('course', [\App\Http\Controllers\InformController::class, 'searchCourse']);
    Route::post('area', [\App\Http\Controllers\InformController::class, 'searchArea']);
    Route::post('certificado-adicional', [\App\Http\Controllers\InformController::class, 'certificadoAdicional']);
    Route::post('certificado-grado-academico', [\App\Http\Controllers\InformController::class, 'certificadoGradoAcademico']);
    Route::put('searchObs/{pidm}', [\App\Http\Controllers\InformController::class, 'searchObs']);

    //Route::post('update', [\App\Http\Controllers\UserController::class, 'update']);  
});
Route::group(['prefix' => 'datos-laborales', 'middleware' => 'auth:sanctum'], function () {
    Route::post('files', [\App\Http\Controllers\DatosLaboralesController::class, 'files']);
    Route::post('filesCe', [\App\Http\Controllers\DatosLaboralesController::class, 'filesCe']);
    Route::post('filesEp', [\App\Http\Controllers\DatosLaboralesController::class, 'filesEp']);
    Route::post('filesEd', [\App\Http\Controllers\DatosLaboralesController::class, 'filesEd']);
    Route::post('store', [\App\Http\Controllers\DatosLaboralesController::class, 'store']);
});

Route::group(['prefix' => 'datos-academicos', 'middleware' => 'auth:sanctum'], function () {
    Route::post('store', [\App\Http\Controllers\DatosAcademicosController::class, 'store']);
});

Route::group(['prefix' => 'programacion-horaria', 'middleware' => 'auth:sanctum'], function () {
    Route::post('visualizar', [\App\Http\Controllers\ProgramacionController::class, 'verProgramacionHoraria']);
});

Route::group(['prefix' => 'mi-nps', 'middleware' => 'auth:sanctum'], function () {
    Route::post('visualizar', [\App\Http\Controllers\NpsController::class, 'verNpsDocente']);
});

Route::group(['prefix' => 'estado', 'middleware' => 'auth:sanctum'], function () {
    Route::post('listar', [\App\Http\Controllers\EstadoDocenteController::class, 'listar']);
    Route::put('updatedStatus/{pidm}', [\App\Http\Controllers\EstadoDocenteController::class, 'updatedStatus']);
    Route::put('updatedCoordinador/{pidm}', [\App\Http\Controllers\EstadoDocenteController::class, 'updatedCoordinador']);
    Route::put('updatedTimeLine/{pidm}', [\App\Http\Controllers\EstadoDocenteController::class, 'updatedTimeLine']);
    Route::delete('/deleteObservacion/{pidm}/{periodo}', [\App\Http\Controllers\EstadoDocenteController::class, 'deleteObservacion']);
});
/*
Route::group(['prefix' => 'at','middleware' => 'auth:sanctum'], function () {
    Route::resource('schedule', \App\Http\Controllers\AreaTematicaController::class);
});

Route::group(['prefix' => 'admission','middleware' => 'auth:sanctum'], function () {
    Route::post('getPersona', [\App\Http\Controllers\AdmissionController::class, 'index']);
    Route::post('files', [\App\Http\Controllers\AdmissionController::class, 'files']);
    Route::resource('admission', \App\Http\Controllers\AdmissionController::class); 
});
*/
Route::group(['prefix' => 'reports', 'middleware' => 'auth:sanctum'], function () {
    Route::post('get-documents', [\App\Http\Controllers\Reports\DocumentsController::class, 'index']);
    Route::post('get-carreras', [\App\Http\Controllers\Reports\DocumentsController::class, 'getCarreras']);
    Route::post('get-tiposdoc', [\App\Http\Controllers\Reports\DocumentsController::class, 'getTiposdoc']);
    Route::get('view-document/{file}', [\App\Http\Controllers\Reports\DocumentsController::class, 'viewDocument']);
    Route::post('view-img-post', [\App\Http\Controllers\Reports\DocumentsController::class, 'viewImgPost'])->name('viewImgPost');
    Route::get('view-img/{file}', [\App\Http\Controllers\Reports\DocumentsController::class, 'viewImg']);
    Route::post('export-xls', [\App\Http\Controllers\Reports\DocumentsController::class, 'exportXls']);
});

Route::group(['prefix' => 'us', 'middleware' => 'auth:sanctum'], function () {
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('templates', \App\Http\Controllers\TemplateController::class);
    //Route::post('update', [\App\Http\Controllers\UserController::class, 'update']);  
});

Route::group(['prefix' => 'ac', 'middleware' => 'auth:sanctum'], function () {
    Route::resource('academics', \App\Http\Controllers\AcademicController::class);
    Route::post('download', [\App\Http\Controllers\AcademicController::class, 'download']);
    Route::post('export', [\App\Http\Controllers\AcademicController::class, 'export']);
    Route::post('disabled', [\App\Http\Controllers\AcademicController::class, 'disabled']);
    Route::post('eliminar', [\App\Http\Controllers\AcademicController::class, 'eliminarArchivoZIP']);
});


Route::group(['prefix' => 'role', 'middleware' => 'auth:sanctum'], function () {
    Route::get('rolesUser', [\App\Http\Controllers\RoleController::class, 'getRoles']);
});
Route::get('/{any}', [ApplicationController::class, 'index'])->where('any', '.*');
