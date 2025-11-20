<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" style="transition: all 0.3s ease;">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon">
            <img src="/assets/images/Logo.png"
                alt=""
                style="width: 40px; height: 40px; border-radius: 50%; transition: transform 0.3s ease;">
        </div>
        <div class="sidebar-brand-text mx-3" style="transition: all 0.3s ease;">MTS TANWIRIYYAH</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" style="transition: all 0.3s ease;">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider my-0">

    @if (auth()->user()->role === \App\Models\User::ROLES['Admin'])
        <li class="nav-item {{ request()->routeIs('admin.librarians.*') ? 'active' : '' }}" style="transition: all 0.3s ease;">
            <a class="nav-link" href="{{ route('admin.librarians.index') }}">
                <i class="fas fa-fw fa-book-open"></i>
                <span>Pustakawan</span>
            </a>
        </li>
    @endif

    @if (auth()->user()->role === \App\Models\User::ROLES['Librarian'])
        <li class="nav-item {{ request()->routeIs('admin.members.*') ? 'active' : '' }}" style="transition: all 0.3s ease;">
            <a class="nav-link" href="{{ route('admin.members.index') }}">
                <i class="fas fa-fw fa-user"></i>
                <span>Siswa</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.books.*') ? 'active' : '' }}" style="transition: all 0.3s ease;">
            <a class="nav-link" href="{{ route('admin.books.index') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>Buku</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.borrows.*') ? 'active' : '' }}" style="transition: all 0.3s ease;">
            <a class="nav-link" href="{{ route('admin.borrows.index') }}">
                <i class="fas fa-fw fa-copy"></i>
                <span>Peminjaman</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.returns.*') ? 'active' : '' }}" style="transition: all 0.3s ease;">
            <a class="nav-link" href="{{ route('admin.returns.index') }}">
                <i class="fas fa-fw fa-paste"></i>
                <span>Pengembalian</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('fines.index*') ? 'active' : '' }}" style="transition: all 0.3s ease;">
            <a class="nav-link" href="{{ route('fines.index') }}">
                <i class="fas fa-fw fa-paste"></i>
                <span>Denda</span>
            </a>
        </li>
    @endif

    <div class="mt-5 text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle" style="transition: transform 0.3s ease;"></button>
    </div>
</ul>

<!-- Tambahkan CSS animasi -->
<style>
    /* Hover efek lembut */
    .sidebar .nav-item a.nav-link {
        transition: all 0.3s ease;
        transform: translateX(0);
    }

    .sidebar .nav-item a.nav-link:hover {
        transform: translateX(5px);
        filter: brightness(1.1);
    }

    /* Efek aktif halus */
    .sidebar .nav-item.active a.nav-link {
        transform: scale(1.03);
        transition: all 0.3s ease;
    }

    /* Logo berputar lembut saat hover */
    .sidebar-brand:hover img {
        transform: rotate(10deg) scale(1.05);
    }

    /* Sidebar toggle animasi */
    #sidebarToggle:hover {
        transform: rotate(180deg);
    }
</style>
