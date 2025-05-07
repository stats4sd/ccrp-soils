<?php

namespace App\Models;

use App\Models\Project;
use App\Models\Xlsform;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\ProjectXlsform
 *
 * @property int $id
 * @property int $project_id
 * @property int $xlsform_id
 * @property string|null $kobo_version_id
 * @property int $records
 * @property string|null $kobo_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $project
 * @property-read int|null $project_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Xlsform[] $xls_form
 * @property-read int|null $xls_form_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectXlsform newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectXlsform newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectXlsform query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectXlsform whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectXlsform whereDeployed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectXlsform whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectXlsform whereKoboId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectXlsform whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectXlsform whereRecords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectXlsform whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectXlsform whereXlsformId($value)
 * @mixin \Eloquent
 * @property int $processing If true, this entire entry should not be editable
 * @property int $is_active If true, this project-form is deployed and active on Kobotoolbox
 * @property string|null $enketo_url If null; for mis not currently deployed/active on Kobo
 * @property-read mixed $title
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProjectSubmission[] $project_submissions
 * @property-read int|null $project_submissions_count
 * @property-read \App\Models\Xlsform $xlsform
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectXlsform whereEnketoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectXlsform whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectXlsform whereKoboVersionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProjectXlsform whereProcessing($value)
 */
class ProjectXlsform extends Pivot
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'project_xlsform';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    public $incrementing = true;

    public $appends = [
        'title',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getTitleAttribute()
    {
        $project_name = str_replace('/', '_', $this->project->name);
        $form_name = str_replace('/', '_', $this->xlsform->title);

        return $project_name . ' - ' . $form_name;
    }

    public function getRecordsAttribute()
    {
        return $this->project_submissions()->count();
    }



    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function xlsform()
    {
        return $this->belongsTo(Xlsform::class);
    }

    public function project_submissions()
    {
        return $this->hasMany(ProjectSubmission::class, 'project_xlsform_id');
    }

}
