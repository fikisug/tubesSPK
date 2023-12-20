<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Criteria;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HasilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $criteria1 = Criteria::all();
        $score1 = Score::all();
        $sc = $score1->groupBy('alternatif');

        // Mengambil semua skor alternatif beserta informasi terkait
        $score = Score::get();

        // Mengambil semua kriteria bobot
        $criteria = Criteria::get();

        // Mengambil semua alternatif (you may need to adjust this based on your model)
        $alternatif = Alternatif::get();

        // Memanggil fungsi untuk normalisasi Moora
        $normalizedScores = $this->normalisasi();
        $normalisasi = $normalizedScores['normalisasi'];
        $normTerbobot = $normalizedScores['normalisasiTerbobot'];
        $sumBenefit = $normalizedScores['sumBenefit'];
        $sumCost = $normalizedScores['sumCost'];
        $yi = $normalizedScores['yi'];
        $rank = $normalizedScores['rank'];
        // dd($normalizedScores);
        // dd($normTerbobot);
        // dd($rank);

        return view('hasil', compact('criteria1', 'sc', 'score', 'criteria', 'alternatif', 'normalisasi', 'normTerbobot', 'sumBenefit', 'sumCost', 'yi', 'rank'));
    }

    // Fungsi untuk normalisasi Moora
    private function normalisasi()
    {
        $data = Score::select('score.id', 'score.criteria', 'score.alternatif', 'score.score', 'a.bobot', 'a.jenis')
            ->leftJoin('criteria as a', 'a.nama', '=', 'score.criteria')
            ->get()
            ->toArray();

        $criteria = Criteria::all(['nama', 'bobot', 'jenis'])->toArray();

        $alternatif = Alternatif::all(['nama'])->toArray();

        $akarKuadrat = [];
        $normalisasi = [];
        $normalisasiTerbobot = [];

        $sumBenefit = [];
        $sumCost = [];

        $Yi = [];

        // hitung nilai akar
        foreach ($criteria as $criterion) {
            $akarKuadrat[$criterion['nama']] = DB::table('score')->where('criteria', $criterion)->select(DB::raw('SQRT(SUM(POWER(score, 2)))as asd'))->first();
        }

        //hitung normalisasi
        foreach ($data as $item) {
            $normalisasi[$item['criteria']][$item['alternatif']] = ($item['score'] / $akarKuadrat[$item['criteria']]->asd);
            $normalisasiTerbobot[$item['jenis']][$item['criteria']][$item['alternatif']] = ($normalisasi[$item['criteria']][$item['alternatif']] * $item['bobot']);
        }

        // dd($normalisasiTerbobot);

        foreach ($alternatif as $alt) {
            // dd($normalisasiTerbobot);
            $totBen = 0;
            $totCost = 0;
            foreach ($criteria as $crt) {
                // dd($criteria);
                if ($crt['jenis'] == 0) {
                    $totBen += $normalisasiTerbobot[$crt['jenis']][$crt['nama']][$alt['nama']];
                }
                if ($crt['jenis'] == 1) {
                    $totCost += $normalisasiTerbobot[$crt['jenis']][$crt['nama']][$alt['nama']];
                }
            }
            $sumBenefit[$alt['nama']] = ($totBen);
            $sumCost[$alt['nama']] = ($totCost);
            $Yi[$alt['nama']] = ($sumBenefit[$alt['nama']] - $sumCost[$alt['nama']]);
        }

        arsort($Yi);
        $rank = [];

        $r = 1;
        foreach ($Yi as $key => $value) {
            $rank[$key] = $r++;
        }

        return [
            'normalisasi' => $normalisasi,
            'normalisasiTerbobot' => $normalisasiTerbobot,
            'sumBenefit' => $sumBenefit,
            'sumCost' => $sumCost,
            'yi' => $Yi,
            'rank' => $rank,
        ];
    }
}
