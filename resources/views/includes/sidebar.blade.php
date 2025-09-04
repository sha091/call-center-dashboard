<style>
.dropdown-item:hover {
  background-color: #007220;
}
</style>
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="{{ route(auth()->user()->designation == 'Supervisor' ? 'user.dashboard' : 'agent.user.dashboard') }}"   >
        <img src="{{ asset('public/img/Avatar.png') }}" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">{{ Auth::user()->full_name }}</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white active bg-gradient-info" href="{{ route(auth()->user()->designation == 'Supervisor' ? 'user.dashboard' : 'agent.user.dashboard') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>


        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Call Agent Dashboard</h6>
        </li>



        @if (Auth::user()->designation == "Supervisor")
            <li class="nav-item">
                <a href="#" class="nav-link   text-white" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">dashboard</i>
                </div>
                <span class="nav-link-text ms-1">Call Agent Dashboard </span>
                </a>
                <ul class="dropdown-menu shadow bg-gradient-dark  m-0" aria-labelledby="dropdown">
                    <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('call.agent.home') }}">Agent Stats</a></li>
                    <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('call.center.wallboard') }}">Call Center Wallborad</a></li>
                    <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('agent.statistics.summary') }}">Agent Statistics Summary </a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link   text-white" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">leaderboard</i>
                </div>
                <span class="nav-link-text ms-1">Reports </span>
                </a>
                <ul class="dropdown-menu shadow bg-gradient-dark  m-0" aria-labelledby="dropdown">
                    <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('call.records') }}">Call Records</a></li>
                    <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('outbound.reports') }}">Outbound Reports</a></li>
                    <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('inbound.reports') }}">Inbound Reports</a></li>
                    <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('agent.summary.reports') }}">Agent Summary Reports</a></li>
                    <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('agent.productivity.home') }}">Agent Productivity Report</a></li>
                    <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('off.time.reports') }}">Off Time Report</a></li>
                    {{-- <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('agent.abandon.calls') }}">Abandoned Call Report</a></li> --}}
                    <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('missed.call.reports') }}">Missed Call Report</a></li>
		    <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('transferred.call.report') }}">Transferred Call Report</a></li>


       {{--      <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('feedback.report') }}">Feedback Report</a></li>--}}

	    
	</ul>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('outbound.home') }}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">phone</i>
                </div>
                <span class="nav-link-text ms-1">Outbound</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link   text-white" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">notifications</i>
                </div>
                <span class="nav-link-text ms-1">Notification </span>
                </a>
                <ul class="dropdown-menu shadow bg-gradient-dark  m-0" aria-labelledby="dropdown">
                    <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('notification.list') }}">Notification List</a></li>
                    <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('notification') }}">Notification Add</a></li>
                </ul>
            </li>

            @if (Auth::user()->company->is_robocall)
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('campaign') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">podcasts</i>
                    </div>
                    <span class="nav-link-text ms-1">Robocall</span>
                    </a>
                </li>    
            @endif
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ url('chatify').'/'.Auth::user()->admin_id }}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">chat</i>
                </div>
                <span class="nav-link-text ms-1">Chat</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link   text-white" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">settings</i>
                </div>
                <span class="nav-link-text ms-1">Settings </span>
                </a>
                <ul class="dropdown-menu shadow bg-gradient-dark  m-0" aria-labelledby="dropdown">
                    <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('settings.working.hours') }}">Working Hours</a></li>
                    @if (Auth::user()->company->is_callflow)
                        <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('sound-settings') }}">Sound Settings</a></li>
                        <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('create-flow') }}">Create Flow</a></li>
                        <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('queue') }}">Queue</a></li>
                    @endif
                </ul>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Admin Settings</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('user.list') }}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">person</i>
                </div>
                <span class="nav-link-text ms-1">User</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('workcode.home') }}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">work</i>
                </div>
                <span class="nav-link-text ms-1">WorkCode</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white " href="{{ route('logout') }}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">login</i>
                </div>
                <span class="nav-link-text ms-1">Logout</span>
                </a>
            </li>

        @else
            <li class="nav-item">
                <a href="#" class="nav-link   text-white" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">dashboard</i>
                </div>
                <span class="nav-link-text ms-1">Call Agent Dashboard </span>
                </a>
                <ul class="dropdown-menu shadow bg-gradient-dark  m-0" aria-labelledby="dropdown">
                    <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('agent.call.agent.home') }}">Agent Stats</a></li>
                </ul>
            </li>

            <li class="nav-item">
            <a href="#" class="nav-link   text-white" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">leaderboard</i>
                </div>
                <span class="nav-link-text ms-1">Reports </span>
            </a>
            <ul class="dropdown-menu shadow bg-gradient-dark  m-0" aria-labelledby="dropdown">
                <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('agent.missed.call.reports') }}">Missed Call Report</a></li>
                <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('agents.agent.summary.reports') }}">Agent Summary Reports</a></li>
                <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('agents.off.time.reports') }}">Off Time Report</a></li>
                {{-- <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('call.records') }}">Call Records</a></li>
                <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('outbound.reports') }}">Outbound Reports</a></li>
                <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('inbound.reports') }}">Inbound Reports</a></li>
                <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('agent.productivity.home') }}">Agent Productivity Report</a></li>
                <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('agent.abandon.calls') }}">Abandoned Call Report</a></li>
                <li class="bg-gradient-dark"><a class="ps-5 dropdown-item text-white" href="{{ route('agent.transferred.call.report') }}">Transferred Call Report</a></li> --}}
            </ul>
            </li>

            <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('agent.outbound.home') }}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">phone</i>
                </div>
                <span class="nav-link-text ms-1">Outbound</span>
            </a>
            </li>

            <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('client') }}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">task</i>
                </div>
                <span class="nav-link-text ms-1">Client Data</span>
            </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="{{ url('chatify').'/'.Auth::user()->admin_id }}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">chat</i>
                </div>
                <span class="nav-link-text ms-1">Chat</span>
                </a>
            </li>


            <li class="nav-item mt-3">
            <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Admin Settings</h6>
            </li>

            <li class="nav-item">
            <a class="nav-link text-white " href="{{ route('logout') }}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">login</i>
                </div>
                <span class="nav-link-text ms-1">Logout</span>
            </a>
            </li>

        @endif
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
