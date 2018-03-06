<div class="modal modal-js modal-mask already-record-preset-modal-js">
    <div class="content small">
        <div class="header">
            <span class="title">Already a Preset!</span>
            <a href="#" class="modal-toggle modal-toggle-js">
                <i class="icon icon-cancel"></i>
            </a>
        </div>
        <div class="body">
            <div class="form-group">
                This current version of this record is already designated as a preset. You may visit the
                <a class="record-preset-link" href="{{action('RecordPresetController@index', ['pid' => $form->pid, 'fid' => $form->fid])}}">
                    Record Preset Management
                </a>
                page if you would like to remove this record as a preset.
            </div>

            <div class="form-group">
                {!! Form::submit('Gotchya!',['class' => 'btn gotchya-js']) !!}
            </div>
        </div>
    </div>
</div>