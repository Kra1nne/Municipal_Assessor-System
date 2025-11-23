@extends('layouts/contentNavbarLayout')

@section('title', 'Land Property - List')

@section('page-script')
@vite('resources/assets/js/landproperty.js')
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
    <div class="navbar-nav flex-row align-items-center ms-auto gap-5">
      <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#AddRequestModal">
        <span class="tf-icons ri-add-circle-line ri-16px me-1_5"></span>Request
      </button>
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
<div class="modal fade" id="AddRequestModal" tabindex="-1" aria-hidden="true">
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
        <!-- /Logo -->
        <div class="card-body mt-5">

          <form id="ProductData" enctype="multipart/form-data" class="mb-5">
            @csrf

             <div class="form-group mb-5">
              <label for="">Request form for Updated Tax Declaration</label>
              <input type="file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf" class="form-control mt-3 " id="request_form" name="request_form" autofocus>
            </div>
             <div class="form-group mb-5">
              <label for="">Transfer Certificate of Title/Original Certificate of Title</label>
              <input type="file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf" class="form-control mt-3" id="certificate" name="certificate" autofocus>
            </div>
             <div class="form-group mb-5">
              <label for="">Deed of Sale or any proof of transfer</label>
              <input type="file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf" class="form-control mt-3" id="proff_of_transfer" name="proff_of_transfer" autofocus>
            </div>
             <div class="form-group mb-5">
              <label for="">Certificate Authorizing Registration</label>
              <input type="file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf" class="form-control mt-3" id="authorizing" name="authorizing" autofocus>
            </div>
            <div class="form-group mb-5">
              <label for="">Updated Real Real Property Tax Payment</label>
              <input type="file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf" class="form-control mt-3" id="updated_tax" name="updated_tax" autofocus>
            </div>
             <div class="form-group mb-5">
              <label for="">Transfer Tax Receipt</label>
              <input type="file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf"  class="form-control mt-3" id="transfer_tax" name="transfer_tax" autofocus>
            </div>
             <div class="form-group mb-5">
              <label for="">Latest Tax Declaration</label>
              <input type="file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf" class="form-control mt-3" id="tax_reciept" name="tax_reciept" autofocus>
            </div>
          </form>
        </div>
        <div>
          <button type="button" class="btn btn-primary d-grid w-100 mb-5" id="AddRequest">Submit</button>
      </div>
      </div>
    </div>
  </div>
</div>
<script>
  window.request = @json($request);
</script>
@endsection
