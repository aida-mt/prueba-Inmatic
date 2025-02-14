<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

// Define una ruta de tipo API para los recursos 'invoices'.
// Genera automáticamente rutas para todas las acciones CRUD del controlador InvoiceController,
// como index, store, show, update y destroy.
Route::apiResource('invoices', InvoiceController::class);
