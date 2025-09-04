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
                            <h6 class="font-weight-bolder mb-0">Work Code</h6>
                        </nav>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">report</i><a class="opacity-5 text-dark ms-2" href="javascript:;">WorkCode</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Work Code List</li>
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
                    <div class="card-header p-3 pt-2">
                        <div class="row my-1">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-outline-success float-end" data-bs-toggle="modal" data-bs-target="#myModal">
                                    Create Workcode
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Workcode</h5>
                                                <button type="button" class="btn btn-sm btn-danger badge rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('add.workcode') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="workcode">Workcode:</label>
                                                                <input type="text" class="form-control" name="workcode" placeholder="Please Enter Work Code Title" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Add New Workcode</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table id="example" class="table  table-striped table-bordered  dt-responsive nowrap text-center" style="width:100% overflow-x: auto">
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>Work Code Title</th>
                                    <th>Status</th>
                                    {{-- <th class="text-center">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($workcodes as $key => $data)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $data->wc_title }}</td>
                                        <td class="text-center">
                                            @if ($data->status)
                                                <a id="statusBtn{{ $data->staff_id }}"  data-status="active" class="btn btn-success btn-sm rounded" title="Active" data-bs-toggle="tooltip" data-bs-placement="top" title="User is active" data-id="{{ $data->staff_id }}">Active</a>
                                            @else
                                                <a id="statusBtn{{ $data->staff_id }}" data-status="inactive" class="btn btn-danger btn-sm rounded" title="Inactive" data-bs-toggle="tooltip" data-bs-placement="top" title="User is inactive" data-id="{{ $data->staff_id }}">Inactive</a>
                                            @endif
                                        </td>
                                        {{-- <td class="text-center">
                                            <!-- Edit Button -->
                                            <a class="btn btn-warning btn-sm rounded" title="Edit" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit user details"><i class="fa fa-pencil" aria-hidden="true"></i> </a>
                                            <!-- Delete Button -->
                                            <a class="btn btn-danger btn-sm rounded" title="Delete" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete user"><i class="fa fa-trash" aria-hidden="true"></i> </a>
                                        </td> --}}
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Data not found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div id="paginationLinks">
                            {{ $workcodes->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
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


<script>
$(document).ready(function() {
    $('[id^="statusBtn"]').click(function() {
        var button = $(this);
        var userId = button.data('id'); // Get the user ID from data-id
        var currentStatus = button.data('status'); // Get current status (active or inactive)
        var newStatus = (currentStatus === 'active') ? 'inactive' : 'active'; // Toggle status

        if (newStatus === 'active') {
            button.text('Active')
                .removeClass('btn-danger')
                .addClass('btn-success')
                .attr('title', 'User is active')
                .data('status', 'active'); // Update the status in data-status
        } else {
            button.text('Inactive')
                .removeClass('btn-success')
                .addClass('btn-danger')
                .attr('title', 'User is inactive')
                .data('status', 'inactive'); // Update the status in data-status
        }

        // Optional: AJAX to update the status in the database
        $.ajax({
            url: "{{ route('workcode.status.update') }}",  // Adjust to your route that updates status
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus,
                admin_id: userId  // Pass the user ID or relevant data to identify the user
            },
            success: function(response) {
                toastr.success('User status updated successfully!');
            },
            error: function(xhr, status, error) {
                // Handle error
                toastr.error('Failed to update status'+error);
            }
        });
    });
});
</script>

