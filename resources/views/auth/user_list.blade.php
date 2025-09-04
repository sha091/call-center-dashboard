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
                            <h6 class="font-weight-bolder mb-0">User</h6>
                        </nav>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">person</i><a class="opacity-5 text-dark ms-2" href="javascript:;">User</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">User List</li>
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
                                    Create User
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                                                <button type="button" class="btn btn-sm btn-danger badge rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('user.registration') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="agent_exten">Agent Extension:</label>
                                                                <input type="number" class="form-control" name="agent_exten" value="{{ $agent_exten }}"  readonly />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="full_name">Full Name:</label>
                                                                <input type="text" class="form-control" name="full_name" placeholder="Please Enter Full Name" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="email">Email:</label>
                                                                <input type="email" class="form-control" name="email" placeholder="Please Enter Email Address" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="password">Password:</label>
                                                                <input type="password" class="form-control" name="password" placeholder="Please Enter Password" />
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Add</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <table id="example" class="table  table-striped table-bordered  dt-responsive nowrap" style="width:100% overflow-x: auto">
                            <thead>
                                <tr>
                                    <th>Admin Id</th>
                                    <th>Agent Extension</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($user_data as $key => $data)
                                    <tr>
                                        <td>{{ $data->admin_id }}</td>
                                        <td class="text-danger">{{ $data->agent_exten }}</td>
                                        <td>{{ $data->full_name }}</td>
                                        <td>{{ $data->email }}</td>
                                        <td>{{ $data->designation }}</td>
                                        <td class="text-center">
                                            @if ($data->status)
                                                <a id="statusBtn{{ $data->admin_id }}"  data-status="active" class="btn btn-success btn-sm rounded" title="Active" data-bs-toggle="tooltip" data-bs-placement="top" title="User is active" data-id="{{ $data->admin_id }}">Active</a>
                                            @else
                                                <a id="statusBtn{{ $data->admin_id }}" data-status="inactive" class="btn btn-danger btn-sm rounded" title="Inactive" data-bs-toggle="tooltip" data-bs-placement="top" title="User is inactive" data-id="{{ $data->admin_id }}">Inactive</a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <!-- Edit Button -->
                                            <a class="btn btn-secondary btn-sm rounded edit-btn" title="ResetPassword" data-bs-toggle="modal" data-bs-target="#myEditModal"
                                                data-user-id="{{ $data->admin_id }}"
                                                id="resetPasswordBtn">
                                                <i class="fa fa-key" aria-hidden="true"></i>
                                            </a>
                                            <!-- Delete Button -->
                                            {{-- <a class="btn btn-danger btn-sm rounded" title="Delete" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete user"><i class="fa fa-trash" aria-hidden="true"></i> </a> --}}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Data not found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $user_data->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="myEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reset Password</h5>
                    <button type="button" class="btn btn-sm btn-danger badge rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('user.change.password') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" name="password"  placeholder="Please Enter Password" />
                                    <input type="hidden" id="resetPassword" name="admin_id"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                </form>
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
            url: "{{ route('user.status.update') }}",  // Adjust to your route that updates status
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
                toastr.error('Failed to update status');
            }
        });
    });
});
</script>


<script>
$(document).ready(function() {
    $('[id^="resetPasswordBtn"]').click(function() {
        var button = $(this);
        var userId = button.data('user-id'); // Get the user ID from data-id
        $("#resetPassword").val(userId);
    });
});
</script>
