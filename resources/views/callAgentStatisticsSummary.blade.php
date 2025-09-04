@include('includes.header')
@include('includes.sidebar')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('includes.topbar')
    <!-- End Navbar -->
    <!-- CONTAINER START -->
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <nav aria-label="breadcrumb">
                            <h6 class="font-weight-bolder mb-0">Reports</h6>
                        </nav>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">report</i><a class="opacity-5 text-dark ms-2" href="javascript:;">Call Agent Dashboard</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Agent Statistics Summary</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-5">
            <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <table id="Agent-Statistaics-Summary" class="table table-success table-striped table-bordered  dt-responsive nowrap text-center" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Agent Name</th>
                                    <th>Inbound Calls</th>
                                    <th>Outbound Calls</th>
                                    <th>Break Time</th>
                                    <th>Assignment Time</th>
                                    <th>Login Time</th>
                                    <th>Busy Time</th>
                                    <th>Time Duration</br>(Last Status)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $value)
                                    <tr>
                                        <td>{{ $value['Admin_FullName'] }}</td>
                                        <td>{{ $value['InboundCalls'] }}</td>
                                        <td>{{ $value['OutboundCalls'] }}</td>
                                        <td>{{ $value['BreakTime'] }}</td>
                                        <td>{{ $value['AssignmentTime'] }}</td>
                                        <td>{{ $value['LoginTime'] }}</td>
                                        <td>{{ $value['BusyTime'] }}</td>
                                        <td>{{ $value['TimeDuration'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $cc_admin->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTAINER END -->
</main>

<!--   Core JS Files   -->
@include('includes.footer')
@include('includes.custom')
