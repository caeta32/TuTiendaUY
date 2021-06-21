<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('clientes') ->insert([
            'nombre'=>'Usuario',
            'apellido'=>'PlaceHolder',
            'fecha'=>'01/01/2000',
            'email'=>'placeholder@email.com',
            'telefono'=>'099123456',
            'direccion'=>'Calle 01 esq. Calle 02',
            'postal'=>'20000',
            'pass'=>Hash::make('password')
        ]);
    }
}
