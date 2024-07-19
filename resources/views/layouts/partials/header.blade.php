<nav class="flex items-center justify-between px-6 py-3 border-b border-gray-100">
  <div id="nav-left" class="flex items-center">
      <x-application-logo size="text-xl"/>
      @include('layouts.partials.header-left-menu')
  </div>
  <div id="nav-right" class="flex items-center md:space-x-6">
    @auth
      @include('layouts.partials.header-right-auth')
    @else
      @include('layouts.partials.header-right-guest')
    @endauth
  </div>
</nav>