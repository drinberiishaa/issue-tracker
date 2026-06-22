<nav class="bg-white border-b border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-8">
                <a href="{{ route('projects.index') }}" class="flex items-center gap-2 font-bold text-lg text-brand-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Issue Tracker
                </a>
                <div class="hidden sm:flex gap-6 text-sm font-medium text-gray-600">
                    <a href="{{ route('projects.index') }}" class="hover:text-brand-600 {{ request()->routeIs('projects.*') ? 'text-brand-600' : '' }}">Projects</a>
                    <a href="{{ route('issues.index') }}" class="hover:text-brand-600 {{ request()->routeIs('issues.*') ? 'text-brand-600' : '' }}">Issues</a>
                    <a href="{{ route('tags.index') }}" class="hover:text-brand-600 {{ request()->routeIs('tags.*') ? 'text-brand-600' : '' }}">Tags</a>
                </div>
            </div>

            <div class="flex items-center gap-4 text-sm">
                @auth
                    <span class="text-gray-500">{{ auth()->user()->name }}</span>
                    <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-brand-600">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-brand-600">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-brand-600">Log in</a>
                    <a href="{{ route('register') }}" class="bg-brand-600 text-white px-3 py-1.5 rounded-md hover:bg-brand-700">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
