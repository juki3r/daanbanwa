<x-guest-layout>

<div class="min-h-screen flex items-center justify-center bg-white">

    <form method="POST" action="{{ route('login') }}" class="w-full max-w-sm space-y-4">

        @csrf

        <div class="text-center mb-6">
            <i class="bi bi-shield-lock text-primary fs-2"></i>
            <div class="fw-semibold fs-4">Login</div>
        </div>

        <x-auth-session-status class="text-sm text-center" :status="session('status')" />

        <!-- Phone -->
        <input
            type="text"
            name="phone"
            value="{{ old('phone') }}"
            placeholder="Phone"
            required
            autofocus
            class="w-full border-0 border-bottom py-2 outline-none"
        />

        <x-input-error :messages="$errors->get('phone')" class="text-sm text-danger" />

        <!-- Password -->
        <input
            type="password"
            name="password"
            placeholder="Password"
            required
            class="w-full border-0 border-bottom py-2 outline-none"
        />

        <x-input-error :messages="$errors->get('password')" class="text-sm text-danger" />

        <!-- Submit -->
        <button type="submit"
            class="w-full py-2 mt-3 bg-primary text-white rounded">
            Log in
        </button>

    </form>

</div>

</x-guest-layout>