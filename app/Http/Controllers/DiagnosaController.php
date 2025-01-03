<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use App\Models\{Gejala, Penyakit, Rule, Riwayat};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;


class DiagnosaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:diagnosa', ['only' => ['index']]);
        $this->middleware('permission:diagnosa-create', ['only' => ['diagnosa']]);
    }

    public function index()
    {
        $gejala = Gejala::all();

        return view('admin.diagnosa', compact('gejala'));
    }

  
    public function tingkat_keyakinan($keyakinan)
    {
        switch ($keyakinan) {
            case 0:
                return 'Tidak';
            case 1:
                return 'Ya';
            default:
                return 'Unknown'; 
        }
    }

  
    public function calculateSum($data_penyakit)
    {
        $hasil_diagnosa = [];

        foreach ($data_penyakit as $final) {
            if (count($final) < 2) {
                continue; 
            }

            $sum = 0;

            foreach ($final as $key => $value) {
                if ($key === 0) {
                    continue; 
                }

                if (is_array($value) && isset($value[1]) && isset($value[2])) {
                    $sum += $value[1] * $value[2]; 
                }
            }

           
            $hasil_diagnosa[] = [
                'nama_penyakit' => $final[0]['nama'] ?? 'Unknown',
                'kode_penyakit' => $final[0]['kode'] ?? 'Unknown',
                'total' => $sum
            ];
        }

        return $hasil_diagnosa;
    }

    
    public function kalkulasi_cf($data)
    {
        $data_penyakit = [];
        $gejala_terpilih = [];

        foreach ($data['diagnosa'] as $input) {
            if (!empty($input)) {
                $opts = explode('+', $input);
                $gejala = Gejala::with('penyakits')->find($opts[0]);

                foreach ($gejala->penyakits as $penyakit) {
                    
                    if (empty($data_penyakit[$penyakit->id])) {
                        $data_penyakit[$penyakit->id] = [
                            $penyakit, 
                            [$gejala, $opts[1], $penyakit->pivot->value_cf]
                        ];
                    } else {
                        
                        array_push($data_penyakit[$penyakit->id], [$gejala, $opts[1], $penyakit->pivot->value_cf]);
                    }

                    if (empty($gejala_terpilih[$gejala->id])) {
                        $gejala_terpilih[$gejala->id] = [
                            'nama' => $gejala->nama,
                            'kode' => $gejala->kode,
                            'cf_user' => $opts[1],
                            'keyakinan' => $this->tingkat_keyakinan($opts[1])
                        ];
                    }
                }
            }
        }


        $hasil_diagnosa = $this->calculateSum($data_penyakit);

        return [
            'hasil_diagnosa' => $hasil_diagnosa,
            'gejala_terpilih' => $gejala_terpilih
        ];
    }

    public function diagnosa(Request $request)
    {
        $name = auth()->user()->name;

    
        if (auth()->user()->hasRole('Admin')) {
            $request->validate(['nama' => 'required|string|max:100']);
            $name = $request->nama;
        }

        $data = $request->all();

        $result = $this->kalkulasi_cf($data);

        if (empty($result['hasil_diagnosa'])) {
            return back()->withErrors(['Terjadi sebuah kesalahan']);
        }


        $cf_max = max(array_column($result['hasil_diagnosa'], 'total'));


        $riwayat = Riwayat::create([
            'nama' => $name,
            'hasil_diagnosa' => serialize($result['hasil_diagnosa']),
            'gejala_terpilih' => serialize($result['gejala_terpilih']),
            'cf_max' => $cf_max, 
            'user_id' => auth()->id()
        ]);


        $path = public_path('storage/downloads');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $file_pdf = 'Diagnosa-' . $name . '-' . time() . '.pdf';
        PDF::loadView('pdf.riwayat', ['id' => $riwayat->id])
            ->save($path . "/" . $file_pdf);

 
        $riwayat->update(['file_pdf' => $file_pdf]);


        return redirect()->to(route('admin.riwayat', $riwayat->id));
    }
}
