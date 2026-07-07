<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden</title>

    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-slate-100 flex items-center justify-center px-4">

    <div class="w-full max-w-md rounded-3xl bg-white shadow-xl p-8 text-center">

        <!-- Icon -->
        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-red-100">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-10 w-10 text-red-600"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 9v3m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
            </svg>
        </div>
   
        <!-- Title -->
        <h2 class="mt-3 text-xl sm:text-2xl font-semibold text-slate-700">
           403 Access Denied
        </h2>

        <!-- Message -->
        <p class="mt-4 mb-4 text-sm sm:text-base text-slate-500 leading-6">
            You don't have permission to access this page.
            Please contact your administrator
        </p>

        <!-- Buttons -->
        <div class="mt-8 space-y-3">

            <button
                onclick="window.history.back()"
                class="w-full rounded-xl bg-blue-600 py-3 text-white font-semibold shadow hover:bg-blue-700 active:scale-95 transition">
                ← Go Back
            </button>

        </div>

        <!-- Footer -->
        <p class="mt-6 text-xs text-slate-400">
            Error 403 • Forbidden
        </p>

    </div>

</body>
</html>