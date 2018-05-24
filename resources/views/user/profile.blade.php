@extends('app', ['page_title' => 'My Profile', 'page_class' => 'user-profile'])

@section('aside-content')
  @include('partials.sideMenu.dashboard', ['openDashboardDrawer' => false, 'openProjectDrawer' => false])
@stop

@section('header')
    <section class="head">
        <div class="inner-wrap center">
            <h1 class="title">
                @if ($user->profile)
                  <img class="profile-pic" src="{{ $user->getProfilePicUrl() }}" alt="Profile Pic">
                @else
                  <i class="icon icon-user"></i>
                @endif
                <span class="ml-m">{{$user->first_name}} {{$user->last_name}}</span>
                @if(\Auth::user()->admin | \Auth::user()->id==$user->id)
                    <a href="{{ action('Auth\UserController@editProfile',['uid' => $user->id]) }}" class="head-button">
                        <i class="icon icon-edit right"></i>
                    </a>
                @endif
            </h1>
            <div class="content-sections">
                <a href="{{url('user', ['uid' => $user->id])}}" class="section select-section-js underline-middle underline-middle-hover {{($section == 'profile' ? 'active' : '')}}">Profile</a>
                <a href="{{url('user', ['uid' => $user->id, 'section' => 'permissions'])}}" class="section select-section-js underline-middle underline-middle-hover {{($section == 'permissions' ? 'active' : '')}}">Permissions</a>
                <a href="{{url('user', ['uid' => $user->id, 'section' => 'history'])}}" class="section select-section-js underline-middle underline-middle-hover {{($section == 'recordHistory' ? 'active' : '')}}">Record History</a>
            </div>
        </div>
    </section>
@stop

@section('body')
    <section class="center profile page-section page-section-js {{($section == 'profile' ? 'active' : '')}}" id="profile">
        <div class="attr mt-xl">
            <span class="title">First Name: </span>
            <span class="desc">{{$user->first_name}}</span>
        </div>

        <div class="attr mt-xl">
            <span class="title">Last Name: </span>
            <span class="desc">{{$user->last_name}}</span>
        </div>

        <div class="attr mt-xl">
            <span class="title">User Name: </span>
            <span class="desc">{{$user->username}}</span>
        </div>

        <div class="attr mt-xl">
            <span class="title">Email: </span>
            <span class="desc">{{$user->email}}</span>
        </div>

        <div class="attr mt-xl">
            <span class="title">Organization: </span>
            <span class="desc">{{$user->organization}}</span>
        </div>
    </section>
@stop


@section('javascripts')
    @include('partials.user.javascripts')

    <script type="text/javascript">
        Kora.User.Profile();
    </script>
@stop

