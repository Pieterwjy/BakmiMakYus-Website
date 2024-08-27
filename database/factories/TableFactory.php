<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Table>
 */
class TableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $table_number = $this->faker->randomNumber();
        // $qrCodePath = 'qr_codes/table_' . $table_number . '.png';
        // QrCode::format('png')->size(200)->generate(route('table.scan', $table_number), public_path($qrCodePath));
        // return [
        //     'table_number' => $table_number,
        //     'table_capacity'=> $this->faker->randomDigit(),
        //     'table_qr' => $qrCodePath
            
        // ];
        
        // ambil di tempat
        $table_number = 0;
        $qrCodePath = 'qr_codes/table_' . $table_number . '.png';
        QrCode::format('png')->size(200)->generate(route('table.scan', $table_number), public_path($qrCodePath));
        return [
            'table_number' => $table_number,
            'table_capacity'=> 0,
            'table_qr' => $qrCodePath
            
        ];
    }
}
