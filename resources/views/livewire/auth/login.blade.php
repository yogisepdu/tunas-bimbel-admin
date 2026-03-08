<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700">

    <div class="w-full max-w-md">

        <!-- Card -->
        <form wire:submit.prevent="login"
            class="bg-black/50 backdrop-blur-md shadow-2xl rounded-2xl p-8 border border-white/20">

            <!-- Title -->
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-white">
                    Tunas Bimbel Admin Login
                </h2>
                <p class="text-white text-sm">
                    Silakan masuk ke dashboard
                </p>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <flux:field>
                    <flux:label>Email</flux:label>

                    <flux:input
                        type="email"
                        wire:model.defer="email"
                        placeholder="admin@email.com"
                    />

                    <flux:error name="email" />
                </flux:field>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <flux:field>
                    <flux:label>Password</flux:label>

                    <flux:input
                        type="password"
                        wire:model.defer="password"
                        placeholder="••••••••"
                    />

                    <flux:error name="password" />
                </flux:field>
            </div>

            <!-- Button -->
            <button type="submit"
                wire:loading.attr="disabled"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white
                font-semibold py-2.5 rounded-lg
                transition duration-200 shadow-md hover:shadow-lg">

                <span wire:loading.remove>Login</span>
                <span wire:loading>Loading...</span>

            </button>

            <!-- Footer -->
            <p class="text-center text-xs text-gray-500 mt-6">
                © {{ date('Y') }} Admin Panel Tunas Bimbel. All rights reserved.
            </p>

        </form>

    </div>

</div>