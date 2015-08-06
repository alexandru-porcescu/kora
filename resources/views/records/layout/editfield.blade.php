@if($field->type == 'Text')
    @include('records.fieldInputs.text-edit', ['text' => \App\TextField::where('rid', '=', $record->rid)->where('flid', '=', $field->flid)->first()])
@elseif($field->type == 'Rich Text')
    @include('records.fieldInputs.richtext-edit', ['richtext' => \App\RichTextField::where('rid', '=', $record->rid)->where('flid', '=', $field->flid)->first()])
@elseif($field->type == 'Number')
    @include('records.fieldInputs.number-edit', ['number' => \App\NumberField::where('rid', '=', $record->rid)->where('flid', '=', $field->flid)->first()])
@elseif($field->type == 'List')
    @include('records.fieldInputs.list-edit', ['list' => \App\ListField::where('rid', '=', $record->rid)->where('flid', '=', $field->flid)->first()])
@elseif($field->type == 'Multi-Select List')
    @include('records.fieldInputs.mslist-edit', ['mslist' => \App\MultiSelectListField::where('rid', '=', $record->rid)->where('flid', '=', $field->flid)->first()])
@elseif($field->type == 'Generated List')
    @include('records.fieldInputs.genlist-edit', ['genlist' => \App\GeneratedListField::where('rid', '=', $record->rid)->where('flid', '=', $field->flid)->first()])
@elseif($field->type == 'Date')
    @include('records.fieldInputs.date-edit', ['date' => \App\DateField::where('rid', '=', $record->rid)->where('flid', '=', $field->flid)->first()])
@elseif($field->type == 'Schedule')
    @include('records.fieldInputs.schedule-edit', ['schedule' => \App\ScheduleField::where('rid', '=', $record->rid)->where('flid', '=', $field->flid)->first()])
@endif