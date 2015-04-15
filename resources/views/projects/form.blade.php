{!! Form::hidden('nextModel',$mid,['class' => 'form-control']) !!}
<div class="form-group">
    {!! Form::label('name','Name: ') !!}
    {!! Form::text('name',null,['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('slug','Internal Reference Name (no spaces, alpha-numeric values only): ') !!}
    {!! Form::text('slug',null,['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('description','Description: ') !!}
    {!! Form::textarea('description',null,['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('adminId','Project Admin: ') !!}
    {!! Form::select('adminId',$users=['koraadmin'], null,['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('active','Status: ') !!}
    {!! Form::select('active', ['1' => 'Active', '0' => 'Inactive'], '1',['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText,['class' => 'btn btn-primary form-control']) !!}
</div>