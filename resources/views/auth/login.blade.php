<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 p-4">

    <div class="w-full max-w-md bg-white/70 backdrop-blur-xl
                border border-white/60 rounded-3xl
                shadow-[0_8px_32px_rgba(15,23,42,0.08)] p-8">

        <div class="text-center mb-8">
            <img src="{{ asset('images/atl new logo.png') }}"
                 alt="Logo" class="h-40 mx-auto mb-4 object-contain">
        </div>

        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-2xl bg-rose-50 border border-rose-200 text-sm text-rose-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="  /admin/login" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-2.5 rounded-2xl border border-slate-200
                              bg-white/80 focus:outline-none focus:ring-2
                              focus:ring-slate-400/50 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2.5 rounded-2xl border border-slate-200
                              bg-white/80 focus:outline-none focus:ring-2
                              focus:ring-slate-400/50 text-sm">
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember"> Ingat saya
            </label>

            <button type="submit"
                    class="w-full py-2.5 rounded-2xl text-sm font-semibold text-white
                           bg-gradient-to-r from-slate-800 to-slate-700
                           hover:from-slate-700 hover:to-slate-600
                           transition-all active:scale-[0.98]">
                Login
            </button>
        </form>
    </div>

</body>
</html>