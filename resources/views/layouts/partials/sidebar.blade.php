<nav class="space-y-1">

    <x-nav-link
        href="{{ route('dashboard') }}"
        :active="request()->routeIs('dashboard')">
        Dashboard
    </x-nav-link>

    @role('superadmin')
        <x-nav-link
            href="{{ route('users.index') }}"
            :active="request()->routeIs('users.*')">
            Users
        </x-nav-link>
    @endrole

    @can('products.index')
        <x-nav-link
            href="{{ route('products.index') }}"
            :active="request()->routeIs('products.*')">
            Products
        </x-nav-link>
    @endcan

    <x-nav-link
        href="{{ route('cart.index') }}"
        :active="request()->routeIs('cart.*')">
        Cart
    </x-nav-link>

    @can('orders.index')
        <x-nav-link
            href="{{ route('orders.index') }}"
            :active="request()->routeIs('orders.*')">
            Orders
        </x-nav-link>
    @endcan

</nav>
