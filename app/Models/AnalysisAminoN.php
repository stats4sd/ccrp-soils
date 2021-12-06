<?php

namespace App\Models;

use App\Models\Sample;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\AnalysisAminoN
 *
 * @property int $id
 * @property string|null $sample_id
 * @property string|null $analysis_date
 * @property float|null $weight_soil
 * @property float|null $weight_blank_acid_titrant
 * @property float|null $weight_sample_acid_titrant
 * @property float|null $corrected_acid_titrant
 * @property float|null $titer_conversion_microg_n
 * @property float|null $mg_kg_aminsugar_n
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $result
 * @property-read \App\Models\Sample|null $sample
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalysisAminoN newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalysisAminoN newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalysisAminoN query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalysisAminoN whereAnalysisDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalysisAminoN whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalysisAminoN whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalysisAminoN whereSampleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalysisAminoN whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalysisAminoN whereWeightSoil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalysisAminoN whereWeightBlankAcidTitrant($value)weight_blank_acid_titrant
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalysisAminoN whereWeightSampleAcidTitrant($value)weight_sample_acid_titrant
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalysisAminoN whereCorrectedAcidTitrant($value)corrected_acid_titrant
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalysisAminoN whereTiterConversionMicrogN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalysisAminoN whereMgKgAminsugarN($value)
 * @mixin \Eloquent
 * @property int $project_submission_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalysisAminoN whereProjectSubmissionId($value)
 */
class AnalysisAminoN extends Model
{
    public $table = "analysis_amino_n";

    protected $guarded = [];

    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }

    public function project_submission()
    {
        return $this->belongsTo(ProjectSubmission::class);
    }

    public function getResultAttribute ()
    {
        return $this->mg_kg_aminsugar_n;
    }
}
