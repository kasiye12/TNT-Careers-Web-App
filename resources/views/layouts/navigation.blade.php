<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="text-xl font-bold text-blue-900">
                        TNT <span class="text-blue-600">RATS</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ url('/') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is('/') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm font-medium">
                        Home
                    </a>

                    @auth
                        @if(Auth::user()->user_type === 'applicant')
                            <a href="{{ route('applicant.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('applicant.dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm font-medium">
                                Dashboard
                            </a>
                            <a href="{{ route('vacancies.public.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('vacancies.public.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm font-medium">
                                Vacancies
                            </a>
                            <a href="{{ route('applicant.applications') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('applicant.applications*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm font-medium">
                                My Applications
                            </a>
                        @endif

                        @if(in_array(Auth::user()->user_type, ['admin', 'hr_manager']))
                            <a href="{{ route('hr.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('hr.dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm font-medium">
                                HR Dashboard
                            </a>
                            
                            <!-- Vacancies Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700">
                                    Vacancies
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                    <a href="{{ route('hr.vacancies.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Manage Vacancies</a>
                                    <a href="{{ route('hr.vacancies.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Create New</a>
                                    <a href="{{ route('hr.reports.vacancy-progress') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Progress Report</a>
                                </div>
                            </div>

                            <!-- Applications Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700">
                                    Applications
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                    <a href="{{ route('hr.applications.review') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Review Applications</a>
                                    <a href="{{ route('hr.applications.shortlisted') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Shortlisted</a>
                                    <a href="{{ route('hr.applications.search') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Search</a>
                                </div>
                            </div>

                            <!-- Reports Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700">
                                    Reports
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                    <a href="{{ route('hr.reports.vacancy-progress') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Vacancy Progress</a>
                                    <a href="{{ route('hr.reports.demographics') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Demographics</a>
                                    <a href="{{ route('hr.shortlist-matrix') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Candidate Matrix</a>
                                </div>
                            </div>
                        @endif

                        @if(Auth::user()->user_type === 'evaluator')
                            <a href="{{ route('hr.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('hr.dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm font-medium">
                                Evaluation Dashboard
                            </a>
                        @endif
                    @endauth

                    @guest
                        <a href="{{ route('vacancies.public.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('vacancies.public.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm font-medium">
                            Vacancies
                        </a>
                    @endguest
                </div>
            </div>

            <!-- User Menu -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @guest
                    <div class="flex space-x-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-gray-900">Register</a>
                        @endif
                    </div>
                @endguest

                @auth
                    <div class="ml-3 relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                            {{ Auth::user()->name }}
                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                            <a href="{{ route('account.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Account Settings</a>
                            
                            @if(Auth::user()->user_type === 'applicant')
                                <a href="{{ route('applicant.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Profile</a>
                                <a href="{{ route('applicant.documents') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Documents</a>
                            @endif
                            
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <a href="{{ route('hr.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('hr.dashboard') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-800' }}">Dashboard</a>
                <a href="{{ route('hr.vacancies.index') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800">Manage Vacancies</a>
                <a href="{{ route('hr.applications.review') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800">Review Applications</a>
            @endauth
        </div>
        
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
