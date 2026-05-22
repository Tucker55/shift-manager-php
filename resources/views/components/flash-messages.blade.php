@if (session('status') || session('error'))
    <div {{ $attributes->merge(['class' => 'space-y-3']) }}>
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800 font-medium">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-800 font-medium">
                {{ session('error') }}
            </div>
        @endif
    </div>
@endif
