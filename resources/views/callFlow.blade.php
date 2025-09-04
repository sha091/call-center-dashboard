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
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Create Flow</li>
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
                    <div class="card-body p-3">                        
                        <form method="POST" action="#" enctype="multipart/form-data" id="submit">
                            @csrf
                            <input type="hidden" value="{{ Session::get('cc_id') }}" id="cc_id">                       
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-gradient bg-transparent">
                                    <div class="form-group">
                                        <label for="layers">No of Inputs</label>
                                        <select class="form-select"  name="inputs" id="inputSelect">
                                            <option value="">Please Select no of inputs</option>
                                            @for ($i = 1; $i <21 ; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>    
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="card-body d-none" id="cardBodyContent">
                                    <div class="row">
                                        <div class="container">
                                            @for ($i = 1; $i <= $no_of_audios; $i++)
                                                <div class="row layer-block" data-layer="{{ $i }}">
                                                    <div class="col-12 my-3">                                                    
                                                        <span class="fw-bold">Layer {{ $i }}</span>
                                                        <hr>
                                                    </div>                                            
                                                    <!-- Wrap all inputs inside one .row for proper grid -->
                                                    <div class="row w-100" id="inputsContainer-{{ $i }}">
                                                        <!-- Static inputs -->
                                                        <div class="col-4">
                                                            <label for="option0">Press 0</label>
                                                            <select class="form-select" name="option0">
                                                                <option value="">Please Select option</option>
                                                                @for ($j = 1; $j <= $no_of_audios; $j++)
                                                                    <option value="layer{{ $j }}">layer {{ $j }}</option>
                                                                @endfor
                                                                @foreach ($queue as $item)
                                                                    <option value="{{ $item->queue_id }}">{{ $item->queue_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                
                                                        <div class="col-4">
                                                            <label for="optionStar">Press *</label>
                                                            <select class="form-select" name="option*">
                                                                <option value="">Please Select option</option>
                                                                @for ($j = 1; $j <= $no_of_audios; $j++)
                                                                    <option value="layer{{ $j }}">layer {{ $j }}</option>
                                                                @endfor
                                                                @foreach ($queue as $item)
                                                                    <option value="{{ $item->queue_id }}">{{ $item->queue_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                
                                                        <div class="col-4">
                                                            <label for="optionHash">Press #</label>
                                                            <select class="form-select" name="option#">
                                                                <option value="">Please Select option</option>
                                                                @for ($j = 1; $j <= $no_of_audios; $j++)
                                                                    <option value="layer{{ $j }}">layer {{ $j }}</option>
                                                                @endfor
                                                                @foreach ($queue as $item)
                                                                    <option value="{{ $item->queue_id }}">{{ $item->queue_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                
                                                        <div class="col-4 mt-3">
                                                            <label for="optionT">Auto Route</label>
                                                            <select class="form-select" name="optiont">
                                                                <option value="">Please Select option</option>
                                                                @for ($j = 1; $j <= $no_of_audios; $j++)
                                                                    <option value="layer{{ $j }}">layer {{ $j }}</option>
                                                                @endfor
                                                                @foreach ($queue as $item)
                                                                    <option value="{{ $item->queue_id }}">{{ $item->queue_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                
                                                        <!-- Dynamic inputs will be appended here -->
                                                    </div>
                                                </div>
                                            @endfor                                                                                        
                                        </div>                                                                                
                                    </div>
                                </div>
                                <div class="card-footer d-none p-2" id="cardFooterContent">
                                    <button type="submit" class="btn btn-primary float-end m-0">Update</button>
                                </div>
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
    $(document).ready(function () {
        $('#submit').on('submit', function (e) {
            e.preventDefault();
    
            let allLayers = {};
    
            $('.layer-block').each(function () {
                let layerId = $(this).data('layer'); // e.g., 1
                let layerKey = 'layer' + layerId;
                let layerOptions = {};
    
                $(this).find('select').each(function () {
                    let name = $(this).attr('name'); // e.g., option0, option*, option#
                    let value = $(this).val();
    
                    // Normalize special characters
                    if (name === 'option*') name = 'optionStar';
                    else if (name === 'option#') name = 'optionHash';
                    else if (name === 'optiont') name = 'optionT';
    
                    layerOptions[name] = value;
                });
    
                allLayers[layerKey] = layerOptions;
            });
            $.ajax({
                url: "{{ route('add-call-flow') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    allLayers: allLayers,
                    cc_id: $('#cc_id').val()                    
                },
                success: function(response) {
                    toastr.success('Call Flow updated successfully!');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    toastr.error('Failed to update call flow'+error);
                }
            });
        });
    });
</script>


<script>
    const totalqueues = @json($queue);
    const existingFlow = @json($existingFlow ?? []);

    $(document).ready(function () {
        const totalLayers = {{ $no_of_audios }};

        // Auto-fill static options on page load
        $('.layer-block').each(function () {
            const layerId = $(this).data('layer');
            const layerKey = 'layer' + layerId;
            const data = existingFlow[layerKey] || {};

            // Static option mapping
            const staticOptions = {
                'option0': 'option0',
                'option*': 'optionStar',
                'option#': 'optionHash',
                'optiont': 'optiont'
            };

            Object.entries(staticOptions).forEach(([selectName, dataKey]) => {
                const selectedVal = data[dataKey] || data[selectName] || '';
                if (selectedVal !== '') {
                    $(`#inputsContainer-${layerId} select[name="${selectName}"]`).val(selectedVal);
                }
            });
        });

        // On number of inputs change
        $('#inputSelect').on('change', function () {
            const numberOfInputs = parseInt($(this).val());

            if ($(this).val() === '') {
                $('#cardBodyContent').addClass('d-none');
                $('#cardFooterContent').addClass('d-none');
            } else {
                $('#cardBodyContent').removeClass('d-none');
                $('#cardFooterContent').removeClass('d-none');
            }

            $('.layer-block').each(function () {
                const layerId = $(this).data('layer');
                const layerKey = 'layer' + layerId;
                const container = $(`#inputsContainer-${layerId}`);
                const layerData = existingFlow[layerKey] || {};

                // Remove previously added dynamic inputs
                container.find('.dynamic-input').remove();

                // Generate dynamic inputs (option1 to optionN)
                for (let i = 1; i <= numberOfInputs; i++) {
                    const optionKey = 'option' + i;
                    const selectedValue = layerData[optionKey] || '';

                    const inputHtml = `
                        <div class="col-4 mt-3 dynamic-input">
                            <label for="${optionKey}">Press ${i}</label>
                            <select class="form-select" name="${optionKey}">
                                <option value="">Please Select option</option>
                                ${generateOptions(totalLayers, totalqueues, selectedValue)}
                            </select>
                        </div>
                    `;
                    container.append(inputHtml);
                }
            });
        });

        // Generate options with selected value
        function generateOptions(layerCount, queues, selectedValue = '') {
            let options = '';

            for (let j = 1; j <= layerCount; j++) {
                const val = 'layer' + j;
                const selected = val === selectedValue ? 'selected' : '';
                options += `<option value="${val}" ${selected}>layer ${j}</option>`;
            }

            queues.forEach(queue => {
                const selected = queue.queue_id == selectedValue ? 'selected' : '';
                options += `<option value="${queue.queue_id}" ${selected}>${queue.queue_name}</option>`;
            });

            return options;
        }

        // OPTIONAL: Trigger change if data exists (auto-render dynamic inputs)
        if (Object.keys(existingFlow).length) {
            $('#inputSelect').trigger('change');
        }
    });
</script>

    
    