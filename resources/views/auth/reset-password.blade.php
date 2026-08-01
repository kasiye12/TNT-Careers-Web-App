<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - TNT Construction</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body{font-family:'Plus Jakarta Sans',sans-serif;background:linear-gradient(135deg,#0c4a6e 0%,#075985 40%,#0284c7 100%);min-height:100vh}</style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white/98 backdrop-blur-sm rounded-3xl shadow-2xl p-8 border border-white/30">
            <div class="text-center mb-6"><div class="w-14 h-14 bg-gradient-to-br from-[#0284c7] to-[#0ea5e9] rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg"><i class="fas fa-lock text-white text-xl"></i></div><h1 class="text-2xl font-extrabold text-[#0c4a6e]">Reset Password</h1></div>
            @if($errors->any())<div class="bg-red-50 text-red-600 p-3 rounded-xl mb-4 text-sm">{{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <input type="hidden" name="email" value="{{ old('email', $request->email) }}">
                <div class="relative"><i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="password" name="password" required class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-2xl text-sm focus:border-[#0284c7]" placeholder="New password"></div>
                <div class="relative"><i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="password" name="password_confirmation" required class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-2xl text-sm focus:border-[#0284c7]" placeholder="Confirm password"></div>
                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-[#0284c7] to-[#0ea5e9] text-white font-bold rounded-2xl hover:from-[#0369a1] hover:to-[#0284c7] transition-all shadow-lg"><i class="fas fa-check mr-2"></i> Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
