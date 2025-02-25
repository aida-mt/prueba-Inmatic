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
 * Define una ruta GET para obtener un resumen de factura agrupadas por proveedor y fecha.
 * Este ruta podría recibir un parámetro "format" para permitir que el rango de fecha sea dinámico.
 * Si no se proporciona, se utiliza el valor predeterminado definido en la clase InvoiceReportQueryBuilder.
 *
 * Ejemplo de uso:
 * - /api/invoices/summary
 *
 * Ejemplo de ruta con el parámetro dinámico "format":
 * - /api/invoices/summary?format=%Y
 */
Route::get('invoices/summary', [InvoiceController::class, 'summary']);

/**
 * Define una ruta POST para la creación masiva de facturas.
 * Esta ruta acepta solicitudes POST en 'api/invoices/bulk-store' y
 * llama al método 'bulkStore' del InvoiceController para crear múltiples facturas.
 *
 * Ejemplo de uso:
 * - POST /api/invoices/bulk-store con el payload de las facturas
 */
Route::post('invoices/bulk-store', [InvoiceController::class, 'bulkStore']);
/**
 * Define una ruta de tipo API para los recursos 'invoices'.
 * Genera automáticamente rutas para todos los métodos CRUD del controlador InvoiceController,
 * como index, store, show, update y destroy.
*/
Route::apiResource('invoices', InvoiceController::class);
