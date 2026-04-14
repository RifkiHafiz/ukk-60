<div>
<style>
    .navbar-brand {
        font-weight: 800;
        font-size: 1.5rem;
        color: #0ea5e9 !important;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .navbar-brand img {
        height: 40px;
        width: auto;
    }

    .navbar-brand div {
        display: flex;
    }

    .navbar-brand span {
        color: #0369a1;
    }

    .navbar-brand:hover {
        opacity: 0.8;
        transform: scale(1.02);
        transition: all 0.2s ease;
    }

    .collapse {
        transition: height 0.3s ease;
    }

    a[data-bs-toggle="collapse"] i.bi-chevron-down {
        transition: transform 0.3s ease;
    }

    a[data-bs-toggle="collapse"][aria-expanded="true"] i.bi-chevron-down {
        transform: rotate(180deg);
    }

    .nav-pills .nav-link {
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }

    .nav-pills .nav-link:hover:not(.active) {
        background-color: #f3f4f6;
    }

    .nav-pills .collapse .nav-link.active {
        background-color: var(--bs-primary);
        color: white !important;
    }

    /* ── Mobile: sidebar off-canvas behaviour ─────────────── */
    #appSidebar {
        transition: transform 0.3s ease;
    }

    @media (max-width: 991.98px) {
        #appSidebar {
            transform: translateX(-270px);
            z-index: 1045 !important;
        }
        #appSidebar.sidebar-open {
            transform: translateX(0);
        }
    }

    /* semi-transparent overlay behind sidebar on mobile */
    #sidebarOverlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 1044;
    }
    #sidebarOverlay.active {
        display: block;
    }
</style>
    <div id="sidebarOverlay" onclick="closeSidebar()"></div>
    <div id="appSidebar" class="d-flex flex-column p-3 bg-white rounded-end-5"
        style="position: fixed; top: 0; left: 0; width: 260px; min-height: 100vh; border-right: 1px solid #e5e7eb; z-index: 10;">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('img/logo-BorrowMe.png') }}" alt="BorrowMe Logo">
            <div><span>Borrow</span>Me</div>
        </a>

        <hr>

        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                class="nav-link d-flex align-items-center gap-2
                {{ request()->is('dashboard') ? 'active' : 'text-dark' }}">
                    <i class="bi bi-clipboard-data"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center justify-content-between gap-2 text-dark"
                   data-bs-toggle="collapse"
                   href="#loansMenu"
                   role="button"
                   aria-expanded="{{ request()->is('loans*') || request()->is('manage*') ? 'true' : 'false' }}"
                   aria-controls="loansMenu">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-bag-plus"></i>
                        Loans
                    </span>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse {{ request()->is('loans*') || request()->is('manage*') ? 'show' : '' }}" id="loansMenu">
                    <ul class="nav flex-column ms-3 mt-1 gap-1">
                        @if (Auth::user()->role !== 'Staff')
                            <li class="nav-item">
                                <a href="{{ route('loans.index') }}"
                                    class="nav-link d-flex align-items-center gap-2 py-2
                                    {{ request()->is('loans') ? 'active' : 'text-dark' }}">
                                    <i class="bi bi-plus-circle"></i>
                                    Borrow Item
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ route('loans.index-table') }}"
                            class="nav-link d-flex align-items-center gap-2 py-2
                            {{ request()->is('loans/index-table') ? 'active' : 'text-dark' }}">
                                <i class="bi bi-list-ul"></i>
                                Manage Loans
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="{{ route('returns.index') }}"
                class="nav-link d-flex align-items-center gap-2
                {{ request()->is('returns') ? 'active' : 'text-dark' }}">
                    <i class="bi bi-arrow-left-circle"></i>
                    Returns
                </a>
            </li>
            @if (Auth::user()->role === 'Admin')
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center justify-content-between gap-2 text-dark"
                    data-bs-toggle="collapse"
                    href="#itemsMenu"
                    role="button"
                    aria-expanded="{{ request()->is('items*') || request()->is('categories*') ? 'true' : 'false' }}"
                    aria-controls="itemsMenu">
                        <span class="d-flex align-items-center gap-2">
                            <i class="bi bi-tools"></i>
                            Items
                        </span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->is('items*') || request()->is('categories*') ? 'show' : '' }}" id="itemsMenu">
                        <ul class="nav flex-column ms-3 mt-1 gap-1">
                            <li class="nav-item">
                                <a href="{{ route('items.index') }}"
                                class="nav-link d-flex align-items-center gap-2 py-2
                                {{ request()->is('items') ? 'active' : 'text-dark' }}">
                                    <i class="bi bi-box-seam"></i>
                                    Manage Items
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('categories.index') }}"
                                class="nav-link d-flex align-items-center gap-2 py-2
                                {{ request()->is('categories') ? 'active' : 'text-dark' }}">
                                    <i class="bi bi-card-checklist"></i>
                                    Item Categories
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if(Auth::user()->role === 'Admin')
                <li class="nav-item">
                    <a href="{{ route('user.index') }}"
                    class="nav-link d-flex align-items-center gap-2
                    {{ request()->is('user') ? 'active' : 'text-dark' }}">
                        <i class="bi bi-person-circle"></i>
                        Users
                    </a>
                </li>
            @endif
            @if (Auth::user()->role === 'Admin')
                <li class="nav-item">
                    <a href="{{ route('activity-logs.index') }}"
                    class="nav-link d-flex align-items-center gap-2
                    {{ request()->is('activity-logs') ? 'active' : 'text-dark' }}">
                        <i class="bi bi-activity"></i>
                        Log Activity
                    </a>
                </li>
            @endif
            @if (Auth::user()->role === 'Admin')
                <li class="nav-item">
                    <a href="{{ route('reports.index') }}"
                    class="nav-link d-flex align-items-center gap-2
                    {{ request()->is('reports*') ? 'active' : 'text-dark' }}">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        Report
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar  = document.getElementById('appSidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('sidebar-open');
    overlay.classList.toggle('active');
}
function closeSidebar() {
    document.getElementById('appSidebar').classList.remove('sidebar-open');
    document.getElementById('sidebarOverlay').classList.remove('active');
}
// Close sidebar when a nav link is clicked on mobile
document.addEventListener('DOMContentLoaded', function () {
    if (window.innerWidth < 992) {
        document.querySelectorAll('#appSidebar .nav-link:not([data-bs-toggle])').forEach(function(link) {
            link.addEventListener('click', closeSidebar);
        });
    }
});
</script>
