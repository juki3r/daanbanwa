<x-guest-layout>

<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md text-center">

        <!-- Logo -->
        <img src="{{ asset('images/logo.png') }}" class="mx-auto mb-4" width="70">

        <div class="text-xs tracking-widest text-gray-500 mb-1">
            BARANGAY PORTAL
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-1">
            Barangay Daan Banwa
        </h2>

        <p class="text-sm text-gray-500 mb-6">
            Secure Login Access
        </p>

        <x-auth-session-status class="mb-3 text-sm" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4 text-left">
            @csrf

            <!-- Phone -->
            <div>
                <x-input-label for="phone" :value="__('Phone')" />
                <x-text-input
                    id="phone"
                    class="block mt-1 w-full"
                    type="text"
                    name="phone"
                    :value="old('phone')"
                    required
                    autofocus
                />
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input
                    id="password"
                    class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required
                />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Remember -->
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="remember_me" class="ml-2 text-sm text-gray-600">
                    Remember me
                </label>
            </div>

            <!-- Button -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition">
                Log in
            </button>

        </form>

    </div>
</div>

</x-guest-layout>