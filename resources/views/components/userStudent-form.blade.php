<form wire:submit.prevent="save">

<div class="grid gap-6 mb-6 md:grid-cols-2">

    <!-- NAME -->
    <div>
        <label class="block mb-2.5 text-sm font-medium text-heading">Full Name</label>
        <input type="text"
            wire:model="name"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
            placeholder="Yogi Sepdu" />

        @error('name')
        <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- EMAIL -->
    <div>
        <label class="block mb-2.5 text-sm font-medium text-heading">Email</label>
        <input type="email"
            wire:model="email"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
            placeholder="email@gmail.com" />

        @error('email')
        <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- PHONE -->
    <div>
        <label class="block mb-2.5 text-sm font-medium text-heading">Phone Number</label>
        <input type="text"
            wire:model="phone"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
            placeholder="08123456789" />

        @error('phone')
        <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- SCHOOL -->
    <div>
        <label class="block mb-2.5 text-sm font-medium text-heading">School</label>
        <input type="text"
            wire:model="school"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
            placeholder="SMA 1 Pekanbaru" />

        @error('school')
        <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- GRADE -->
    <div>
        <label class="block mb-2.5 text-sm font-medium text-heading">Grade</label>
        <input type="number"
            wire:model="grade"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
            placeholder="12" />

        @error('grade')
        <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- BIRTH DATE -->
    <div>
        <label class="block mb-2.5 text-sm font-medium text-heading">Birth Date</label>
        <input type="date"
            wire:model="birth_date"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" />

        @error('birth_date')
        <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

</div>


<!-- ADDRESS -->
<div class="mb-6">
    <label class="block mb-2.5 text-sm font-medium text-heading">Address</label>
    <input type="text"
        wire:model="address"
        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
        placeholder="Jl. Sudirman No. 10" />

    @error('address')
    <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>


<!-- PASSWORD -->
<div class="mb-6">
    <label class="block mb-2.5 text-sm font-medium text-heading">Password</label>
    <input type="password"
        wire:model="password"
        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
        placeholder="••••••••" />

    @error('password')
    <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>


<!-- CONFIRM PASSWORD -->
<div class="mb-6">
    <label class="block mb-2.5 text-sm font-medium text-heading">Confirm Password</label>
    <input type="password"
        wire:model="password_confirmation"
        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
        placeholder="••••••••" />
</div>


    <button type="submit"
    wire.loading.attr="disabled"
    class="text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium rounded-base text-sm px-4 py-2.5">

        <span wire:loading.remove wire.target="save">
            Register
        </span>

        <span wire.loading wire.target="save">
            Registering...
        </span>

    </button>

</form>