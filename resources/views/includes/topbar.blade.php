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
                      <div class="col-xl-3 col-sm-3 mb-xl-0 mb-4">
                          <div class="card-body p-3 pt-2">
                              <nav aria-label="breadcrumb">
                                <button class="btn btn-sm btn-transparent dropdown-toggle text-white m-0 p-0" id='TimeStatus' data-bs-toggle="dropdown" aria-expanded="false">
                                  <i class="fa-solid fa-clock px-2" aria-hidden="true"></i>
                                  @if ( Session::get('TimeStatus'))
                                      {{ Session::get('TimeStatus') }}
                                  @else
                                      Online
                                  @endif


                                </button>
                                <ul class="dropdown-menu">
                                  <li><a class="dropdown-item d-item" href="#">Online</a></li>
                                  <li><a class="dropdown-item d-item" href="#">Namaz Break</a></li>
                                  <li><a class="dropdown-item d-item" href="#">Lunch Break</a></li>
                                  <li><a class="dropdown-item d-item" href="#">Tea Break</a></li>
                                  {{-- <li><a class="dropdown-item d-item" href="#">Auxiliary Time</a></li>
                                  <li><a class="dropdown-item d-item" href="#">Assignment</a></li> --}}
                                  <li><a class="dropdown-item d-item" href="#">Campaign</a></li>
                                </ul>
                              </nav>
                          </div>
                      </div>
                      <div class="col-xl-5 col-sm-5 mb-xl-0 mb-4">
                          <div class="card-body p-3 pt-2 text-center pr-2">
                              <nav aria-label="breadcrumb">
                                  <h6 class="font-weight-bolder mb-0 text-white text-md" id='call_center_status'>Call Center Is</h6>
                              </nav>
                          </div>
                      </div>
                      <div class="col-xl-4 col-sm-4 mb-xl-0 mb-4">
                          <div class="card-body p-3 pt-2 float-end">
                              <nav aria-label="breadcrumb" id='agent_status'>
                                  <h6 class="font-weight-bolder mb-0 text-white text-md" id='agent_status'><i class="fa fa-phone"></i> Agent is Free</h6>
                              </nav>
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
                                          $workCodes = DB::table('cc_workcodes_new')->where('status',1)->where('cc_id',auth()->user()->cc_id)->get();
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

