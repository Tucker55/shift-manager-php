<x-guest-layout>
    <h2 class="text-2xl font-bold mb-1" style="color: var(--coffee-950)">Staff login</h2>
    <p class="text-sm mb-8 opacity-60">Bean & Brew shift rota</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="rounded-xl p-4 mb-6 text-sm" style="background: #f0e8dc; color: #4a2c1a">
        <p class="font-semibold mb-2">Demo accounts · password: <code class="bg-white/80 px-1.5 py-0.5 rounded text-xs">password</code></p>
        <p>alex@beanbrew.coffee · chris@beanbrew.coffee · taylor@beanbrew.coffee</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div>
            <x-input-label for="email" value="Email" class="font-semibold" style="color: #4a2c1a" />
            <x-text-input id="email" class="block mt-2 w-full rounded-xl border-stone-200 shadow-sm" type="email" name="email" :value="old('email', 'alex@beanbrew.coffee')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password" value="Password" class="font-semibold" style="color: #4a2c1a" />
            <x-text-input id="password" class="block mt-2 w-full rounded-xl border-stone-200 shadow-sm" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <label class="flex items-center gap-2 text-sm opacity-70">
            <input type="checkbox" name="remember" class="rounded border-stone-300 text-amber-800 focus:ring-amber-600">
            Remember me
        </label>
        <button type="submit" class="btn-primary w-full py-3 text-base">Log in</button>
    </form>
</x-guest-layout>
