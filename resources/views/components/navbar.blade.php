<style>
.profile-dropdown-toggle {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 30px;
    padding: 5px 16px 5px 5px !important;
    transition: all 0.3s ease;
}

.profile-dropdown-toggle:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-1px);
}

.profile-dropdown-toggle span {
    font-weight: 500;
    color: white !important;
}

.profile-avatar-nav {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease;
}

.profile-dropdown-toggle:hover .profile-avatar-nav {
    transform: scale(1.05);
}

/* Dropdown Menu */
.profile-dropdown-menu {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    padding: 8px;
    margin-top: 10px;
    min-width: 180px;
}

.profile-dropdown-menu .dropdown-item {
    border-radius: 8px;
    padding: 10px 16px;
    transition: all 0.2s ease;
    font-weight: 500;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 10px;
}

.profile-dropdown-menu .dropdown-item:hover {
    background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
    color: #0369a1;
    transform: translateX(4px);
}

.profile-dropdown-menu .dropdown-item.text-danger:hover {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #dc2626;
}

/* hide username text on small phones, keep avatar visible */
@media (max-width: 400px) {
    .profile-dropdown-toggle .profile-username {
        display: none;
    }
    .profile-dropdown-toggle {
        padding: 4px 10px 4px 4px !important;
    }
}
</style>
<div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm" style="position: sticky; top: 0; z-index: 10;">
        <div class="container d-flex align-items-center">

            {{-- ☰ Sidebar toggle — mobile only, shown when logged in --}}
            @auth
                @if (!request()->routeIs('login.page', 'register.page', 'landing'))
                    <button class="navbar-toggler d-lg-none border-0 me-2" type="button" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                @endif
            @endauth

            {{-- spacer to push profile to right --}}
            <span class="flex-grow-1"></span>

            {{-- ── Profile dropdown — ALWAYS visible (outside collapse) ── --}}
            @auth
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 profile-dropdown-toggle"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                        {{-- Foto Profile --}}
                        <img
                            src="{{ auth()->user()->profile_picture
                                ? asset('storage/' . auth()->user()->profile_picture)
                                : asset('img/user-default.jpg') }}"
                            alt="Profile"
                            class="rounded-circle border border-white profile-avatar-nav"
                            width="35"
                            height="35"
                            style="object-fit: cover;"
                        >
                        {{-- Username --}}
                        <span class="profile-username">{{ auth()->user()->username }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end profile-dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-circle"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" onsubmit="showLoading()">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                {{-- Guest: Login / Sign Up buttons (collapse on very small screens) --}}
                <div class="d-flex gap-2">
                    @if (request()->routeIs('register.page'))
                        <a class="btn btn-light text-primary px-3" href="{{ route('login.page') }}">Login</a>
                    @else
                        <a class="btn btn-light text-primary px-3" href="{{ route('register.page') }}">Sign Up</a>
                    @endif
                </div>
            @endauth

        </div>
    </nav>
</div>
