<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BobotKriteria;
use App\Models\University;

class BobotController extends Controller
{
    public function index()
    {
        $bobot = BobotKriteria::all()->keyBy('kriteria');
        return view('bobot', compact('bobot'));
    }

    public function store(Request $request)
    {
        $input = $request->all();

        // Update custom bobot
        foreach ($input['bobot'] as $key => $custom_bobot) {
            $bobot = BobotKriteria::where('kriteria', $key)->first();
            if ($bobot) {
                $bobot->custom_bobot = $custom_bobot;
                $bobot->save();
            }
        }
    
        // Safely update default bobot for each criteria
        $criteria = ['spp', 'akreditasi', 'dosen_s3', 'lokasi'];
    
        foreach ($criteria as $criterion) {
            if (isset($input['bobot'][$criterion])) {
                BobotKriteria::where('kriteria', ucfirst($criterion))
                    ->update(['default_bobot' => $input['bobot'][$criterion]]);
            }
        }
    
        // Trigger ranking calculation
        $rankingData = $this->ranking(); // Call ranking method to get ranking data
    
        return response()->json(['success' => 'Bobot berhasil diperbarui!', 'ranking' => $rankingData]);
    }

    public function ranking()
    {
        $universities = University::all();
        $weights = BobotKriteria::all()->keyBy('kriteria');

        // Calculate scores and rankings
        foreach ($universities as $university) {
            $score = $this->calculateScore($university, $weights);
            $university->score = $score; // Add score to university object
        }

        // Sort universities by score
        $rankedUniversities = $universities->sortByDesc('score');

        return view('ranking', compact('rankedUniversities'));
    }

    private function calculateScore($university, $weights)
    {
        // Initialize score
        $score = 0;
        
        // Calculate the total score by applying the custom weights
        if (isset($weights['SPP'])) {
            $score += (float)$university->spp * (float)$weights['SPP']->custom_bobot;
        }
        if (isset($weights['Akreditasi'])) {
            $score += (float)$university->akreditasi * (float)$weights['Akreditasi']->custom_bobot;
        }
        if (isset($weights['DosenS3'])) {
            $score += (float)$university->dosen_s3 * (float)$weights['DosenS3']->custom_bobot;
        }
        if (isset($weights['Lokasi'])) {
            $score += (float)$university->lokasi * (float)$weights['Lokasi']->custom_bobot;
        }

        return $score;
    }
}
