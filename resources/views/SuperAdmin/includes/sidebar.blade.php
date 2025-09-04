<style>
.dropdown-item:hover {
  background-color: #007220;
}
</style>
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="{{ route('super.admin.dashboard') }}"   >
        <img src="{{ asset('public/img/Avatar.png') }}" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">{{ Auth::user()->full_name }}</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white active bg-gradient-info" href="{{ route('super.admin.dashboard') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>

        <li class="nav-item mt-3">
            <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Admin Settings</h6>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link   text-white" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">business</i>
                </div>
                <span class="nav-link-text ms-1">Company </span>
            </a>
            <ul class="dropdown-menu shadow bg-gradient-dark  m-0" aria-labelledby="dropdown">
                <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('home.company') }}">View Companies</a></li>
                <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('create.company') }}">Create Company</a></li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white " href="{{ route('logout') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">login</i>
            </div>
            <span class="nav-link-text ms-1">Logout</span>
            </a>
        </li>

      </ul>
    </div>
    <!-- Footer Section -->
    {{-- <footer class="sidenav-footer mt-auto py-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center shadow-lg bg-mute rounded">
                    <span class="text-sm text-white">© Convex Interactive Pvt Ltd 2025.</span>
                </div>
            </div>
        </div>
    </footer> --}}

  </aside>
