<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//usuarios
Route::get('/usuarios', 'UsuariosController@index')->name('usuarios');
Route::post('/nuevoUsuario', 'UsuariosController@nuevoUsuario')->name('nuevoUsuario');
Route::post('/editarUsuario', 'UsuariosController@editarUsuario')->name('editarUsuario');
Route::post('/eliminarUsuario', 'UsuariosController@destroy')->name('eliminarUsuario');

//reportes
Route::get('/reportes', 'ReporteController@index')->name('reportes');
Route::post('/nuevoReporte', 'ReporteController@nuevoReporte')->name('nuevoReporte');
Route::post('/editarReporte', 'ReporteController@editarReporte')->name('editarReporte');
Route::post('/eliminarReporte', 'ReporteController@destroy')->name('eliminarReporte');

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
