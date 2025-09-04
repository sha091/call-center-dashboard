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
                            <h6 class="font-weight-bolder mb-0">Notification</h6>
                        </nav>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">notifications</i><a class="opacity-5 text-dark ms-2" href="javascript:;">Notification</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Notification List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3">
                        <table id="outbound-reports-cdrs" class="table table-success table-striped table-bordered  dt-responsive nowrap text-center" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>STATUS</th>
                                    <th>TEXT</th>
                                    <th>DATE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cdrs as $key => $value)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $value->text }}</td>
                                        <td>{{ $value->status == 0 ? "Unpublished" : "Published" }}</td>
                                        <td>{{ date("d F Y h:i A",strtotime($value->created_at)) }}</td>
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





