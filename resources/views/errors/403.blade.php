<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>403 — Forbidden</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center
             bg-gradient-to-br from-slate-100 to-slate-200 p-4">

    <div class="max-w-md w-full bg-white/70 backdrop-blur-xl border border-white/60
                rounded-3xl shadow-[0_8px_32px_rgba(15,23,42,0.08)] p-8 text-center">

        <div class="w-20 h-20 mx-auto mb-4 rounded-3xl
                    bg-gradient-to-br from-rose-500 to-rose-400
                    flex items-center justify-center text-white
                    shadow-[0_8px_20px_rgba(244,63,94,0.35)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-slate-800 mb-2">403 Forbidden</h1>
        <p class="text-sm text-slate-500 mb-6">
            {{ $exception->getMessage() ?: 'Anda tidak memiliki akses ke halaman ini.' }}
        </p>

        <a href="/admin/login"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl
                  text-sm font-bold text-white
                  bg-gradient-to-r from-slate-800 to-slate-700
                  hover:from-slate-700 hover:to-slate-600
                  transition-all active:scale-[0.98]">
            Login Admin
        </a>
    </div>

</body>
</html>