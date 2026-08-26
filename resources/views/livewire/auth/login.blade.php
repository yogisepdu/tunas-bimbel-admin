<div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700">

    <div class="w-full max-w-md">
        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-400 bg-red-100 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <!-- Card -->
        <form class="rounded-2xl border border-white/20 bg-black/50 p-8 shadow-2xl backdrop-blur-md"
            wire:submit.prevent="login">

            <!-- Title -->
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-white">
                    Tunas Bimbel Admin Login
                </h2>
                <p class="text-sm text-white">
                    Silakan masuk ke dashboard
                </p>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <flux:field>
                    <flux:label>Email</flux:label>

                    <flux:input placeholder="admin@email.com" type="email" wire:model.defer="email" />

                    <flux:error name="email" />
                </flux:field>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <flux:field>
                    <flux:label>Password</flux:label>

                    <flux:input placeholder="••••••••" type="password" wire:model.defer="password" />

                    <flux:error name="password" />
                </flux:field>
            </div>

            <!-- Button -->
            <button
                class="w-full rounded-lg bg-blue-600 py-2.5 font-semibold text-white shadow-md transition duration-200 hover:bg-blue-700 hover:shadow-lg"
                type="submit" wire:loading.attr="disabled">

                <span wire:loading.remove>Login</span>
                <span wire:loading>Loading...</span>

            </button>

            <!-- Footer -->
            <p class="mt-6 text-center text-xs text-gray-500">
                © {{ date('Y') }} Admin Panel Tunas Bimbel. All rights reserved.
            </p>

        </form>

    </div>

</div>
