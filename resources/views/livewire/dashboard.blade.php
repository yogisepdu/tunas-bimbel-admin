<div>
    @if ($isAdministrator)
        @include('livewire.dashboard.partials.administrator')
    @else
        @include('livewire.dashboard.partials.learning')
    @endif
</div>
