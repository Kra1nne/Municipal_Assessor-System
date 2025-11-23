@extends('layouts/contentNavbarLayout')

@section('title', 'Request - List')

@section('page-script')
@vite('resources/assets/js/request.js')
@endsection

@section('content')
<header class="row mb-5">
  <div class="col-xl-3 col-md-6 mb-5">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-5 fw-bolder">Total Request</p>
          <h3 class="fw-bold mb-5" id="totalRequest"></h3>
          <small class="text-primary">All request records</small>
        </div>
        <div class="text-primary fs-2">
          <i class="ri-file-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-5">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-5 fw-bolder">Request Complete</p>
          <h3 class="fw-bold mb-5" id="completeRequest"></h3>
          <small class="text-success">Request completed</small>
        </div>
        <div class="text-success fs-2">
          <i class="ri-checkbox-circle-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-5">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-5 fw-bolder">Under Review Request</p>
          <h3 class="fw-bold mb-5" id="reviewRequest"></h3>
          <small class="text-muted">Under review request</small>
        </div>
        <div class="text-muted fs-2">
          <i class="ri-error-warning-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-5">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-5 fw-bolder">Decline Request</p>
          <h3 class="fw-bold text-danger mb-5" id="declineRequest"></h3>
          <small class="text-muted">Decline Request</small>
        </div>
        <div class="text-success fs-2">
          <i class="ri-close-circle-line text-danger" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>
</header>
<section class="card">
  <div class="mb-5 navbar-nav-right d-flex align-items-center px-3 mt-3">
    <div class="navbar-nav align-items-start">
      <div class="nav-item d-flex align-items-center">
        <i class="ri-search-line ri-22px me-1_5"></i>
        <input type="search" id="search" class="form-control mt-3 border-0 shadow-none ps-1 ps-sm-2 ms-50" placeholder="Search name..." aria-label="Search...">
      </div>
    </div>
  </div>
  <div class="table-responsive text-nowrap overflow-auto" style="max-height: 500px;">
    <table class="table table-hover">
      <thead class="position-sticky top-0 bg-body">
        <tr>
          <th>Name</th>
          <th>Status</th>
          <th>Description</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0" id="requestlist">
      </tbody>
    </table>
  </div>
</section>
<div class="modal fade" id="ViewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header text-white">
        <h5 class="modal-title">
          <i class="bx bx-file"></i> View and Download Uploaded Documents
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="text-center mb-4">
          <h6 class="fw-bold">Uploaded Files</h6>
          <p class="text-muted mb-0">You can download each document below.</p>
        </div>

        <!-- Download list -->
        <div id="download-section" class="row g-3"></div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          <i class="bx bx-x"></i> Close
        </button>
      </div>

    </div>
  </div>
</div>
<div class="modal fade" id="ViewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header text-white">
        <h5 class="modal-title">
          <i class="bx bx-file"></i> View and Download Uploaded Documents
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="text-center mb-4">
          <h6 class="fw-bold">Uploaded Files</h6>
          <p class="text-muted mb-0">You can download each document below.</p>
        </div>

        <!-- Download list -->
        <div id="download-section" class="row g-3"></div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          <i class="bx bx-x"></i> Close
        </button>
      </div>

    </div>
  </div>
</div>
<div class="modal fade" id="AddAccept" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="app-brand justify-content-center mt-5">
          <a href="{{url('/')}}" class="app-brand-link gap-3">
            <span class="app-brand-logo demo">@include('_partials.macros',["height"=>20])</span>
            <span class="app-brand-text demo text-heading fw-semibold">{{ config('variables.templateName') }}</span>
          </a>
        </div>

        <div class="card-body mt-5">

          <form id="Data" class="mb-5">
            @csrf
            <div class="navbar-nav align-items-start mb-5">
              <div class="nav-item d-flex align-items-center">
                <i class="ri-search-line ri-22px me-1_5"></i>
                <input type="search" id="searchID" class="form-control border-0 shadow-none ps-1 ps-sm-2 ms-50" placeholder="Search land number..." aria-label="Search...">
              </div>
            </div>
            <div class="form-floating form-floating-outline mb-5">
              <select class="form-select" id="lot" name="lot" aria-label="Default select example">
                <option value="" selected disabled>Select Property</option>
                  @foreach ($property as $item)
                    <option value="{{ $item->id }}">{{ $item->lot_number }}</option>
                  @endforeach
              </select>
              <label for="exampleFormControlSelect1">Property Type</label>
              <div>
                <input type="hidden" id="id" name="id">
              </div>
            </div>
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="owner" name="owner" placeholder="Enter property owner" autofocus>
              <label for="owner">Owner</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="address" name="address" placeholder="Enter address owner" autofocus>
              <label for="owner">Address</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="tin" name="tin" placeholder="Enter Tin number" autofocus>
              <label for="owner">TIN Number</label>
            </div>
          </form>
        </div>
        <div>
          <button type="button" class="btn btn-primary d-grid w-100 mb-5" id="AddLot">Submit</button>
      </div>
      </div>
    </div>
  </div>
</div>
<script>
  window.request = @json($request);
</script>
<script>
$('#searchID').on('input', function () {
    let search = $(this).val().toLowerCase();

    if (search === "") {
        $('#lot').val("");
        $('#id').val("");
        return;
    }
    $('#lot option').each(function () {
        if ($(this).text().toLowerCase().includes(search)) {
            $('#lot').val($(this).val());
            $('#id').val($(this).val());
            return false;
        }
    });
});
</script>


@endsection
