<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountingCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('accounting_codes')->insert([
            ['code' => 6000, 'description' => 'Compras', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['code' => 4720, 'description' => 'IVA Soportado', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['code' => 4000, 'description' => 'Proveedores', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
