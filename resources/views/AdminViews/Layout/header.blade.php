
    <!-- ======= Header ======= -->

  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <i class="bi me-2 bi-list toggle-sidebar-btn"></i>
      <a href="{{route('dashboard.view')}}" class="logo d-flex align-items-center">
        <h1><img src="{{asset('/images/logo.png')}}" width="33px"/><span class="text-secendory">Mis</span><span class="text-primary">Placed</span></h1>

      </a>
    </div><!-- End Logo -->


    <div class="pagetitle">
        <h1>@yield('title')</h1>
    </div>
    {{-- <div class="search-bar">

      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar --> --}}

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            @if (auth()->guard('admin')->check())
            <img src={{url(auth()->guard('admin')->user()->photo)}} alt="Profile" class="rounded-circle" >

            <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->guard('admin')->user()->name }}</span>
                      @endif
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              @if (auth()->guard('admin')->check())
    <h6>{{ auth()->guard('admin')->user()->name }}</h6>


              <span>{{ auth()->guard('admin')->user()->role }}</span>
              @endif
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            {{-- <li>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li> --}}
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{route('admin.settings')}}">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{route('admin.logout')}}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->
