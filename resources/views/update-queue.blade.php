@include('includes.header')
@include('includes.sidebar')

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
                            <h6 class="font-weight-bolder mb-0">Call Flow</h6>
                        </nav>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">report</i><a class="opacity-5 text-dark ms-2" href="javascript:;">Call Flow</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Update-Queue</li>
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
                        <form action="{{ route('update-queue') }}" method="POST">                            
                            @csrf
                            <input type="hidden" name="queue_id" value="{{ $queue->queue_id }}">
                            <div class="card-body">
                                <div class="row g-3">
                                    <!-- Queue Name -->
                                    <div class="col-md-6">
                                        <label for="queueName" class="form-label">Queue Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="queueName" name="queue_name" placeholder="Enter queue name" value="{{ old('queue_name', $queue->queue_name) }}">
                                    </div>
                    
                                    <!-- Queue Extensions -->
                                    <div class="col-md-6">
                                        @php                                            
                                            $selected_admin_ids = explode(',', $queue->admin_id);
                                            $ordered_extensions = collect($extensions)->sortBy(function ($extension) use ($selected_admin_ids) {
                                                return array_search($extension->admin_id, $selected_admin_ids);
                                            });                                                                                           
                                        @endphp
                                        <label for="extensions" class="form-label">Queue Extensions <span class="text-danger">*</span></label>
                                        <select id="extensions" class="form-select" name="extensions[]" multiple required>                                            
                                            @foreach ($ordered_extensions    as $extension)
                                                <option value="{{ $extension->admin_id }}"
                                                    {{ in_array($extension->admin_id, $selected_admin_ids) ? 'selected' : '' }}>
                                                    {{ $extension->agent_exten }}
                                                </option>
                                            @endforeach
                                        </select> 
                                         <!-- Hidden input to store ordered values -->
                                         <input type="hidden" id="orderedExtensions" name="ordered_extensions"  value="{{ $queue->admin_id }}"/>                                   
                                    </div>
                    
                                    <!-- Queue Type -->
                                    <div class="col-md-6">
                                        <label for="queueType" class="form-label">Queue Type <span class="text-danger">*</span></label>
                                        <select id="queueType" class="form-select" name="queue_type" required>
                                            <option value="">-- Select a queue type --</option>
                                            <option value="sequence" {{ $queue->queue_type == 'sequence' ? 'selected' : '' }}>Sequence</option>
                                            <option value="Priority" {{ $queue->queue_type == 'Priority' ? 'selected' : '' }}>Priority</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-success">
                                    Update
                                </button>
                            </div>
                        </form>
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

        if (select && hiddenInput) {
            const initialValues = hiddenInput.value ? hiddenInput.value.split(',') : [];

            const choices = new Choices(select, {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Select extensions',
                shouldSort: false  // Keeps the custom order
            });

            // Listen for item added
            select.addEventListener('addItem', function (event) {
                const value = event.detail.value;
                if (!initialValues.includes(value)) {
                    initialValues.push(value);
                    hiddenInput.value = initialValues.join(',');
                }
            });

            // Listen for item removed
            select.addEventListener('removeItem', function (event) {
                const value = event.detail.value;
                const index = initialValues.indexOf(value);
                if (index > -1) {
                    initialValues.splice(index, 1);
                    hiddenInput.value = initialValues.join(',');
                }
            });

            // Initial load (re-save values in case Choices.js changes the select)
            hiddenInput.value = initialValues.join(',');
        }
    });
</script>
