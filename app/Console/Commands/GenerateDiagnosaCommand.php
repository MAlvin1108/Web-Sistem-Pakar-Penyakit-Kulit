<?php

namespace App\Console\Commands;

use Faker\Factory as Faker;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\DiagnosaController;
use App\Models\{Riwayat, Gejala};
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateDiagnosaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:diagnosa {total=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buat riwayat diagnosa secara dinamis';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $diagnosa = new DiagnosaController();
        $faker = Faker::create();

        $total_data = $this->argument('total');
        $this->warn("$total_data sample data");

        $gejala_id = Gejala::pluck('id')->all();
        $pilihan_keyakinan = [-4, 0.4, 0.6, 0.8, 1];

        $skipped = 0;

        for ($i = 1; $i <= $total_data; $i++) {
            $name = $faker->name();
            $jumlah_gejala = rand(4, 6);
            $gejala_terpilih = ['diagnosa' => []];  // Initialize diagnosa array

            for ($j = 0; $j < $jumlah_gejala; $j++) {
                $gejala = $gejala_id[rand(0, count($gejala_id) - 1)];
                $keyakinan = $pilihan_keyakinan[rand(0, count($pilihan_keyakinan) - 1)];
                $gejala_terpilih['diagnosa'][] = $gejala . "+" . $keyakinan;
            }

            // Perform the CF calculation
            $result = $diagnosa->kalkulasi_cf($gejala_terpilih);

            if (empty($result['hasil_diagnosa'])) {
                $this->error("Skipped: No valid diagnosis for sample ($i)");
                $skipped++;
                continue;
            }

            // Ensure cf_max is a scalar value, not an array
            $cf_max = $result['cf_max'] ? $result['cf_max'][1] : null;

            if (!$cf_max) {
                $this->error("Skipped: No CF max value for sample ($i)");
                $skipped++;
                continue;
            }

            // Generate a random date for the history record
            $random_date = Carbon::now()->subDays(rand(0, 10));

            // Create the history record
            $riwayat = Riwayat::create([
                'nama' => $name,
                'hasil_diagnosa' => serialize($result['hasil_diagnosa']),
                'cf_max' => $cf_max,  // Store cf_max as scalar value
                'gejala_terpilih' => serialize($result['gejala_terpilih']),
                'user_id' => 1,  // Assuming user ID is 1 for testing
                'created_at' => $random_date,
                'updated_at' => $random_date
            ]);

            $this->line("<fg=green>Generated sample($i): </>" . $name . " - " . $cf_max);

            // Ensure the directory for storing PDFs exists
            $path = public_path('storage/downloads');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            // Generate and save the PDF file
            $file_pdf = 'Diagnosa-' . $name . '-' . time() . '.pdf';
            PDF::loadView('pdf.riwayat', ['id' => $riwayat->id])
                ->save($path . "/" . $file_pdf);

            // Update the record with the generated PDF file path
            $riwayat->update(['file_pdf' => $file_pdf]);
        }

        $this->newLine();

        $generated = $total_data - $skipped;
        $this->line("Skipped : $skipped");
        $this->line("Generated : $generated");

        return 0;
    }
}
