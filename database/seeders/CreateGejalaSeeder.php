<?php

namespace Database\Seeders;

use App\Models\Gejala;
use Illuminate\Database\Seeder;

class CreateGejalaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'nama' => 'Benjolan kecil dan bulat',
                'kode' => 'G001'
            ],
            [
                'nama' => 'Berwarna merah/kekuningan/ungu/kecoklatan',
                'kode' => 'G002'
            ],
            [
                'nama' => 'Memiliki nanah',
                'kode' => 'G003'
            ],
            [
                'nama' => 'Berada diarea tubuh yang ditumbuhi rambut',
                'kode' => 'G004'
            ],
            [
                'nama' => 'Gatal',
                'kode' => 'G005'
            ],
            [
                'nama' => 'Perih',
                'kode' => 'G006'
            ],
            [
                'nama' => 'Berwarna merah dengan kepala putih',
                'kode' => 'G007'
            ],
            [
                'nama' => 'Sensitif',
                'kode' => 'G008'
            ],
            [
                'nama' => 'Terasa nyeri',
                'kode' => 'G009'
            ],
            [
                'nama' => 'Terjadi pembengkakan',
                'kode' => 'G010'
            ],
            [
                'nama' => 'Demam',
                'kode' => 'G011'
            ],
            [
                'nama' => 'Bersisik di area yang terkena penyakit',
                'kode' => 'G012'
            ],
            [
                'nama' => 'Teradapat ruam atau kerak melingkar di area kulit',
                'kode' => 'G013'
            ],
            [
                'nama' => 'Kulit melepuh',
                'kode' => 'G014'
            ],
            [
                'nama' => 'Kulit bentol-bentol/bintik-bintik',
                'kode' => 'G015'
            ],
            [
                'nama' => 'Kulit terasa kering',
                'kode' => 'G016'
            ],
            [
                'nama' => 'Mengeluarkan cairan',
                'kode' => 'G017'
            ],
            [
                'nama' => 'Mengelupas',
                'kode' => 'G018'
            ],
            [
                'nama' => 'Kulit terasa terbakar/panas',
                'kode' => 'G019'
            ],
            [
                'nama' => 'Bercak putih terang',
                'kode' => 'G020'
            ],
            [
                'nama' => 'Terlihat mengkilap',
                'kode' => 'G021'
            ],
            [
                'nama' => 'Kelelahan',
                'kode' => 'G022'
            ],
            [
                'nama' => 'Sakit tenggorokan',
                'kode' => 'G023'
            ],
            [
                'nama' => 'Pilek batuk',
                'kode' => 'G024'
            ],
            [
                'nama' => 'Nafsu makan menurun',
                'kode' => 'G025'
            ]
        ];

        Gejala::insert($data);
    }
}
