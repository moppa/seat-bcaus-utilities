@if (strtotime($datetime))
    <? $d = \Carbon\Carbon::parse($datetime) ?>
    <span data-toggle="tooltip" title="{{ $d->diffInDays(\Carbon\Carbon::now()) }} days">{{ $d }}</span>
@else
    <span>{{ $datetime }}</span>
@endif
