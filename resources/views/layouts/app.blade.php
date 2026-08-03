<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <meta name="theme-color" content="#0a7aa8">
    <meta name="description" content="{{ \App\Helpers\SettingsHelper::companyDescription() }}">
    <title>@yield('title', \App\Helpers\SettingsHelper::companyName()) · Careers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        body { background: #f8fafc; }
        .hero-gradient { background: linear-gradient(145deg, #0b3b5a 0%, #0a4b6e 40%, #0a5f89 80%, #0a7aa8 100%); }
        .btn-solid-sky { background: #0a7aa8; color: white; transition: all 0.15s ease; }
        .btn-solid-sky:hover { background: #0b5f85; }
        .nav-link-active { background: #e3f0f9; color: #0a5f89; font-weight: 600; }
        .footer-link { transition: color 0.2s; }
        .footer-link:hover { color: #7fc9f0; }
        .search-input { background: white; border: 1px solid #e2e8f0; transition: all 0.15s; }
        .search-input:focus { border-color: #0a7aa8; box-shadow: 0 0 0 3px rgba(10, 122, 168, 0.15); outline: none; }
    </style>
    @stack('styles')
</head>
<body class="antialiased text-gray-700">

    <!-- TOP BAR -->
    <div class="hidden lg:block bg-[#0b3b5a] text-gray-200 text-xs py-2.5 border-b border-[#1a5270]">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <span><i class="fas fa-phone-alt mr-2 text-[#65b7e0]"></i> {{ \App\Helpers\SettingsHelper::contactPhone() }}</span>
                <span><i class="fas fa-envelope mr-2 text-[#65b7e0]"></i> {{ \App\Helpers\SettingsHelper::contactEmail() }}</span>
                <span><i class="fas fa-map-pin mr-2 text-[#65b7e0]"></i> {{ \App\Helpers\SettingsHelper::contactAddress() }}</span>
            </div>
            <div class="flex gap-4 text-gray-300">
                @php $social = \App\Helpers\SettingsHelper::socialLinks(); @endphp
                @if(!empty($social['facebook']))
                    <a href="{{ $social['facebook'] }}" target="_blank" class="hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                @endif
                @if(!empty($social['linkedin']))
                    <a href="{{ $social['linkedin'] }}" target="_blank" class="hover:text-white transition"><i class="fab fa-linkedin-in"></i></a>
                @endif
                @if(!empty($social['twitter']))
                    <a href="{{ $social['twitter'] }}" target="_blank" class="hover:text-white transition"><i class="fab fa-x-twitter"></i></a>
                @endif
                @if(!empty($social['instagram']))
                    <a href="{{ $social['instagram'] }}" target="_blank" class="hover:text-white transition"><i class="fab fa-instagram"></i></a>
                @endif
            </div>
        </div>
    </div>

    <!-- NAVIGATION -->
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-lg border-b border-gray-200/60 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16 lg:h-20">
            <a href="/" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-gradient-to-br from-[#0a7aa8] to-[#0b4b6e] rounded-xl flex items-center justify-center shadow-md shadow-sky-400/20">
                    <i class="fas fa-hard-hat text-white text-lg"></i>
                </div>
                <div class="leading-tight">
                    <span class="text-lg font-extrabold text-[#0b3b5a] tracking-tight">{{ \App\Helpers\SettingsHelper::companyShortName() }}</span>
                    <span class="block -mt-0.5 text-[10px] font-medium text-gray-400 tracking-widest">CONSTRUCTION PLC</span>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-1">
                <a href="/" class="px-4 py-2 rounded-xl text-sm font-medium transition {{ request()->is('/') ? 'nav-link-active' : 'text-gray-500 hover:bg-gray-50 hover:text-[#0b4b6e]' }}">Home</a>
                <a href="{{ route('vacancies.public.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('vacancies.public.*') ? 'nav-link-active' : 'text-gray-500 hover:bg-gray-50 hover:text-[#0b4b6e]' }}">Jobs</a>
                <a href="{{ route('resume.builder') }}" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-500 hover:bg-gray-50 hover:text-[#0b4b6e] transition">Resume</a>
                <a href="{{ route('cv.generator') }}" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-500 hover:bg-gray-50 hover:text-[#0b4b6e] transition">CV Builder</a>
                <a href="{{ route('salary.calculator') }}" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-500 hover:bg-gray-50 hover:text-[#0b4b6e] transition">Payroll</a>
                @auth
                    @if(Auth::user()->user_type === 'evaluator')
                        <a href="{{ route('evaluator.dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-500 hover:bg-gray-50 hover:text-[#0b4b6e] transition">Evaluate</a>
                    @endif
                    @if(in_array(Auth::user()->user_type, ['admin', 'hr_manager']))
                        <a href="{{ route('hr.dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-500 hover:bg-gray-50 hover:text-[#0b4b6e] transition">Dashboard</a>
                    @endif
                    @if(Auth::user()->user_type === 'applicant')
                        <a href="{{ route('applicant.dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-500 hover:bg-gray-50 hover:text-[#0b4b6e] transition">My Profile</a>
                    @endif
                @endauth
            </div>

            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="hidden sm:inline-block text-sm font-medium text-gray-600 hover:text-[#0b4b6e] transition">Sign In</a>
                    <a href="{{ route('register') }}" class="btn-solid-sky text-sm font-semibold px-5 py-2 rounded-xl shadow-sm transition">Get Started</a>
                @endguest
                @auth
                    <div class="relative" x-data="{open:false}">
                        <button @click="open=!open" class="flex items-center gap-2 p-1.5 pr-3 rounded-xl hover:bg-gray-50 transition">
                            <div class="w-8 h-8 bg-gradient-to-br from-[#0a7aa8] to-[#0b4b6e] rounded-lg flex items-center justify-center text-white text-sm font-bold">{{ substr(Auth::user()->name,0,1) }}</div>
                            <span class="hidden md:block text-sm font-semibold text-[#0b3b5a]">{{ Auth::user()->name }}</span>
                        </button>
                        <div x-show="open" @click.away="open=false" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                            <div class="px-4 py-3 border-b"><p class="text-sm font-bold">{{ Auth::user()->name }}</p><p class="text-xs text-gray-500">{{ Auth::user()->email }}</p></div>
                            @if(Auth::user()->user_type === 'admin')
                                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-sky-50"><i class="fas fa-users w-5 mr-2"></i> Users</a>
                                <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-sky-50"><i class="fas fa-cog w-5 mr-2"></i> Settings</a>
                            @endif
                            <a href="{{ route('account.settings') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-sky-50"><i class="fas fa-user-circle w-5 mr-2"></i> Account</a>
                            <hr><form method="POST" action="{{ route('logout') }}">@csrf<button class="flex items-center w-full px-4 py-2.5 text-sm text-red-500 hover:bg-red-50"><i class="fas fa-sign-out-alt w-5 mr-2"></i> Sign Out</button></form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main>@yield('content')</main>

    <!-- FOOTER -->
    <footer class="mt-20 bg-[#0b3b5a] text-gray-300 border-t border-[#1a5270]">
        <div class="max-w-7xl mx-auto px-6 pt-14 pb-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#3b97c4] to-[#1b6f96] rounded-xl flex items-center justify-center"><i class="fas fa-hard-hat text-white"></i></div>
                        <div><span class="text-white font-extrabold text-lg">{{ \App\Helpers\SettingsHelper::companyShortName() }}</span><span class="block text-[10px] text-gray-400">CONSTRUCTION PLC</span></div>
                    </div>
                    <p class="text-sm text-gray-400">{{ \App\Helpers\SettingsHelper::companyDescription() }}</p>
                </div>
                <div>
                    <h5 class="text-white font-semibold text-sm mb-3">Career Tools</h5>
                    <ul class="text-sm space-y-2">
                        <li><a href="{{ route('resume.builder') }}" class="footer-link text-gray-400 hover:text-white">Resume Builder</a></li>
                        <li><a href="{{ route('cv.generator') }}" class="footer-link text-gray-400 hover:text-white">CV Generator</a></li>
                        <li><a href="{{ route('salary.calculator') }}" class="footer-link text-gray-400 hover:text-white">Salary Calculator</a></li>
                        <li><a href="{{ route('interview.tips') }}" class="footer-link text-gray-400 hover:text-white">Interview Tips</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-semibold text-sm mb-3">Quick Links</h5>
                    <ul class="text-sm space-y-2">
                        <li><a href="{{ route('vacancies.public.index') }}" class="footer-link text-gray-400 hover:text-white">Vacancies</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white">Projects</a></li>
                        <li><a href="{{ route('register') }}" class="footer-link text-gray-400 hover:text-white">Register</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-semibold text-sm mb-3">Contact</h5>
                    <ul class="text-sm space-y-2 text-gray-400">
                        <li><i class="fas fa-map-pin mr-2 text-[#65b7e0] w-4"></i> {{ \App\Helpers\SettingsHelper::contactAddress() }}</li>
                        <li><i class="fas fa-phone mr-2 text-[#65b7e0] w-4"></i> {{ \App\Helpers\SettingsHelper::contactPhone() }}</li>
                        <li><i class="fas fa-envelope mr-2 text-[#65b7e0] w-4"></i> {{ \App\Helpers\SettingsHelper::contactEmail() }}</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-[#1a5270] mt-12 pt-6 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} {{ \App\Helpers\SettingsHelper::companyName() }}. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
</body>
</html>
