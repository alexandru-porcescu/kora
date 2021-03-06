<!doctype html>

<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">
    <title>kora - Install Success</title>

    <link rel="stylesheet" href="{{url('assets/css/app.css')}}">
</head>
<body class="install-success-body">

<div class="install-success">
    <section class="last-steps">
        <div class="last-desc">
            <img class="logo mt-xxxl" src="{{ url('assets/logos/logo_green_text_dark.svg') }}">
            <div class="sub-title mt-xxxl">Initialization Complete</div>
            <div class="description mt-m">
                kora has finished initialization. Please review the following:
            </div>
        </div>

        <div class="last-cmd mt-xxxl">
            <div class="form-group">
                <label for="preset">Give READ access to the web user for your kora directory and ALL sub-folders</label>
            </div>

            <div class="form-group mt-xl">
                <label for="preset">Give WRITE access to the web user for the following directories and ALL their sub-folders</label>
                <div class="solid-box">kora/bootstrap/cache/</div>
            </div>
            <div class="form-group mt-xs">
                <div class="solid-box">kora/storage/</div>
            </div>
            <div class="form-group mt-xs">
                <div class="solid-box">kora/public/assets/javascripts/production/</div>
            </div>

            <div class="form-group mt-xl">
                @php
                    $pw = !is_null(app('request')->input('pw')) ? app('request')->input('pw') : '********************';
                @endphp
                <label for="preset">Your password for user `admin` is</label>
                <div class="solid-box">{{ $pw }}</div>
            </div>

            <div class="form-group mt-xxxl">
                <a href="{{url('')}}" class="btn">Start Using kora</a>
            </div>
        </div>
    </section>
</div>

@include('partials.install.javascripts')

@if(Auth::guest() || !Auth::user()->active)
    @include('partials.auth.javascripts')

    <script>
        var langURL ="{{action('WelcomeController@setTemporaryLanguage')}}";

        function setTempLang(selected_lang){
            console.log("Language change started: "+langURL);
            $.ajax({
                url:langURL,
                method:'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "templanguage": selected_lang
                },
                success: function(data){
                    console.log(data);
                    location.reload();
                }
            });
        }

        Kora.Auth.Auth();
    </script>
@endif
</body>
</html>
