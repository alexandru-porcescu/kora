{!! Form::hidden('advanced',true) !!}
<div class="form-group mt-xxxl">
    {!! Form::label('filesize','Max File Size (kb)') !!}
    <div class="number-input-container number-input-container-js">
        <input type="number" name="filesize" class="text-input" step="1" min="0" placeholder="Enter max file size (kb) here">
    </div>
</div>

<div class="form-group mt-xl">
    {!! Form::label('filetype','Allowed File Types (MIME)') !!}
    {!! Form::select('filetype'.'[]',['obj' => 'OBJ','stl' => 'STL','image/jpeg' => 'JPEG Texture',
        'image/png' => 'PNG Texture','application/octet-stream' => 'Other'],
        ['obj','stl','image/jpeg','image/png','application/octet-stream'],
        ['class' => 'multi-select', 'Multiple']) !!}
</div>

<div class="form-group mt-xl">
    {!! Form::label('color','Model Color') !!}
    <input type="color" name="color" class="text-input color-input" value="#ddd">
</div>

<div class="form-group mt-xl">
    {!! Form::label('backone','Background Color One') !!}
    <input type="color" name="backone" class="text-input color-input" value="#2E4F5E">
</div>

<div class="form-group mt-xl">
    {!! Form::label('backtwo','Background Color Two') !!}
    <input type="color" name="backtwo" class="text-input color-input" value="#152730">
</div>

<script>
    Kora.Fields.Options('Model');
</script>