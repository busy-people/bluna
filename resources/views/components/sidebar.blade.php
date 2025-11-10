@props(['active' => 'dashboard'])

@php
use Illuminate\Support\Facades\Auth;
$user = Auth::user();
@endphp

<aside class="w-64 bg-white shadow-lg">
    <div class="p-6">
        <h1 class="text-2xl font-bold text-indigo-600">MyApp</h1>
    </div>

    <nav class="mt-6">
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-6 py-3 {{ $active === 'dashboard' ? 'text-gray-700 bg-indigo-50 border-r-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-700' }} transition">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('sales') }}"
           class="flex items-center px-6 py-3 {{ $active === 'sales' ? 'text-gray-700 bg-indigo-50 border-r-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-700' }} transition">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            Sales
        </a>

        <a href="{{ route('contribution') }}"
           class="flex items-center px-6 py-3 {{ $active === 'contribution' ? 'text-gray-700 bg-indigo-50 border-r-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-700' }} transition">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Contribution
        </a>

        <a href="{{ route('member') }}"
           class="flex items-center px-6 py-3 {{ $active === 'member' ? 'text-gray-700 bg-indigo-50 border-r-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-700' }} transition">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Member
        </a>

        <a href="{{ route('activity') }}"
           class="flex items-center px-6 py-3 {{ $active === 'activity' ? 'text-gray-700 bg-indigo-50 border-r-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-700' }} transition">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Activity
        </a>
    </nav>

    <div class="absolute bottom-0 w-64 p-6 border-t">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="mt-4 w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                Logout
            </button>
        </form>
    </div>
</aside>
