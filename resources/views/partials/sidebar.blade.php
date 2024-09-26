<!-- ========== Left Sidebar Start ========== -->
<style>
    /* Target the slimscroll-menu div to make it scrollable */
    .slimscroll-menu {
        max-height: 400px;
        overflow-y: auto;
    }

    /* Style the scrollbar (optional) */
    .slimscroll-menu::-webkit-scrollbar {
        width: 5px;
    }

    .slimscroll-menu::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .slimscroll-menu::-webkit-scrollbar-thumb {
        background: #888;
    }

    .bg-light-blue {
        background-color: #e3f2fd;
    }
</style>
<div class="left-side-menu bg-light-blue">
    <div class="user-profile text-center mt-2 mb-2">
        {{-- <div class="p-3 text-center rounded mb-2">
            <img src="{{ URL::asset('assets/images/man.png') }}" class="avatar-md rounded-circle mb-2" alt="user image" />
            <h6 class="mt-0 mb-1 text-gold">{{ Auth::user()->name }}</h6>
        </div> --}}

        <div class="media-body">
            <h6 class="pro-user-name mt-0 mb-0">{{ session()->get('names') }}</h6>
            <span class="pro-user-desc">{{ session()->get('roles') }}</span>
        </div>
        <div class="dropdown align-self-center bg-light-blue">
            <a class="dropdown-toggle mr-0 text-gold" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <span data-feather="chevron-down" class="text-gold"></span>
            </a>
            <div class="dropdown-menu bg-light-blue">
                <a href="{{ route('settings.profile') }}" class="dropdown-item notify-item text-gold">
                    <i data-feather="settings" class="icon-dual icon-xs mr-2 text-gold"></i>
                    <span class="text-gold">Settings</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item notify-item text-gold" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i data-feather="log-out" class="icon-dual icon-xs mr-2 text-gold"></i>
                    <span class="text-gold">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
    <div class="sidebar-content">
        <div id="sidebar-menu" class="slimscroll-menu">
            <ul class="metismenu" id="menu-bar">
                <li class="menu-title" style="font-size: 11px;"><b>Navigation</b></li>

                <li>
                    <a href="javascript: void(0);" style="display: block; padding: 10px 15px; font-weight: bold;">
                        <i data-feather="airplay"></i>
                        <span> Dashboard </span>
                        <span data-feather="chevron-down"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{ route('dashboard') }}"><i data-feather="activity"></i>Dashboard</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" style="display: block; padding: 10px 15px; font-weight: bold;">
                        <i data-feather="users"></i>
                        <span> Chef Management </span>
                        <span data-feather="chevron-down"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{ route('chefs.index') }}"><i data-feather="list"></i> All Chefs</a>
                        </li>
                        <li>
                            <a href="{{ route('chefs.approved') }}"><i data-feather="check-circle"></i> Approved Chefs</a>
                        </li>
                        <li>
                            <a href="{{ route('chefs.pending') }}"><i data-feather="check-circle"></i> Pending Chefs</a>
                        </li>
                    </ul>

                </li>


                <li>
                    <a href="javascript: void(0);" style="display: block; padding: 10px 15px; font-weight: bold;">
                        <i data-feather="book-open"></i>
                        <span> Topic Management </span>
                        <span data-feather="chevron-down"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{ route('topics.index') }}"><i data-feather="list"></i> View Topics</a>
                        </li>
                        <li>
                            <a href="{{ route('topics.create') }}"><i data-feather="plus-circle"></i> Add Topic</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" style="display: block; padding: 10px 10px; font-weight: bold;">
                        <i data-feather="file-text"></i>
                        <span> Recipe Management </span>
                        <span data-feather="chevron-down"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{ route('recipes.index') }}"><i data-feather="list"></i> View Recipes</a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('recipes.create') }}"><i data-feather="plus-circle"></i> Add Recipe</a>
                        </li> --}}
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" style="display: block; padding: 10px 15px; font-weight: bold;">
                        <i data-feather="tool"></i>
                        <span> Reports </span>
                        <span data-feather="chevron-down"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{ route('reports.index') }}"><i data-feather="list"></i> View Reports</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
