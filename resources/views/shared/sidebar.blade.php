<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link text-center">
        {{--        <img src="assets/admin/img/AdminLTELogo.png" alt="" class="brand-image img-circle elevation-3" style="opacity: .8">--}}
        <span class="brand-text font-weight-light">tSalary</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset("assets/admin/img/avatar.png") }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info dropdown show">
                <a href="/profile" class="d-block">{{ Auth::user()->name  }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        {{-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> --}}

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item ">
                    <a href="/" class="nav-link {{ $currentPath === '' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> หน้าแรก</p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="/employees" class="nav-link {{ $currentPath === 'employee' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-address-card"></i>
                        <p> รายชื่อพนักงาน</p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="/works" class="nav-link {{ $currentPath === 'working-timeline' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-business-time"></i>
                        <p> เวลาทำงาน</p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="/items/increase" class="nav-link {{ $currentPath === 'increase' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-plus-circle"></i>
                        <p> รายการเพิ่ม</p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{ route('deduction') }}" class="nav-link {{ $currentPath === 'deduction' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-minus-circle"></i>
                        <p> รายการหัก</p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="/items" class="nav-link {{ $currentPath === 'items' ? 'active' : '' }}">
                        <i class="nav-icon far fa-list-alt"></i>
                        <p> รายการ</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/reports/export" class="nav-link {{ $currentPath === 'export' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-export"></i>
                        <p> นำออก</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
