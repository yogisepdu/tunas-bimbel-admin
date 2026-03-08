<form wire:submit.prevent="save">

<div class="grid gap-6 mb-6 md:grid-cols-2">

    <!-- NAME -->
    <div>
        <label class="block mb-2.5 text-sm font-medium text-heading">Full Name</label>
        <input type="text"
            wire:model.defer="name"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            placeholder="John Doe" />

        @error('name')
        <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- EMAIL -->
    <div>
        <label class="block mb-2.5 text-sm font-medium text-heading">Email</label>
        <input type="email"
            wire:model.defer="email"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            placeholder="teacher@email.com" />

        @error('email')
        <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- PHONE -->
    <div>
        <label class="block mb-2.5 text-sm font-medium text-heading">Phone</label>
        <input type="text"
            wire:model.defer="phone"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            placeholder="08123456789" />

        @error('phone')
        <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- COMPANY -->
    <div>
        <label class="block mb-2.5 text-sm font-medium text-heading">Company / Institution</label>
        <input type="text"
            wire:model.defer="company"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            placeholder="Universitas / Sekolah / Freelance" />

        @error('company')
        <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- SPECIALIZATION -->
    <div>
        <label class="block mb-2.5 text-sm font-medium text-heading">Specialization</label>
        <input type="text"
            wire:model.defer="specialization"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            placeholder="Mathematics, Physics, English" />

        @error('specialization')
        <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- EXPERIENCE -->
    <div>
        <label class="block mb-2.5 text-sm font-medium text-heading">Experience (Years)</label>
        <input type="number"
            wire:model.defer="experience_years"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            placeholder="5" />

        @error('experience_years')
        <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

</div>


<!-- BIO -->
<div class="mb-6">
    <label class="block mb-2.5 text-sm font-medium text-heading">Bio</label>
    <textarea
        wire:model.defer="bio"
        rows="4"
        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
        placeholder="Short biography about teacher"></textarea>

    @error('bio')
    <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>


<!-- PASSWORD -->
<div class="mb-6">
    <label class="block mb-2.5 text-sm font-medium text-heading">Password</label>
    <input type="password"
        wire:model.defer="password"
        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
        placeholder="••••••••" />

    @error('password')
    <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>


<!-- CONFIRM PASSWORD -->
<div class="mb-6">
    <label class="block mb-2.5 text-sm font-medium text-heading">Confirm Password</label>
    <input type="password"
        wire:model.defer="password_confirmation"
        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
        placeholder="••••••••" />
</div>


    <button type="submit"
    wire:loading.attr="disabled"
    class="text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium rounded-base text-sm px-4 py-2.5">

        <span wire:loading.remove wire:target="save">
        Register Teacher
        </span>

        <span wire:loading wire:target="save">
        Saving...
        </span>

    </button>

</form>