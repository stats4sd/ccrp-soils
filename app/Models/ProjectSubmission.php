<?php

namespace App\Models;

use App\Models\Project;
use App\Models\AnalysisP;
use App\Models\AnalysisPh;
use App\Models\AnalysisAgg;
use App\Models\AnalysisPom;
use App\Models\AnalysisPoxc;
use App\Models\AnalysisAminoN;
use App\Models\ProjectXlsform;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Models\ProjectSubmission
 *
 * @property int $id
 * @property string $uuid
 * @property string $submitted_at
 * @property int $project_xlsform_id
 * @property mixed $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProjectXlsform $project_xlsform
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectSubmission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectSubmission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectSubmission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectSubmission whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectSubmission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectSubmission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectSubmission whereProjectXlsformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectSubmission whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectSubmission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectSubmission whereUuid($value)
 * @mixin \Eloquent19
 */
class ProjectSubmission extends Model
{
    use CrudTrait,
    RevisionableTrait,
    SoftDeletes;

    protected $table = 'project_submissions';
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('project', function (Builder $builder) {
            if (Auth::check()) {
                if (! Auth::user()->isAdmin()) {
                    $userProjects = Auth::user()->projects()->get()->pluck('id')->toArray();

                    $builder->whereIn('project_submissions.project_id', $userProjects);
                }
            }
        });
    }

    public function identifiableName()
    {
        return $this->project_xlsform->title . ' - SAMPLE_ID: ' . $this->sample_id;
    }

    public function getSampleIdAttribute()
    {
        $content = json_decode($this->content);

        if (isset($content->sample_id)) {
            return $content->sample_id;
        }

        if (isset($content->no_bar_code)) {
            return $content->no_bar_code;
        }

        return null;
    }

    public function getProjectNameAttribute()
    {
        return $this->project ? $this->project->name : null;
    }

    public function getXlsformTitleAttribute()
    {
        return $this->project_xlsform ? $this->project_xlsform->title : null;
    }



    public function project_xlsform()
    {
        return $this->belongsTo(ProjectXlsform::class, 'project_xlsform_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }


    public function analysis_agg()
    {
        return $this->hasMany(AnalysisAgg::class);
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

    public function amino_n()
    {
        return $this->hasMany(AnalysisAminoN::class);
    }

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }
}
