@if (strtotime($datetime))
    <? $d = \Carbon\Carbon::parse($datetime) ?>
    <span data-toggle="tooltip" title="{{ \Carbon\Carbon::now()->diffInDays($d, false) }} days">{{ $d }}</span>
@else
    <span>{{ $datetime }}</span>
@endif
