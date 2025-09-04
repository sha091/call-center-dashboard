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
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Sound Settings</li>
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
                        <form method="POST" action="{{ route('upload-sound-setting') }}" enctype="multipart/form-data" id="audioForm">
                            @csrf
                            <input type="hidden" value="{{ Session::get('cc_id') }}" id="cc_id">                       
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-gradient bg-transparent">
                                    <div class="form-group">
                                        <label for="layers">Layers</label>
                                        <select class="form-select"  name="layers" id="layerSelect">
                                            <option value="">Please Select Layer</option>
                                            @for ($i = 1; $i <= 20; $i++)
                                                <option value="{{ $i }}">Layer-{{ $i }}</option>    
                                            @endfor
                                            <option value="ivr">off-time</option>                                                
                                        </select>
                                    </div>
                                </div>
                                <div class="card-body d-none" id="cardBodyContent">
                                    <!-- Table-like header -->
                                    <div class="row fw-semibold border-bottom pb-2 mb-3 text-center small text-secondary">
                                        <div class="col-md-4">Audio Name</div>
                                        <div class="col-md-4">Upload</div>
                                        <div class="col-md-4">Listen</div>                                        
                                    </div>
                        
                                    <!-- Row Entry -->
                                    <div id="audioRows">
                                        {{-- <div class="row align-items-center mb-1 text-center">
                                            <!-- Audio Name -->
                                            <div class="col-md-3">
                                                <span class="text-dark">${audioName}</span>
                                            </div>
                
                                            <!-- Upload Field -->
                                            <div class="col-md-3">
                                                <input type="file" class="form-control form-control-sm" name="audio-file[]" accept="audio/*">
                                            </div>
                
                                            <!-- Audio Player -->
                                            <div class="col-md-3">
                                                <audio controls class="w-100" style="height: 28px;">
                                                    <source src="#" type="audio/mpeg">
                                                </audio>
                                            </div>
                
                                            <!-- Actions -->
                                            <div class="col-md-3 d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-outline-success btn-sm d-flex align-items-center gap-1 ">
                                                    <span class="material-icons" style="font-size: 15px;">edit</span> Edit
                                                </button>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="card-footer d-none p-2" id="cardFooterContent">
                                    <button type="submit" class="btn btn-primary float-end m-0">Upload</button>
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
        $('#layerSelect').on('change', function () {
            const no_of_layer = $(this).val();
            const cc_id = $('#cc_id').val();
            $('#audioRows').empty();
            if ($(this).val() === '') {
                $('#cardBodyContent').addClass('d-none');
                $('#cardFooterContent').addClass('d-none');
            } else {                
                var i = 0;
                var j = 1;                
                var audio_array = ['welcome'];                
                for(i = 0; i<=no_of_layer; i++){
                    if(i > 1){ 
                        audio_array.push('mainmenu' + (j));
                        j++;                                                
                    }
                }
                if(no_of_layer == "ivr"){
                    audio_array = ['ivr'];
                    $('#audioForm').attr('action', "{{ route('upload-off-time-sound') }}");
                }else{
                    $('#audioForm').attr('action', "{{ route('upload-sound-setting') }}");
                }
                $.each(audio_array, function (index, audioName) {
                    var rowHtml = `
                        <div class="row align-items-center mb-1 text-center">
                            <!-- Audio Name -->
                            <div class="col-md-4">
                                <span class="text-dark">${audioName}</span>
                            </div>

                            <!-- Upload Field -->
                            <div class="col-md-4">
                                <input type="file" class="form-control form-control-sm" name="${audioName}" accept="audio/wav">
                            </div>

                            <!-- Audio Player -->
                            <div class="col-md-4">
                                <audio controls class="w-100" style="height: 28px;">
                                    <source src="{{ asset('storage/app/public/prompts/685aa8cfe14a3/${audioName}.wav') }}" type="audio/wav">
                                </audio>
                            </div>

                        </div>
                    `;
                    $('#audioRows').append(rowHtml);
                });
                $('#cardBodyContent').removeClass('d-none');
                $('#cardFooterContent').removeClass('d-none');
            }
        });
    });
</script>