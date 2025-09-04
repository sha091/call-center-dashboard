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
                            <h6 class="font-weight-bolder mb-0">Call Flow</h6>
                        </nav>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">report</i><a class="opacity-5 text-dark ms-2" href="javascript:;">Call Flow</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Queue</li>
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
                                    Add Queue
                                </button>                                
                                <!-- Modal -->
                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Create Queue</h5>
                                                <button type="button" class="btn btn-sm btn-danger badge rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('add-queue') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="input">Queue name</label>
                                                                <input type="text" class="form-control" name="queue_name"  placeholder="Enter queue name"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="select">Queue</label>
                                                                <select id="extensions" class="form-select" name="extensions[]" multiple>                                                                    
                                                                    @foreach ($extensions as $extension)
                                                                        <option value="{{ $extension->admin_id }}">{{ $extension->agent_exten }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <!-- Hidden input to store ordered values -->
                                                                <input type="hidden" id="orderedExtensions" name="ordered_extensions" />
                                                            </div>
                                                        </div>                                                    
                                                        <div class="col-md-6 col-lg-6 col-sm-6 my-2">
                                                            <div class="form-group">
                                                                <label for="dateInput">Queue type</label>
                                                                <select class="form-select" name="queue_type">
                                                                    <option value="" >Select a queue type</option>
                                                                    <option value="sequence" >Sequence</option>
                                                                    <option value="Priority" >Priority</option>                      
                                                                </select>
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
                        <table id="Agent-Statistaics-Summary" class="table table-success table-striped table-bordered  dt-responsive nowrap text-center" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Queue Name</th>
                                    <th>Queue Type</th>
                                    <th>Extensions</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($queue as $key => $value)
                                    <tr>
                                        <td>{{ $value->queue_name }}</td>
                                        <td>{{ $value->queue_type }}</td>
                                        <td>{{ $value->agent_exten }}</td>
                                        <td>{{ $value->created_at }}</td>
                                        <td>
                                            <a href="{{ route('show-queue', ['queue_id' => $value->queue_id]) }}" class="btn btn-success btn-sm me-2">Update</a>
                                            <a href="{{ route('delete-queue', ['queue_id' => $value->queue_id]) }}" class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Data not found</td>
                                        </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div id="paginationLinks">
                            {{ $queue->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
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
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('extensions');
        const hiddenInput = document.getElementById('orderedExtensions');

        if (select) {
            const choices = new Choices(select, {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Select extensions',
                shouldSort: false
            });

            // Listen for change to capture selected order
            select.addEventListener('change', function () {
                const selectedValues = choices.getValue(true); // Gets array of selected values in order
                hiddenInput.value = JSON.stringify(selectedValues); // Store as JSON string (or comma-separated if you prefer)
            });
        }
    });
</script>