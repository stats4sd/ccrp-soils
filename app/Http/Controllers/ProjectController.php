<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Invite;
use App\Models\Project;
use App\Models\Xlsform;
use App\Mail\InviteMember;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProjectMember;
use App\Models\ProjectXlsform;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Controllers\createProjectMember;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ProjectController extends Controller
{
    /**
     * shows the projects in 'All Projects' page that are not deleted and not hidden status.
     * @return [Collection] [projects]
     */
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }


    /**
     *
     * @param  Project $project [description]
     * @return [view]           Project account
     */
    public function show(Project $project, $tab = null)
    {
        $this->authorize('view', $project);
        $project = $project->load(
            [
                'users' => function ($q) {
                    $q->orderBy('pivot_admin', 'desc');
                },
                # 'project_xlsforms.xlsform',
                'samples',
            ]
        );

        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(ProjectStoreRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['creator_id'] = auth()->user()->id;
        $validatedData['slug'] = Str::slug($validatedData['name']);

        $project = Project::create($validatedData);

        return redirect()->route('projects.show', [$project]);
    }

    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $this->authorize('update', $project);
        $validatedData = $request->validated();

        $project->update($validatedData);

        return redirect()->route('projects.show', [$project]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();

        return redirect()->route('projects.index');
    }
}
