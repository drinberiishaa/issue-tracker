<nav class="bg-white border-b border-gray-200/80 sticky top-0 z-40 backdrop-blur-sm bg-white/95">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14 sm:h-16 items-center">
            <div class="flex items-center gap-4 sm:gap-8">
                <a href="{{ route('projects.index') }}" class="flex items-center gap-2 font-bold text-base sm:text-lg group">
                    <span class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-sm group-hover:shadow-md transition-shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <span class="text-gray-900 hidden sm:inline">Issue<span class="text-brand-600">Tracker</span></span>
                    <span class="text-gray-900 sm:hidden">I<span class="text-brand-600">T</span></span>
                </a>
                <div class="hidden sm:flex gap-1 text-sm font-medium">
                    <a href="{{ route('projects.index') }}"
                       class="px-3 py-1.5 rounded-lg transition-colors {{ request()->routeIs('projects.*') ? 'bg-brand-50 text-brand-700' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            Projects
                        </span>
                    </a>
                    <a href="{{ route('issues.index') }}"
                       class="px-3 py-1.5 rounded-lg transition-colors {{ request()->routeIs('issues.*') ? 'bg-brand-50 text-brand-700' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Issues
                        </span>
                    </a>
                    <a href="{{ route('tags.index') }}"
                       class="px-3 py-1.5 rounded-lg transition-colors {{ request()->routeIs('tags.*') ? 'bg-brand-50 text-brand-700' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            Tags
                        </span>
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 text-sm">
                @auth
                    <div class="hidden sm:flex items-center gap-2 text-gray-500">
                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-brand-100 text-brand-700 text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <span class="font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="hidden sm:inline text-sm text-gray-500 hover:text-brand-600 font-medium transition-colors">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-red-500 font-medium transition-colors">Log out</button>
                    </form>
                    <!-- Mobile menu button -->
                    <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="sm:hidden p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary text-xs sm:text-sm py-1.5 px-2.5 sm:px-3">Log in</a>
                    <a href="{{ route('register') }}" class="btn-primary text-xs sm:text-sm py-1.5 px-2.5 sm:px-3">Register</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    @auth
    <div id="mobile-menu" class="hidden sm:hidden border-t border-gray-100 bg-white">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('projects.index') }}"
               class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('projects.*') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                Projects
            </a>
            <a href="{{ route('issues.index') }}"
               class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('issues.*') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Issues
            </a>
            <a href="{{ route('tags.index') }}"
               class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('tags.*') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Tags
            </a>
        </div>
        <div class="border-t border-gray-100 px-4 py-3">
            <div class="flex items-center gap-2.5 mb-3">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-brand-100 text-brand-700 text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('profile.edit') }}" class="flex-1 text-center text-sm font-medium text-gray-600 py-2 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full text-sm font-medium text-red-600 py-2 rounded-xl bg-red-50 hover:bg-red-100 transition-colors">Log out</button>
                </form>
            </div>
        </div>
    </div>
    @endauth
</nav>
