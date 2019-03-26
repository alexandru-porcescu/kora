@extends('fields.show')

@section('fieldOptions')
    <div class="form-group">
        {!! Form::label('default','Default') !!}
        <span class="error-message"></span>
        <div class="number-input-container">
            <input
                type="number"
                name="default"
                class="text-input number-default-js"
                value="{{ $field['default'] }}"
                placeholder="Enter number here"
            >
        </div>
    </div>

    @include('partials.fields.options.defaults.integer')
@stop

@section('fieldOptionsJS')
    Kora.Fields.Options('Number');
@stop
