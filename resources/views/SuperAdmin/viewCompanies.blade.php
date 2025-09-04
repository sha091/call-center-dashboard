@include('SuperAdmin.includes.header')
@include('SuperAdmin.includes.sidebar')

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
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
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">report</i><a class="opacity-5 text-dark ms-2" href="javascript:;">Company</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">View Comapies</li>
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
                        <div class="row my-1" >
                            <div class="col-md-12 col-lg-12 col-sm-12 p-2 ">
                                <!-- Button trigger modal -->
                                <div class="form-group float-end">
                                    <input type="text" class="form-control"  id="search-input" name="full_name" placeholder="Search Company" />
                                </div>
                            </div>
                        </div>
                        <table id="Agent-Statistaics-Summary" class="table table-success table-striped table-bordered  dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Company Name</th>
                                    <th>Master Number</th>
                                    <th>company Id</th>
                                    <th>POC Name</th>
                                    <th>Address</th>
                                    <th>Auto Detection</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @forelse ($cdrs as $key => $value)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $value->company_name }}</td>
                                        <td>{{ $value->master_number }}</td>
                                        <td>{{ $value->cc_id }}</td>
                                        <td>{{ $value->poc_name }}</td>
                                        <td>{{ $value->address }}</td>
                                        <td >
                                            @if ($value->auto_detection)
                                                <a id="autoDetectionBtn{{ $value->id }}"  data-status="active" class="btn btn-success btn-sm rounded" title="Active" data-bs-toggle="tooltip" data-bs-placement="top" title="Auto detection is active" data-id="{{ $value->id }}">Active</a>
                                            @else
                                                <a id="autoDetectionBtn{{ $value->id }}" data-status="inactive" class="btn btn-danger btn-sm rounded" title="Inactive" data-bs-toggle="tooltip" data-bs-placement="top" title="Auto detection is inactive" data-id="{{ $value->id }}">Inactive</a>
                                            @endif
                                        </td>
                                        <td >
                                            @if ($value->status)
                                                <a id="statusBtn{{ $value->id }}"  data-status="active" class="btn btn-success btn-sm rounded" title="Active" data-bs-toggle="tooltip" data-bs-placement="top" title="User is active" data-id="{{ $value->id }}">Active</a>
                                            @else
                                                <a id="statusBtn{{ $value->id }}" data-status="inactive" class="btn btn-danger btn-sm rounded" title="Inactive" data-bs-toggle="tooltip" data-bs-placement="top" title="User is inactive" data-id="{{ $value->id }}">Inactive</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Data not found</td>
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
@include('SuperAdmin.includes.footer')

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
            url: "{{ route('change.status') }}",  // Adjust to your route that updates status
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus,
                company_id: userId  // Pass the user ID or relevant data to identify the user
            },
            success: function(response) {
                toastr.success('Company status updated successfully!');
            },
            error: function(xhr, status, error) {
                // Handle error
                toastr.error('Failed to update status');
            }
        });
    });
});


$(document).ready(function() {
    $('[id^="autoDetectionBtn"]').click(function() {
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
            url: "{{ route('change.auto.detection.status') }}",  // Adjust to your route that updates status
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus,
                company_id: userId  // Pass the user ID or relevant data to identify the user
            },
            success: function(response) {
                toastr.success('Company status updated successfully!');
            },
            error: function(xhr, status, error) {
                // Handle error
                toastr.error('Failed to update status');
            }
        });
    });
});

$(document).ready(function() {
    // Event listener for the search input field
    $('#search-input').on('keyup', function() {
        var query = $(this).val(); // Get the current value of the input field

        // Perform AJAX request
        $.ajax({
            url: "{{ route('cdrs.search') }}", // Adjust route to match your controller
            method: 'GET',
            data: { search: query },
            success: function(response) {
                // Replace the table body with the new data
                $('#table-body').html(response.tableRows);

                // Optionally update pagination links
                $('#paginationLinks').html(response.paginationLinks);
            }
        });
    });
});

</script>

