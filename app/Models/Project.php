<?php

namespace App\Models;

use App\Models\Sample;
use App\Models\Submission;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Project
 *
 * @property int $id
 * @property int $creator_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $avatar
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $share_data
 * @property-read mixed $admins
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sample[] $samples
 * @property-read int|null $samples_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Submission[] $submissions
 * @property-read int|null $submissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Xlsform[] $xls_forms
 * @property-read int|null $xls_forms_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereShareData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project withoutTrashed()
 * @mixin \Eloquent
 */
class Project extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];

    protected $casts = [
        'share_data' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS / Getters / Setters
    |--------------------------------------------------------------------------
    */

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setAvatarAttribute($value)
    {
        $disk = "public";
        $destination_path = "projects/avatars";
        $this->uploadFileToDisk($value, "avatar", $disk, $destination_path);
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function xls_forms ()
    {
        return $this->belongsToMany(Xlsform::class)->using(Projectxlsform::class)
        ->withPivot([
            'kobo_id',
            'deployed',
            'records',
        ]);
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'projects_members')->withPivot('admin');
    }

    // Filtered sets of users();
    public function admins ()
    {
       return $this->users()->wherePivot('admin', 1);
    }

    public function members ()
    {
       return $this->users()->wherePivot('admin', 0);
    }


    public function submissions ()
    {
        return $this->hasMany(Submission::class);
    }

    public function samples ()
    {
        return $this->hasMany(Sample::class);
    }


}
