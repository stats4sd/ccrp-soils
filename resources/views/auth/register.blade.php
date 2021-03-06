@extends('layouts.full_width')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header"><h1><b>{{ t("Create an Account") }}</b></h1></div>
                <div class="card-body">
                    @if($invite)
                        <div class="alert alert-info">

                            {{ t("You have been invited to join the :projectName project.", ['projectName' => $invite->project->name]) }}
                            <br/><br/>
                            {{ t("Your invite is attached to your email address. Please use this same address when registering to get immediate access to the project data and forms. You may change your email address after registration if you would prefer to use a different one.") }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('register') }}">
                            @csrf

                        <div class="form-group row required">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ t('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row required">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ t('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input
                                id="email"
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email') ?: ($invite ? $invite->email : null )}}"
                                required
                                autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row required">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ t('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row required">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ t('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <hr/>
                        <p class="form-text">
                            {{ t("In order to share ODK forms directly with you on Kobotoolbox, we need the username for your Kobotoolbox account.") }}
                        </p>
                        <p class="form-text">
                            {{ t("We do not require your Kobotoolbox password, and will never ask for direct access to your Kobo account.") }}
                        </p>
                        <div class="form-group row">
                            <label for="kobo_id" class="col-md-4 col-form-label text-md-right">{{ t("Kobotoolbox Username") }}</label>

                            <div class="col-md-6">
                                <input id="kobo_id" type="text" class="form-control @error('kobo_id') is-invalid @enderror" name="kobo_id">

                                @error('kobo_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <hr/>

                        <div class="form-group row mb-0 ">
                            <div class="col-md-10 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    {{ t('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

