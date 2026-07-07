<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Empresa::firstOrCreate(
            ['ruc' => '12345678901'],
            [
                'razon_social' => 'SISTEMA POS PRO',
                'nombre_comercial' => 'POS PREMIUM',
                'direccion_fiscal' => 'AV. TECNOLOGÍA 123',
                'ubigeo' => '150101',
                'departamento' => 'LIMA',
                'provincia' => 'LIMA',
                'distrito' => 'LIMA',
                'telefono' => '987654321',
                'correo' => 'contacto@pospro.com',
                'logo' => null,
            ]
        );
    }
}
