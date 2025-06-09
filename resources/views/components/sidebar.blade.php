<div id="app" class="relative">

    <div
        id="sidebar"
        class="fixed flex flex-col left-0 top-16 bg-sekunder text-white text-lg p-8 gap-5 h-screen w-55 transform -translate-x-full
            sm:min-w-55 sm:fixed sm:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto"
        >
        <!-- ALL : DASHBOARD -->
        <a href="{{ route('dashboard.index') }}" class="flex gap-3 items-center p-1 hover:bg-aksen">
            <img src="{{ asset('images/icons/sidebar/dashboard-icon.svg') }}" alt="Sidebar-Dashboard"
                class="h-10 w-10"
            >
            Dashboard
        </a>
        <!-- ALL : PROFIL -->
        <a href="{{ route('profil.index') }}" class="flex gap-3 items-center p-1 hover:bg-aksen">
            <img src="{{ asset('images/icons/defaultAvatarLight.png') }}" alt="Sidebar-Profi"
                class="w-10 h-10"
            >
            Profil
        </a>
        @if (Auth::user()->role->nama_role == 'customer')
        <!-- CUST : LELANG ANDA -->
        <a href="{{ route('lelang.saya') }}" class="flex gap-3 items-center p-1 hover:bg-aksen">
            <img src="{{ asset('images/icons/sidebar/lelang-icon.svg') }}" alt="Sidebar-Lelang"
                class="w-10 h-10"
            >
            Lelang anda
        </a>
        <!-- CUST : TRANSAKSI -->
        <a href="{{ route('transaksi.index') }}" class="flex gap-3 items-center p-1 hover:bg-aksen">
            <img src="{{ asset('images/icons/sidebar/transaksi-icon.svg') }}" alt="Sidebar-Transaksi"
                class="h-10 w-10"
            >
            Transaksi
        </a>
        @elseif (Auth::user()->role->nama_role == 'pegawai' || Auth::user()->role->nama_role == 'owner')
        <!-- PEGAWAI & OWNER : KATALOG -->
        <a href="{{ route('dashboard.katalog') }}" class="flex gap-3 items-center p-1 hover:bg-aksen">
            <img src="{{ asset('images/icons/sidebar/katalog-icon.svg') }}" alt="Sidebar-Katalog"
                class="w-10 h-10"
            >
            Katalog
        </a>
        <!-- PEGAWAI & OWNER : LELANG -->
        <a href="{{ route('dashboard.lelang') }}" class="flex gap-3 items-center p-1 hover:bg-aksen">
            <img src="{{ asset('images/icons/sidebar/lelang-icon.svg') }}" alt="Sidebar-Lelang"
                class="w-10 h-10"
            >
            Lelang
        </a>
        <!-- PEGAWAI & OWNER : TRANSAKSI -->
        <a href="{{ route('dashboard.transaksi') }}" class="flex gap-3 items-center p-1 hover:bg-aksen">
            <img src="{{ asset('images/icons/sidebar/transaksi-icon.svg') }}" alt="Sidebar-Transaksi"
                class="h-10 w-10"
            >
            Transaksi
        </a>
            @if (Auth::user()->role->nama_role == 'owner')
            <!-- OWNER : PEGAWAI SAYA -->
            <a href="{{ route('pegawai.index') }}" class="flex gap-3 items-center p-1 hover:bg-aksen">
                <img src="{{ asset('images/icons/sidebar/employee-icon.svg') }}" alt="Sidebar-Pegawai"
                    class="w-10 h-10"
                >
                Pegawai Saya
            </a>
            @endif
        @endif
    </div>

</div>
