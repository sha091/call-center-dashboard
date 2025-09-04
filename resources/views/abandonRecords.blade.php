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
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">report</i><a class="opacity-5 text-dark ms-2" href="javascript:;">Reports</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Abandoned Calls</li>
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
                        <div class="row my-1">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-outline-success float-end" data-bs-toggle="modal" data-bs-target="#myModal">
                                    Add Filter
                                </button>
                                <button type="button" class="btn btn-outline-success float-end mx-3">Export Excel</button>
                                <!-- Modal -->
                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                                                <button type="button" class="btn btn-sm btn-danger badge rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('agent.abandon.calls') }}" method="GET">
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
                                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="dateInput">Caller ID</label>
                                                                <input type="text" class="form-control" name="keywords" placeholder="Enter Caller Number" value="{{ isset($_GET['keywords']) ? $_GET['keywords'] : false }}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="dateInput">Options</label>
                                                                <select class="form-select"  name="search_keyword">
                                                                    <option   value="caller_id" >Caller ID</option>
                                                                    <option   value="full_name" >Agent Name</option>
                                                                    <option   value="unique_id" >Call Tracking ID</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="javascript:void(0);" type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="window.location='{{ route('agent.abandon.calls') }}'">Clear</a>
                                                    <button type="submit" class="btn btn-primary">Apply</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table id="Agent-Statistaics-Summary" class="table table-success table-striped table-bordered  dt-responsive nowrap text-center" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Caller Id</th>
                                    <th>Date Time</th>
                                    <th>Agent Name</th>
                                    <th>Call Id</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse  ($cdrs as $value)
                                    <tr>
                                        <td>{{ $value->caller_id }}</td>
                                        <td>{{ $value->call_date }}</td>
                                        <td>{{ empty($value->full_name)?"-":$value->full_name }}</td>
                                        <td>{{ $value->unique_id }}</td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Data not found</td>
                                        </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div id="paginationLinks">
                            {{ $cdrs->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                        </div>
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


