@extends('layouts.full_width')

@section('content')

@include('projects.header')

<!-- Tab links -->

<nav class="mt-5">
    <ul class="nav nav-tabs mr-auto" id="project-tabs" role="tablist">
        <li class="nav-item">
            <a href="#forms" class="nav-link active" id="forms-tab" data-toggle="tab" role="tab" aria-controls="forms" aria-selected="true">{{ t("Data Collection Forms") }}</a>
        </li>
        <li class="nav-item">
            <a href="#data" class="nav-link" id="data-tab" data-toggle="tab" role="tab" aria-controls="data" aria-selected="true">{{ t("Project Data") }}</a>
        </li>
        <li class="nav-item">
            <a href="#nutrients" class="nav-link" id="nutrients-tab" data-toggle="tab" role="tab" aria-controls="nutrients" aria-selected="true">{{ t("Project Nutrients Data") }}</a>
        </li>
        <li class="nav-item">
            <a href="#members" class="nav-link" id="members-tab" data-toggle="tab" role="tab" aria-controls="members" aria-selected="true">{{ t("Project Members") }}</a>
        </li>
        <li class="nav-item">
            <a href="#settings" class="nav-link" id="settings-tab" data-toggle="tab" role="tab" aria-controls="settings" aria-selected="true">{{ t("Project Settings") }}</a>
        </li>
    </ul>
</nav>

<div id="vue-app">
    <div class="tab-content" id="project-tab-content">
        <div class="tab-pane fade show active" id="forms" role="tabpanel" aria-labelledby="forms-tab">
            @if(!auth()->user()->kobo_id)
                <div class="alert alert-info text-dark">
                    {{ t("Note - you have not entered your KoboToolbox Username, which means you will not be able to see these formson KoboToolbox or ODK Collect. You can update your account here:") }} <a href="{{ route('users.edit', auth()->user()) }}">{{ t("My Account") }}</a>.<br/><br/>
                    {{ t("You still have access to all the data collected with these forms.") }}
                </div>
            @endif
                <!-- to pass project slug as a string to Vue component, wrap string value with single quote -->
                <project-forms-table
                :project-slug="'{{ $project->slug }}'"
                :user-id="{{ auth()->user()->id }}"
                >
                </project-forms-table>
        </div>
        <div class="tab-pane fade" id="data" role="tabpanel" aria-labelledby="data-tab">
            <h4>Downloads</h4>
            <div class="alert alert-info">
                Two download options are available. Choose "wide" format to receive:
                <ul>
                    <li>A single worksheet containing 1 row per sample</li>
                    <li>Only the first analysis record entered for a specific sample</li>
                </ul>
                Choose "split" format to revceive:
                <ul>
                    <li>One worksheet for sample data</li>
                    <li>One worksheet for each type of analysis</li>
                    <li>Each analysis record is linked to a sample via the sample_id column</li>
                    <li>If there is more than 1 anlysis record for a specific sample, all the records will be shown.</li>
                </ul>
            </div>
            <a href="{{ route('projects.samples.download-wide', $project) }}" class="btn btn-success">{{ t("Download sample data in wide format") }}</a>
            <a href="{{ route('projects.samples.download-long', $project) }}" class="btn btn-success">{{ t("Download sample data in split format") }}</a>
            <project-data-table
                :project-identifiers="{{ json_encode($project->identifiers) }}"
                :user-id="{{ auth()->user()->id }}"
                :samples="{{ $project->samples->toJson() }}"
            ></project-data-table>
        </div>
        <div class="tab-pane fade wide-table" id="nutrients" role="tabpanel" aria-labelledby="nutrients-tab">
            <a href="{{ route('projects.nutrients.download', $project) }}" class="btn btn-success">{{ t("Download nutrients data") }}</a>
            <p></p>
            <p>Note: Press [Shift] + mouse wheel to scroll horizontally</p>
            <project-nutrients-table
                :project-id="{{ $project->id }}"
                :user-id="{{ auth()->user()->id }}">
            </project-nutrients-table>
        </div>
        <div class="tab-pane fade" id="members" role="tabpanel" aria-labelledby="members-tab">
            @include('projects.tab-members')
        </div>
        <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
            @include('projects.tab-settings');
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/projectforms.js') }}"></script>
<script>
    $(document).ready(() => {

        let url = location.href.replace(/\/$/, "");

        if (location.hash) {
            const hash = url.split("#");
            $('#project-tabs a[href="#' + hash[1] + '"]').tab("show");
            url = location.href.replace(/\/#/, "#");
            history.replaceState(null, null, url);
            setTimeout(() => {
                $(window).scrollTop(0);
            }, 400);
        }

        $('a[data-toggle="tab"]').on("click", function() {
            let newUrl;
            const hash = $(this).attr("href");
            if (hash == "#forms") {
                newUrl = url.split("#")[0];
            } else {
                newUrl = url.split("#")[0] + hash;
            }
            newUrl += "/";
            history.replaceState(null, null, newUrl);
        });
    });
</script>
@endpush
