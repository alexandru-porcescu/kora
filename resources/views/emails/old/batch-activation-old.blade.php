@extends('email')

@section('main-text')
    <span class="green">{{ $sender->getFullName() }}</span>
    has invited you to join them on 
    @if (isset($project))
        the following kora Project: <br>
        <span class="green project-title">{{ $project->name }}</span>
    @else
        kora!
    @endif
@endsection

@if (!empty($personal_message))
    @section('sub-text')
    <div class="sub-text">
        "{{ $personal_message }}"
    </div>
    @endsection
@endif

@section('button-link')
    {{ action('Auth\UserController@activateFromInvite', ['token' => $token]) }}
@endsection

@section('button-text')
    Accept Invite
@endsection

@if (isset($project))
    @section('post-action-text')
        Once you accept, you'll be added to the {{ $projectGroup->name }} permissions group.  This means you'll be able to:
        <ul>
            <li>View the Project</li>

            @if ($projectGroup->create == '1')
                <li>Create Forms</li>
            @endif

            @if ($projectGroup->edit == '1')
                <li>Edit Forms</li>
            @endif

            @if ($projectGroup->delete == '1')
                <li>Delete Forms</li>
            @endif
        </ul>
    @endsection
@endif

@section('pre-footer-text')
    <span class="green">kora</span> is an open-source, database-driven, online digital repository application for complex digital objects. kora allows you to store, manage, and publish digital objects, each with corresponding metadata into a single record, enhancing the research and educational value of each.
@endsection

@section('footer-text')
    You have been invited by {{ $sender->getFullName() }}
@endsection

@section('footer-email')
    ({{ $sender->username }}, <span class="green-nolink"><a href="#" class="green-nolink">{{ $sender->email }}</a></span>)
@endsection
