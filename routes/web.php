<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FactusController;

Route::get('/obtener-token', [FactusController::class, 'obtenerAccessToken']);

Route::get('/api/facturas', [FactusController::class, 'obtenerFacturas']);

Route::get('/factura/{number}', [FactusController::class, 'verFactura']);

Route::get('/municipios', [FactusController::class, 'obtenerMunicipios']);

Route::get('/rangos-numeracion', [FactusController::class, 'obtenerRangosNumeracion']);

Route::get('/unidades-medida', [FactusController::class, 'obtenerUnidadesMedida']);

Route::get('/tributos-productos', [FactusController::class, 'obtenerTributosProductos']);

Route::post('/generar-factura', [FactusController::class, 'generarFactura']);

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/facturacion', function () {
    return view('facturacion');
});

Route::get('/facturas', function () {
    return view('listado_facturas');
});
