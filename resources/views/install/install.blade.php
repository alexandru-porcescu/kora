@extends('app',['page_title' => 'Kora Installation', 'page_class' => 'install'])

@section('header')
    <section class="head">
        {{--NO BACK BUTTON HERE--}}
        <div class="inner-wrap center">
            <h1 class="title no-icon">
                <span>Kora 3 Initialization Form</span>
            </h1>
            <p class="description">Fill out the following form to fully initialize Kora 3</p>
        </div>
    </section>
@stop

@section('body')
    <section class="install-form center">
        <form method="post" id="install_form" enctype="multipart/form-data" action={{action("InstallController@installFromWeb")}}>
            @include('partials.install.form')
        </form>
    </section>
@stop

@section('javascripts')
    @include('partials.install.javascripts')

    <script type="text/javascript">
        Kora.Install.Create();
    </script>
@stop

