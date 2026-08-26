<div class="bg-neutral-primary-soft rounded-base border-default shadow-xs mb-6 border p-6">
    @if (session()->has('error'))
        <div class="mb-4 rounded-lg bg-red-100 p-3 text-red-700">
            {{ session('error') }}
        </div>
    @endif
    <x-userTeacher-form />
</div>
