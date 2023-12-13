<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Criteria;
use App\Models\Score;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function addCriteria(Request $request)
    {
        // Validate the request
        $request->validate([
            'criteriaNumber' => 'required|integer|min:1',
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
        foreach ($criteriaData as $data) {
            Criteria::create([
                'nama' => $data['nama'],
                'bobot' => $data['bobot'],
                'deskripsi' => $data['deskripsi'],
                'jenis' => $data['jenis'],
            ]);
        }

        // You can return a response if needed
        return response()->json(['message' => 'Criteria saved successfully']);
    }

    public function addAlternatif(Request $request)
    {
        // Validate the request
        $request->validate([
            'alternatifNumber' => 'required|integer|min:1',
            'alternatifData' => 'required|array',
            'alternatifData.*.nama2' => 'required|string',
            'alternatifData.*.score' => 'numeric',
            // 'criteriaData.*.deskripsi' => 'required|string',
        ]);

        // Get the form data
        $alternatifNumber = $request->input('alternatifNumber');
        $alternatifData = $request->input('alternatifData');
        
        // Loop through alternatifData and save to the database
        foreach ($alternatifData as $alternatifDatum) {
            $alternatif = Alternatif::create([
                'nama' => $alternatifDatum['nama2'],
                'deskripsi' => $alternatifDatum['deskripsi2'],
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
        // dd($score);
        return response()->json(['score' => $score, 'alternatif' => $alternatif, 'criteria' => $criteria]);
    }

    public function getScoree($alternatif)
    {
        $score = Score::all()->where('alternatif', $alternatif)->toArray();
        // dd($score);
        return response()->json(['score' => $score]);
    }
}
