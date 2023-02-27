<div class="container p-4">
    @can('update', $project)
        <form method="post" action="{{ route('projects.update', $project) }}" id="group_details" enctype="multipart/form-data">
            @csrf
            @method('put')
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <h4>{{ t("Edit Project Details") }}</h4>
            <div class="form-group row required">
                <label for="name" class="col-md-4 col-form-label text-md-right">
                    {{ t("Project Name") }}
                </label>
                <div class="col-md-8">
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $project->name ?: old('name') }}" required>

                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row required">
                <label for="description" class="col-md-4 col-form-label text-md-right">
                    {{ t("Brief Description") }}
                </label>
                <div class="col-md-8">
                    <textarea id="description" rows=5 name="description" class="form-control @error('description') is-invalid @enderror" required>{{ $project->description ?: old('description') }}</textarea>

                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="avatar" class="col-md-4 col-form-label text-md-right">
                    {{ t("Replace the project image") }}
                </label>
                <div class="col-md-6">
                    <input type="file" id="avatar-input" class="form-control-file @error('avatar') is-invalid @enderror" name="avatar" onchange="preview_image(event)">

                    @error('avatar')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-4 col-form-label text-md-right"></label>
                <div class="col-md-3">
                    <figure class="img_group_default">
                        <img src="{{ url('images/mystery-group.png') }}" id="avatar">
                        <figcaption class="figure-caption" id="avatar-caption"></figcaption>
                    </figure>
                </div>
            </div>

            <div class="form-group row">
                <h5 class="col-md-4 col-form-label text-md-right">
                    {{ t("Do you need Olsen P Analysis Outputs split between LR and HR?") }}
                </h5>
                <div class="form-check mb-3">
                    <input
                        class="form-check-input @error('highR') is-invalid @enderror"
                        type="radio"
                        name="highR"
                        value="1"
                        id="highR1"
                        @if($project->highR)checked @endif>

                    <label class="form-check-label" for="highR1">
                        {{ t("Yes") }}
                    </label>

                </div>

                <div class="form-check mb-3">
                    <input
                        class="form-check-input @error('highR') is-invalid @enderror"
                        type="radio"
                        name="highR"
                        value="0"
                        id="highR0"
                        @if(!$project->highR)checked @endif>

                    <label class="form-check-label" for="highR0">
                        {{ t("No") }}
                    </label>

                </div>
            </div>
            <div class="form-group row">
                <h5 class="col-md-4 col-form-label text-md-right">
                    {{ t("Do you need additional Olsen P Analysis Output columns for Custom Reagent packs?") }}
                </h5>
                <div class="form-check mb-3">
                    <input
                        class="form-check-input @error('customR') is-invalid @enderror"
                        type="radio"
                        name="customR"
                        value="1"
                        id="customR1"
                        @if($project->customR)checked @endif>
                    <label class="form-check-label" for="customR1">
                        {{ t("Yes") }}
                    </label>

                </div>

                <div class="form-check mb-3">
                    <input
                        class="form-check-input @error('customR') is-invalid @enderror"
                        type="radio"
                        name="customR"
                        value="0"
                        id="customR0"
                        @if(!$project->customR)checked @endif>
                    <label class="form-check-label" for="customR0">
                        {{ t("No") }}
                    </label>

                </div>
            </div>

            <hr />
            <h3>{{ t("Project Privacy Settings") }}</h3>
            <p>
                {{ t("As part of this platform, we would like to be able to use aggregated summaries to support broader research into soil quality in smallholder farms across the CCRP network and beyond. If you would like your project's data to contribute to this aggregation, please tick the box below.") }}
            </p>
            <p>
                {{ t("The data will be suitably anonymised before sharing. Any instances where individual data points might be recognisable will be discussed with the project before being shared publicly.") }}
            </p>

            <div class="form-group row">
                <h5 class="col-md-4 col-form-label text-md-right">
                    Data Sharing
                </h5>
                <div class="col-md-8">
                    <div class="form-check mb-3">
                        <input class="form-check-input @error('share_data') is-invalid @enderror" type="radio" name="share_data" value="1" id="share_data_1" @if($project->share_data)checked @endif>
                        <label class="form-check-label" for="share_data_1">
                            {{ t("I agree to have this project's data to be included in anonymous summaries produced by the CCRP Soils Cross-cutting Project.") }}
                        </label>

                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input @error('share_data') is-invalid @enderror" type="radio" name="share_data" value="0" id="share_summary_0" @if(!$project->share_data)checked @endif>
                        <label class="form-check-label" for="share_summary_0">
                            {{ t("Do not include this project's data in summaries produced by the platform for use outside of this project's team.") }}
                        </label>

                        @error('share_summary')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                </div>
            </div>
            <div class="form-group row mb-0">
                <div class="col-md-10 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Submit') }}
                    </button>
                </div>
            </div>
        </form>
    @endcan
    @cannot('update', $project)
        {{ t("You do not have access to update the project settings. Please contact a project administrator") }}
    @endcannot
    <hr />

</div>

@push('scripts')

<script type='text/javascript'>
    function preview_image(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('avatar');
            var outputLabel = document.getElementById('avatar-caption');
            output.src = reader.result;
            outputLabel.innerHTML = "Preview of image to upload";
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endpush
