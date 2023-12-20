<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Criteria;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CriteriaController extends Controller
{
    public function addCriteria(Request $request)
    {
        // Validate the request
        $request->validate([
            'criteriaNumber' => 'required|integer',
            'criteriaData' => 'required|array',
            'criteriaData.*.nama' => 'required|string',
            'criteriaData.*.bobot' => 'required|numeric',
            // 'criteriaData.*.jenis' => 'required',
            // 'criteriaData.*.deskripsi' => 'required|string',
        ]);

        // Process the criteria data
        $criteriaNumber = $request->input('criteriaNumber');
        $criteriaData = $request->input('criteriaData');

        // dd($criteriaData[0]['nama']);

        // Save the criteria data to the database
        $totalBobot = 0;
        foreach ($criteriaData as $data) {
            $totalBobot += $data['bobot'];
        }
        if($totalBobot == 1){
            Criteria::truncate();
            foreach ($criteriaData as $data) {
                Criteria::create([
                    'nama' => $data['nama'],
                    'bobot' => $data['bobot'],
                    'deskripsi' => $data['deskripsi'],
                    'jenis' => $data['jenis'],
                ]);
            }
            return response()->json(['message' => 'Criteria saved successfully']);
        }else{
            return response()->json(['message' => 'Gagal Menyimpan Criteria']);
        }
    }

    public function addAlternatif(Request $request)
    {
        // Validate the request
        $request->validate([
            'alternatifNumber' => 'required|integer',
            'alternatifData' => 'required|array',
            'alternatifData.*.nama2' => 'required|string',
            'alternatifData.*.score' => 'numeric',
            // 'criteriaData.*.deskripsi' => 'required|string',
        ]);

        // Get the form data
        $alternatifNumber = $request->input('alternatifNumber');
        $alternatifData = $request->input('alternatifData');

        $total = count($alternatifData);

        if($total > 0){
            Alternatif::truncate();
            Score::truncate();
        }else{
            
        }
        
        // Loop through alternatifData and save to the database
        foreach ($alternatifData as $alternatifDatum) {
            $alternatif = Alternatif::create([
                'nama' => $alternatifDatum['nama2'],
                'deskripsi' => $alternatifDatum['deskripsi2'] ?? '',
            ]);
    
            // Loop through scores and save to the database
            foreach ($alternatifDatum['scores'] as $score) {
                Score::create([
                    'alternatif' => $alternatif->nama, // Assuming you have 'id' column in Alternatif model
                    'criteria' => $score['criteria'],
                    'score' => $score['score'],
                ]);
            }
        }
        

        // You can return a response if needed
        return response()->json(['message' => 'Criteria saved successfully']);
    }

    public function getCriteria()
    {
        $criteriaNames = Criteria::all()->toArray();
        // dd($criteriaNames);
        return response()->json(['criteriaNames' => $criteriaNames]);
    }

    public function getCriteriaa()
    {
        $criteriaNames = Criteria::all()->pluck('nama')->toArray();
        // dd($criteriaNames);
        return response()->json($criteriaNames);
    }

    public function getScore()
    {
        $alternatif = Alternatif::all()->toArray();
        $score = Score::all()->toArray();
        $criteria = Criteria::all()->toArray();
        $countScore = count($score);
        
        return response()->json(['score' => $score, 'alternatif' => $alternatif, 'criteria' => $criteria, 'countScore' => $countScore]);
    }

    public function getScoree($alternatif)
    {
        $score = Score::all()->where('alternatif', $alternatif)->toArray();
        // dd($score);
        return response()->json(['score' => $score]);
    }

    public function deleteData(){
        $countCriteria = Criteria::count();
        if($countCriteria > 0){
            try {
                DB::table('criteria')->truncate();
                DB::table('alternatif')->truncate();
                DB::table('score')->truncate();
                return response()->json(['message' => 'Tabel berhasil di-reset']);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
        }else{
            return response()->json(['message' => 'Data Sudah Kosong']);
        }
        
    }
}
