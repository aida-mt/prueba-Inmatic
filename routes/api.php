<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

/**
 * Define una ruta GET para el filtrado de facturas.
 * Esta ruta acepta solicitudes GET en 'api/invoices/search' y
 * llama al método 'search' del InvoiceController
 *
 * Ejemplo de uso:
 * - /api/invoices/search?status=paid,draft&date_from=2025-01-01&date_to=2025-01-31
*/
Route::get('invoices/search', [InvoiceController::class, 'search']);

/**
 * Define una ruta de tipo API para los recursos 'invoices'.
 * Genera automáticamente rutas para todos los métodos CRUD del controlador InvoiceController,
 * como index, store, show, update y destroy.
*/
Route::apiResource('invoices', InvoiceController::class);
