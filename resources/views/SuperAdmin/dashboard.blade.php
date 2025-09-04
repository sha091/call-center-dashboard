@include('SuperAdmin.includes.header')
@include('SuperAdmin.includes.sidebar')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-3">
          {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
              <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
              <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
            </ol>
            <h6 class="font-weight-bolder mb-0">Dashboard</h6>
          </nav> --}}
          <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <ul class="navbar-nav  justify-content-end">
              <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                  <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                  </div>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
                <div class="card bg-gradient-info shadow-info">
                    <div class="row">
                        <div class="card-body py-0">
                            <button type="button" class="btn btn-sm btn-light  float-end my-2" data-bs-toggle="modal" data-bs-target="#myModal">
                                <i class="fa fa-filter" aria-hidden="true"></i>
                                 Filter
                            </button>
                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                                            <button type="button" class="btn btn-sm btn-danger badge rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('super.admin.dashboard') }}" method="GET">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label for="dateInput">From Date:</label>
                                                            <input type="date" class="form-control" name="startDate" value="{{ isset($_GET['startDate']) ? $_GET['startDate'] : false }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label for="dateInput">To Date:</label>
                                                            <input type="date" class="form-control" name="endDate" value="{{ isset($_GET['endDate']) ? $_GET['endDate'] : false }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="dateInput">Options</label>
                                                            <select class="form-select"  name="company_id">
                                                                <option value="" >Please Select Company</option>
                                                                @foreach ($companies as $company)
                                                                    <option value="{{ $company->cc_id }}" >{{ $company->company_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="javascript:void(0);" type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="window.location='{{ route('super.admin.dashboard') }}'">Clear</a>
                                                <button type="submit" class="btn btn-primary">Apply</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                </div>
            </div>
        </div>



        <div class="modal fade" id="workCodeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Workcode</h5>
                      <button type="button" class="btn btn-sm btn-danger badge rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>

                  <form action="{{ route(auth()->user()->designation == 'Supervisor' ? 'insert.call.workcode' : 'agent.insert.call.workcode') }}" method="POST">
                      @csrf
                      <div class="modal-body">
                          <div class="row">
                              <div class="col-md-12 col-lg-12 col-sm-12">
                                  <div class="form-group">
                                      <label for="workcode">Workcode:</label>
                                        @php
                                            $workCodes = DB::table('cc_workcodes_new')->where('status',1)->get();
                                        @endphp
                                      @foreach ($workCodes as $item)
                                        <div class="form-check">
                                          <input
                                              class="form-check-input"
                                              type="checkbox"
                                              name="roles[]"
                                              value="{{ $item->wc_title }}"
                                              id="role{{ $item->id }}"
                                              @if(in_array($item->id, old('roles', []))) checked @endif>
                                          <label class="form-check-label" for="role{{ $item->id }}">
                                              {{ $item->wc_title }}
                                          </label>
                                        </div>
                                      @endforeach
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>



    <!-- Navbar -->
    <!-- End Navbar -->
    <!-- CONTAINER START -->

        <div class="container ">
            {{-- Missed/Outbound Call Entries START --}}
            <div class="row my-4 ">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div class="icon icon-lg icon-shape bg-gradient-info shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">add_business</i>
                            </div>
                            <div class="text-end pt-1">
                                <h4 class="mb-0">{{ $dashboard['TotalCompanies'] }}</h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0">
                                <span class="text-success text-sm font-weight-bolder"> TOTAL COMPANIES</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div class="icon icon-lg icon-shape bg-gradient-danger shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">business</i>
                            </div>
                            <div class="text-end pt-1">
                                <h4 class="mb-0">{{ $dashboard['TotalActiveCompanies'] }}</h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0">
                                <span class="text-success text-sm font-weight-bolder"> TOTAL ACTIVE COMPANIES</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div class="icon icon-lg icon-shape bg-gradient-secondary shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">support_agent</i>
                            </div>
                            <div class="text-end pt-1">
                                <h4 class="mb-0">{{ $dashboard['TotalAgents'] }}</h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0">
                                <span class="text-success text-sm font-weight-bolder"> TOTAL AGENTS</span>
                            </p>
                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div class="icon icon-lg icon-shape bg-gradient-primary shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">interpreter_mode</i>
                            </div>
                            <div class="text-end pt-1">
                                <h4 class="mb-0">{{ $dashboard['TotalActiveAgents'] }}</h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0">
                                <span class="text-success text-sm font-weight-bolder"> TOTAL ACTIVE AGENTS</span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
            {{-- Missed/Outbound Call Entries END --}}

            {{-- Agent Graph START --}}
            <div class="row">
                <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <nav aria-label="breadcrumb">
                                <h6 class="font-weight-bolder mb-0">Incoming Graph</h6>
                            </nav>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12">
                                    <div style="width: auto; margin: auto;">
                                        <canvas id="inbound-chart-bars"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <nav aria-label="breadcrumb">
                                <h6 class="font-weight-bolder mb-0">Outbound Graph</h6>
                            </nav>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12">
                                    <div style="width: auto; margin: auto;">
                                        <canvas id="outbound-chart-bars"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{-- Agent Graph END --}}

            <div class="row my-5">
                <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <nav aria-label="breadcrumb">
                                <h6 class="font-weight-bolder mb-0">Agent Graph</h6>
                            </nav>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12">
                                    <div style="width: auto; margin: auto;">
                                        <canvas id="chart-bars"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- CONTAINER END -->
</main>

{{-- <form name="dateRangeForm" id="dateRangeForm" action="{{ route('user.dashboard') }}" method="GET" >
    <input type="hidden" name="startDate" id="startDate" value="0"  />
    <input type="hidden" name="endDate" id="endDate" value="0"  />
</form> --}}

<!--   Core JS Files   -->
@include('SuperAdmin.includes.footer')

<script>
    var ctx = document.getElementById("inbound-chart-bars").getContext("2d");
    var graphDates = @json( isset($dashboard['graphDates'])? $dashboard['graphDates']:0 );
    var count = @json( isset($dashboard['inboundCounts'])? $dashboard['inboundCounts']:0 );
    var myChart = new Chart(ctx, {
        type: 'bar',
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },
        data: {

            labels: graphDates,
            datasets: [{
                label: 'Incoming Calls Count',
                data: count,
                backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(201, 203, 207, 0.2)'
                ],
                borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
                ],
                borderWidth: 1
            }]

        }
    });



</script>

<script>
    var outbound_ctx    = document.getElementById("outbound-chart-bars").getContext("2d");
    var outboundcount   = @json( isset($dashboard['outboundCounts'])? $dashboard['outboundCounts']:0 );
    var myChart = new Chart(outbound_ctx, {
        type: 'bar',
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },
        data: {

            labels: graphDates,
            datasets: [{
                label: 'Outbound Calls Count',
                data: outboundcount,
                backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(201, 203, 207, 0.2)'
                ],
                borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
                ],
                borderWidth: 1
            }]

        }
    });



</script>

<script>
    var ctx = document.getElementById("chart-bars").getContext("2d");
    var totalCount   = @json( isset($dashboard['totalCount'])? $dashboard['totalCount']:0 );

    var myChart = new Chart(ctx, {
        type: 'line',
        options: {
            scales: {
            xAxes: [{
                type: 'time',
            }]
            }
        },
        data: {
            labels: graphDates,
            datasets: [{
            label: 'Date Wise Calls',
            data: totalCount,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
            }]
        }
    });
</script>
