<!-- ========== Left Sidebar Start ========== -->
<style>
    /* Target the slimscroll-menu div to make it scrollable */
    .slimscroll-menu {
        max-height: 400px;
        /* Adjust to your desired maximum height */
        overflow-y: auto;
        /* Enable vertical scrollbar */
    }

    /* Style the scrollbar (optional) */
    .slimscroll-menu::-webkit-scrollbar {
        width: 5px;
        /* Adjust width as needed */
    }

    .slimscroll-menu::-webkit-scrollbar-track {
        background: #f1f1f1;
        /* Track color */
    }

    .slimscroll-menu::-webkit-scrollbar-thumb {
        background: #888;
        /* Thumb color */

    }

    .bg-light-blue {
        background-color: #e3f2fd;
        /* Light blue background */
    }
</style>
<div class="left-side-menu bg-light-blue">
    <div class="user-profile text-center mt-2 mb-2">
        <div class="p-3 text-center  rounded mb-2">
            <img src="{{ URL::asset('assets/images/man.png') }}" class="avatar-md rounded-circle mb-2" alt="user image" />
            <h6 class="mt-0 mb-1 text-gold">{{ Auth::user()->name }}</h6>
        </div>

        <div class="media-body">
            <h6 class="pro-user-name mt-0 mb-0">{{ session()->get('names') }}</h6>
            <span class="pro-user-desc">{{ session()->get('roles') }}</span>
        </div>
        <div class="dropdown align-self-center bg-light-blue">
            <a class="dropdown-toggle mr-0 text-gold" data-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                aria-expanded="false">
                <span data-feather="chevron-down" class="text-gold"></span>
            </a>
            <div class="dropdown-menu bg-light-blue">
                <a href="{{ route('settings.profile') }}" class="dropdown-item notify-item text-gold">
                    <i data-feather="settings" class="icon-dual icon-xs mr-2 text-gold"></i>
                    <span class="text-gold">Settings</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item notify-item text-gold"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
                            <a href="{{ route('dashboard') }}"><i data-feather="activity"></i>Manager Dashboard</a>
                        </li>
                        <li>
                            <a href="{{ route('PM') }}"><i data-feather="bar-chart-2"></i>Ticketing</a>
                        </li>
                        @if(in_array(Auth::user()->role, ['CEO', 'HR', 'QA']))
                        <li>
                            <a href="{{ route('qa.dashboard') }}"><i data-feather="activity"></i> QA Dashboard</a>
                        </li>
                        <li>
                            <a href="{{ route('marketing.dashboard') }}"><i data-feather="activity"></i> Marketing Dashboard</a>
                        </li>
                        {{-- //sales.dashboard --}}
                        <li>
                            <a href="{{ route('sales.dashboard') }}"><i data-feather="activity"></i> Sales Dashboard</a>
                        </li>
                        {{-- ///hr.dashboard --}}
                        <li>
                            <a href="{{ route('hr.dashboard') }}"><i data-feather="activity"></i> HR Dashboard</a>
                        </li>

                        @endif
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" style="display: block; padding: 10px 15px; font-weight: bold;">
                        <i data-feather="users"></i>
                        <span> User Management </span>
                        <span data-feather="chevron-down"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{ route('users.index') }}"><i data-feather="list"></i> View Users</a>
                        </li>
                        <li>
                            <a href="{{ route('users.create') }}"><i data-feather="user-plus"></i> Add User</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" style="display: block; padding: 10px 10px; font-weight: bold;">
                        <i data-feather="users"></i>
                        <span> Customers Management </span>
                        <span data-feather="chevron-down"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{ route('customers.index') }}"><i data-feather="list"></i> View Customers</a>
                        </li>
                        <li>
                            <a href="{{ route('customers.create') }}"><i data-feather="user-plus"></i> Add Customer</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" style="display: block; padding: 10px 15px; font-weight: bold; display: flex; align-items: center;">
                        <i data-feather="dollar-sign" style="color: #4e54c8;"></i>
                        <span style="flex: 1;">Payments Management</span>
                        <span style="margin-left: 5px;" data-feather="chevron-down" style="color: #4e54c8;"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{ route('payments.index') }}"><i data-feather="list" style="color: #4e54c8;"></i> View Payments</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" style="display: block; padding: 10px 15px; font-weight: bold;">
                        <i data-feather="tool" style="color: #ffc107;"></i>
                        <span> Maintenance Requests </span>
                        <span data-feather="chevron-down"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{ route('maintenance_requests.index') }}"><i data-feather="list" style="color: #0d0d0f;"></i> View Requests</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" style="display: block; padding: 10px 15px; font-weight: bold;">
                        <i data-feather="bar-chart-2"></i>
                        <span> Generated Reports </span>
                        <span data-feather="chevron-down"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="true">
                        <li>
                            <a href="{{ route('reports.occupancy') }}"><i style="color: #4e54c8;" data-feather="bar-chart"></i> Occupancy Report</a>
                        </li>
                        <li>
                            <a href="{{ route('reports.financial') }}"><i style="color: #0d0d0f;" data-feather="dollar-sign"></i> Financial Report</a>
                        </li>
                        <li>
                            <a href="{{ route('reports.performance') }}"><i style="color: #4e54c8;" data-feather="bar-chart"></i> Property Performance Report</a>
                        </li>
                        <li>
                            <a href="{{ route('reports.maintenance') }}"><i style="color: #ffc107;" data-feather="tool"></i> Maintenance Report</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" style="display: block; padding: 10px 15px; font-weight: bold;">
                        <i data-feather="package" style="color: #4e54c8;"></i>
                        <span> Equipment Inventory </span>
                        <span data-feather="chevron-down"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="javascript: void(0);">
                                <i data-feather="home" style="color: #4e54c8;"></i>
                                <span>Warehouse</span>
                                <span data-feather="chevron-down"></span>
                            </a>
                            <ul class="nav-third-level" aria-expanded="false">
                                <li>
                                    <a href="{{ route('warehouse.stock-in') }}"><i data-feather="arrow-down-circle" style="color: #0d0d0f;"></i> Stock In</a>
                                </li>
                                <li>
                                    <a href="{{ route('warehouse.stock-out') }}"><i data-feather="arrow-up-circle" style="color: #0d0d0f;"></i> Stock Out</a>
                                </li>
                                <li>
                                    <a href="{{ route('warehouse.items') }}"><i data-feather="list" style="color: #0d0d0f;"></i> View Inventory</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);">
                                <i data-feather="shopping-bag" style="color: #4e54c8;"></i>
                                <span>Store</span>
                                <span data-feather="chevron-down"></span>
                            </a>
                            <ul class="nav-third-level" aria-expanded="false">
                                <li>
                                    <a href="{{ route('store.stock-out') }}"><i data-feather="arrow-up-circle" style="color: #0d0d0f;"></i> Stock Out</a>
                                </li>
                                <li>
                                    <a href="{{ route('store-stock.items') }}"><i data-feather="list" style="color: #0d0d0f;"></i> View Inventory</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);">
                                <i data-feather="hard-drive" style="color: #4e54c8;"></i>
                                <span>Field</span>
                                <span data-feather="chevron-down"></span>
                            </a>
                            <ul class="nav-third-level" aria-expanded="false">
                                <li>
                                    <a href="{{ route('field.technician-acceptance') }}"><i data-feather="check-circle" style="color: #0d0d0f;"></i> Technician Acceptance</a>
                                </li>
                                <li>
                                    <a href="{{ route('field.usage-tracking') }}"><i data-feather="activity" style="color: #0d0d0f;"></i> Usage Tracking</a>
                                </li>
                                <li>
                                    <a href="{{ route('field.return-to-store') }}"><i data-feather="rotate-ccw" style="color: #0d0d0f;"></i> Return to Store</a>
                                </li>
                                <li>
                                    <a href="{{ route('warehouse.items') }}"><i data-feather="list" style="color: #0d0d0f;"></i> View Inventory</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);">
                                <i data-feather="check-circle" style="color: #4e54c8;"></i>
                                <span>Approvals</span>
                                <span data-feather="chevron-down"></span>
                            </a>
                            <ul class="nav-third-level" aria-expanded="false">
                                <li>
                                    <a href="{{ route('stock.pending-approvals') }}"><i data-feather="check-circle" style="color: #0d0d0f;"></i> Pending approvals</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                @if(in_array(Auth::user()->role, ['CEO', 'HR', 'QA']))
                <li>
                    <a href="javascript: void(0);" style="display: block; padding: 10px 15px; font-weight: bold;">
                        <i data-feather="check-circle"></i>
                        <span> QA Mgmt </span>
                        <span data-feather="chevron-down"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{ route('qa.connections') }}"><i data-feather="list"></i> Connections List</a>
                        </li>
                    </ul>
                </li>
                @endif
                {{-- for marketing dashboard --}}
                <li>
                    <a href="javascript: void(0);" style="display: block; padding: 10px 15px; font-weight: bold;">
                        <i data-feather="users"></i>
                        <span> Marketing Mgmt </span>
                        <span data-feather="chevron-down"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">

                        <li>
                            <a href="{{ route('marketing.customer-insights') }}"><i data-feather="list"></i> Customer Insights</a>
                        </li>
                    </ul>
                    {{-- //href="{{ route('marketing.customer-insights' --}}

                </li>
                {{-- hr.insights --}}
                <li>
                    <a href="javascript: void(0);" style="display: block; padding: 10px 15px; font-weight: bold;">
                        <i data-feather="users"></i>
                        <span> HR Mgmt </span>
                        <span data-feather="chevron-down"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{ route('hr.insights') }}"><i data-feather="list"></i> HR Insights</a>
                        </li>
                        {{-- //hr.technician-payables --}}
                        <li>
                            <a href="{{ route('hr.technician-payables') }}"><i data-feather="dollar-sign"></i> Payables</a>
                        </li>
                    </ul>
                </li>

                {{-- for procurement dashboard --}}
                <li>
                    <a href="javascript: void(0);" style="display: block; padding: 10px 15px; font-weight: bold;">
                        <i data-feather="users"></i>
                        <span> Procurement </span>
                        <span data-feather="chevron-down"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{ route('procurement.dashboard') }}"><i data-feather="list"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href="{{ route('procurement.vendors') }}"><i data-feather="users"></i> Vendors</a>
                        </li>
                        <li>
                            <a href="{{ route('procurement.requisitions') }}"><i data-feather="list"></i> Requisitions</a>
                        </li>
                        <li>
                            <a href="{{ route('procurement.purchase_orders') }}"><i data-feather="list"></i> Purchase Orders</a>
                        </li>
                        <li>
                            <a href="{{ route('procurement.inventory') }}"><i data-feather="list"></i> Inventory</a>
                        </li>
                    </ul>
                </li>



                <li>
                    <a href="#" style="display: block; padding: 10px 15px; font-weight: bold;">
                        <i data-feather="settings"></i>
                        <span> System Settings </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i data-feather="log-out"></i>
                        <span> Logout </span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
