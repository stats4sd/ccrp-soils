<?php

namespace App\Models;

use App\Models\Project;
use App\Models\AnalysisP;
use App\Models\AnalysisPh;
use App\Models\AnalysisPom;
use App\Models\AnalysisPoxc;
use App\Models\AnalysisAminoN;
use App\Models\Views\SampleMerged;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\Sample
 *
 * @property string $id
 * @property string|null $username
 * @property int|null $site_id
 * @property string|null $date
 * @property int|null $depth
 * @property string|null $texture
 * @property int|null $at_plot
 * @property string|null $plot_photo
 * @property float|null $longitude
 * @property float|null $latitude
 * @property float|null $altitude
 * @property float|null $accuracy
 * @property string|null $comment
 * @property string|null $community_quick
 * @property int $project_id
 * @property string|null $farmer_quick
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnalysisP[] $analysis_p
 * @property-read int|null $analysis_p_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnalysisPh[] $analysis_ph
 * @property-read int|null $analysis_ph_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnalysisPom[] $analysis_pom
 * @property-read int|null $analysis_pom_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnalysisPoxc[] $analysis_poxc
 * @property-read int|null $analysis_poxc_count
 * @property-read mixed $poxc_result
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnalysisAminoN[] $analysis_amino_n
 * @property-read int|null $analysis_amino_n_count
 * @property-read \App\Models\Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereAccuracy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereAltitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereAtPlot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereCommunityQuick($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereFarmerQuick($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample wherePlotPhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereTexture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereUsername($value)
 * @mixin \Eloquent
 * @property int|null $plot_id
 * @property int $project_submission_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnalysisAgg[] $analysis_agg
 * @property-read int|null $analysis_agg_count
 * @property-read mixed $p_result
 * @property-read mixed $ph_result
 * @property-read mixed $pom_result
 * @property-read \App\Models\Views\SampleMerged|null $samples_merged
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample wherePlotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sample whereProjectSubmissionId($value)
 */
class Sample extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    public $incrementing = false;
    public $guarded = [];
    public $casts = [
        'identifiers' => 'array',
    ];

    public $appends = [
        'poxc_result',
        'p_result',
        'lr_p_result',
        'hr_p_result',
        'custom_r_p_result',
        'ph_result',
        'pom_percent',
        'pom_diameter',
        'amino_n_result',
        'total_stableaggregates',
        'twomm_aggreg_pct_result',
        'twofiftymicron_aggreg_pct_result',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function project_submission()
    {
        return $this->belongsTo(ProjectSubmission::class);
    }


    public function getPoxcResultAttribute()
    {
        if ($this->analysis_poxc->count() > 0) {
            if ($this->analysis_poxc->first()->poxc_soil_corrected) {
                return $this->analysis_poxc->first()->poxc_soil_corrected;
            }
            return $this->analysis_poxc->first()->poxc_soil;
        }
        return null;
    }

    //P is split into LR and HR / custom R results:
    public function getPResultAttribute()
    {
        if ($this->analysis_p->count() > 0) {
            if ($this->analysis_p->first()->olsen_p_corrected) {
                return $this->analysis_p->first()->olsen_p_corrected;
            }
            return $this->analysis_p->first()->olsen_p;
        }

        return null;
    }

    // This really needs refactoring...
    public function getLrPResultAttribute()
    {
        if ($this->analysis_p->count() > 0) {
            $result = $this->analysis_p->filter(function ($analysis) {
                return $analysis->reagents == "LR";
            });

            if ($result->count() > 0) {
                if ($result->first()->olsen_p_corrected) {
                    return $result->first()->olsen_p_corrected;
                }
                return $result->first()->olsen_p;
            }
        }

        return null;
    }

    public function getHrPResultAttribute()
    {
        if ($this->analysis_p->count() > 0) {
            $result = $this->analysis_p->filter(function ($analysis) {
                return $analysis->reagents == "HR";
            });

            if ($result->count() > 0) {
                if ($result->first()->olsen_p_corrected) {
                    return $result->first()->olsen_p_corrected;
                }
                return $result->first()->olsen_p;
            }
        }

        return null;
    }
    public function getCustomRPResultAttribute()
    {
        if ($this->analysis_p->count() > 0) {
            $result = $this->analysis_p->filter(function ($analysis) {
                return $analysis->reagents == "Custom R";
            });

            if ($result->count() > 0) {
                if ($result->first()->olsen_p_corrected) {
                    return $result->first()->olsen_p_corrected;
                }
                return $result->first()->olsen_p;
            }
        }

        return null;
    }


    public function getPhResultAttribute()
    {
        if ($this->analysis_ph->count() > 0) {
            return $this->analysis_ph->first()->reading_ph;
        }

        return null;
    }

    public function getPomPercentAttribute()
    {
        if ($this->analysis_pom->count() > 0) {
            return $this->analysis_pom->first()->percent_pom;
        }

        return null;
    }

    public function getPomDiameterAttribute()
    {
        if ($this->analysis_pom->count() > 0) {
            return $this->analysis_pom->first()->diameter_circ_pom;
        }

        return null;
    }

    public function getAminoNResultAttribute()
    {
        if ($this->analysis_amino_n->count() > 0) {
            return $this->analysis_amino_n->first()->mg_kg_aminsugar_n;
        }

        return null;
    }

    public function getTotalStableaggregatesAttribute()
    {
        if ($this->analysis_agg->count() > 0) {
            return $this->analysis_agg->first()->total_stableaggregates;
        }

        return null;
    }

    public function getTwommAggregPctResultAttribute()
    {
        if ($this->analysis_agg->count() > 0) {
            return $this->analysis_agg->first()->twomm_aggreg_pct_result;
        }

        return null;
    }

    public function getTwofiftymicronAggregPctResultAttribute()
    {
        if ($this->analysis_agg->count() > 0) {
            return $this->analysis_agg->first()->twofiftymicron_aggreg_pct_result;
        }

        return null;
    }


    public function analysis_p()
    {
        return $this->hasMany(AnalysisP::class);
    }

    public function analysis_ph()
    {
        return $this->hasMany(AnalysisPh::class);
    }

    public function analysis_pom()
    {
        return $this->hasMany(AnalysisPom::class);
    }

    public function analysis_poxc()
    {
        return $this->hasMany(AnalysisPoxc::class);
    }

    public function analysis_amino_n()
    {
        return $this->hasMany(AnalysisAminoN::class);
    }

    public function analysis_agg()
    {
        return $this->hasMany(AnalysisAgg::class);
    }

    public function samples_merged()
    {
        return $this->hasOne(SampleMerged::class);
    }
}
