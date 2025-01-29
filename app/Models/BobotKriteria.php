<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BobotKriteria extends Model
{
    use HasFactory;

    protected $table = 'bobot_kriteria';
    protected $fillable = ['kriteria', 'custom_bobot', 'default_bobot'];
    public $timestamps = true;

    public function normalizeWeights()
    {
        $weights = self::all();
        $totalWeight = $weights->sum('custom_bobot');

        foreach ($weights as $weight) {
            $normalizedBobot = $weight->custom_bobot / $totalWeight;
            $weight->update(['custom_bobot' => $normalizedBobot]);
        }
    }
}
