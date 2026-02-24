<x-guest-layout>
<div class="min-h-screen bg-[#001529] flex items-center justify-center p-4 relative overflow-hidden">
    <div class="w-full max-w-md bg-white/5 backdrop-blur-xl p-8 sm:p-10 rounded-[3rem] border border-white/10 shadow-2xl relative z-10">

        <div class="text-center mb-10">
            <img alt="WC 2026" class="w-20 mx-auto mb-6 invert brightness-200"
                src="https://paladarnegro.net/escudoteca/copas/copamundial/png/mundial_2026.png">
            <h1 class="text-5xl marker-font text-yellow-400 mb-2">WOOMBI</h1>
            <p class="text-white font-black uppercase tracking-[0.4em] text-[10px] opacity-70">Prode Mundial 2026</p>
        </div>

        <div class="mb-8 text-center">
            <p class="text-white/50 text-xs font-bold uppercase tracking-widest leading-relaxed">
                Elegí tu nueva contraseña
            </p>
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <input
                type="email"
                name="email"
                required
                value="{{ old('email', $request->email) }}"
                placeholder="EMAIL"
                class="w-full bg-white/10 border border-white/20 rounded-xl px-5 py-4 text-white font-bold placeholder:text-white/40 outline-none text-sm"
            >

            <input
                type="password"
                name="password"
                required
                placeholder="NUEVA CONTRASEÑA"
                class="w-full bg-white/10 border border-white/20 rounded-xl px-5 py-4 text-white font-bold placeholder:text-white/40 outline-none text-sm"
            >

            <input
                type="password"
                name="password_confirmation"
                required
                placeholder="CONFIRMAR CONTRASEÑA"
                class="w-full bg-white/10 border border-white/20 rounded-xl px-5 py-4 text-white font-bold placeholder:text-white/40 outline-none text-sm"
            >

            <button type="submit"
                class="w-full font-black py-4 rounded-xl uppercase text-xs shadow-xl transition-all active:scale-95 bg-[#002868] text-white hover:bg-[#001529] border border-white/10">
                Actualizar contraseña
            </button>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}"
                    class="text-white/40 text-[9px] font-black uppercase tracking-widest hover:text-white transition-colors underline underline-offset-4">
                    Volver al inicio
                </a>
            </div>
        </form>

    </div>
</div>
</x-guest-layout>