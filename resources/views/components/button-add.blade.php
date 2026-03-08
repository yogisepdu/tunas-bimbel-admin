<div class="mb-6 flex justify-end">
    <a 
        href="{{ $route }}"
        {{ $attributes->merge([
            'wire:navigate' => true,
            'class' => 'relative inline-flex items-center justify-center p-0.5 overflow-hidden text-sm font-medium text-heading rounded-base group bg-gradient-to-br from-teal-300 to-lime-300 hover:from-teal-300 hover:to-lime-300 focus:ring-4 focus:outline-none focus:ring-lime-200'
        ]) }}
    >
        <span class="relative px-4 py-2.5 transition-all ease-in duration-75 bg-neutral-primary-soft rounded-base group-hover:bg-transparent leading-5">
            {{ $slot->isEmpty() ? 'Tambah Data' : $slot }}
        </span>
    </a>
</div>