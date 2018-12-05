@extends('app', ['page_title' => $project->name, 'page_class' => 'project-show'])

@section('leftNavLinks')
    @include('partials.menu.project', ['pid' => $project->pid])
@stop

@section('aside-content')
  @include('partials.sideMenu.project', ['pid' => $project->pid, 'openDrawer' => true])
@stop

@section('stylesheets')
  <!-- No Additional Stylesheets Necessary -->
@stop

@section('header')
  <section class="head">
      <a class="back" href=""><i class="icon icon-chevron"></i></a>
      <div class="inner-wrap center">
        <h1 class="title">
          <i class="icon icon-project"></i>
          <a href="{{ action('ProjectController@edit',['pid' => $project->pid]) }}" class="head-button tooltip" tooltip="Edit Project">
            <i class="icon icon-edit right"></i>
          </a>
          <span>{{ $project->name }}</span>
        </h1>
        <p class="identifier">
          <span>Unique Project ID:</span>
          <span>{{ $project->slug }}</span>
        </p>
        <p class="description">{{ $project->description }}</p>
      </div>
  </section>
@stop

@section('body')
  @if($notification)
    @include('partials.projects.notification')
  @endif
  @php $pref = 'single_proj_page_tab_selection' @endphp
  @if (count($forms) > 0)
	  <section class="filters center">
		  <div class="underline-middle search search-js">
			<i class="icon icon-search"></i>
			<input type="text" placeholder="Find a Form">
			<i class="icon icon-cancel icon-cancel-js"></i>
		  </div>
		  <div class="sort-options sort-options-js">
			  <!-- <a href="modified" class="option underline-middle">Recently Modified</a> -->
			  <a href="#custom" class="option underline-middle underline-middle-hover {{ \App\Http\Controllers\Auth\UserController::returnUserPrefs($pref) == "2" ? 'active' : ''}}">Custom</a>
			  <a href="#active" class="option underline-middle underline-middle-hover {{ \App\Http\Controllers\Auth\UserController::returnUserPrefs($pref) == "3" ? 'active' : ''}}">Alphabetical</a>
		  </div>
	  </section>
  @endif

  <section class="new-object-button center">
    @if(\Auth::user()->canCreateForms($project))
      <form action="{{ action('FormController@create', ['pid' => $project->pid]) }}">
          <input type="submit" value="Create a New Form">
      </form>
    @endif
  </section>

  <section class="form-selection center form-js form-selection-js">
	@if (count($forms) > 0)  
	  @include("partials.projects.show.alphabetical", ['isCustom' => false, 'active' => \App\Http\Controllers\Auth\UserController::returnUserPrefs($pref) == "3" ? true : false])
	  @include("partials.projects.show.custom", ['isCustom' => true, 'active' => \App\Http\Controllers\Auth\UserController::returnUserPrefs($pref) == "2" ? true : false])
	@else
	  <div class="form-sort active-forms active form-active-js form-sort-js">
		@include('partials.projects.show.no-form')
	  </div>
	@endif
  </section>
@stop

@section('footer')

@stop

@section('javascripts')
  @include('partials.projects.javascripts')

  <script type="text/javascript">
    var CSRFToken = '{{ csrf_token() }}';
    var saveCustomOrderUrl = '{{ action('Auth\UserController@saveFormCustomOrder', ['pid' => $project->pid]) }}';

    Kora.Projects.Show();
  </script>
@stop
