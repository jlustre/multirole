<div class="ml-10 top-menu">
    <div class="flex space-x-4">
        <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
            {{ __('Home') }}
        </x-nav-link>
        <x-nav-link href="{{ route('posts.index') }}" :active="request()->routeIs('posts.index')">
            {{ __('Blogs') }}
        </x-nav-link>
        <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('about')">
            {{ __('About') }}
        </x-nav-link>
        <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('contact')">
            {{ __('Contact') }}
        </x-nav-link>
    </div>
</div>