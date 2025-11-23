@extends('layouts/contentNavbarLayout')

@section('title', 'Assessor - List')

@section('page-script')
@vite('resources/assets/js/assessor.js')
@endsection

@section('content')
<section class="card">
  <div class="mb-3 navbar-nav-right d-flex align-items-center px-3 mt-3">
    <div class="navbar-nav align-items-start">
      <div class="nav-item d-flex align-items-center">
        <i class="ri-search-line ri-22px me-1_5"></i>
        <input type="search" id="search" class="form-control border-0 shadow-none ps-1 ps-sm-2 ms-50" placeholder="Search assessor name..." aria-label="Search...">
      </div>
    </div>
    <div class="navbar-nav flex-row align-items-center ms-auto gap-5">
      <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#AddAssessor">
        <span class="tf-icons ri-add-circle-line ri-16px me-1_5"></span>Add
      </button>
    </div>
  </div>
  <div class="table-responsive text-nowrap overflow-auto" style="max-height: 500px;">
    <table class="table table-hover">
      <thead class="position-sticky top-0 bg-body">
        <tr>
          <th>Name</th>
          <th>Address</th>
          <th>Phone</th>
          <th>Position</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0" id="assessorlist">
      </tbody>
    </table>
  </div>
</section>
{{-- Add Modal --}}
<div class="modal fade" id="AddAssessor" tabindex="-1" aria-hidden="true">
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

          <form id="PropertyData" class="mb-5">
            @csrf
            <div class="form-floating form-floating-outline mb-5">
              <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter firstname..." autofocus>
              <label for="firstname">First Name</label>
            </div>
            <div class="form-floating form-floating-outline mb-5">
              <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Enter middlename..." autofocus>
              <label for="middlename">Middle Name</label>
            </div>
            <div class="form-floating form-floating-outline mb-5">
              <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter lastname..." autofocus>
              <label for="lastname">Last Name</label>
            </div>
            <div class="form-floating form-floating-outline mb-5">
              <input type="number" max="11" class="form-control" id="phone" name="phone" placeholder="Enter phone number..." autofocus>
              <label for="phone">Phone Number</label>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <select class="form-select" id="role" name="role" aria-label="Default select example">
                <option value="" selected disabled>Select Position</option>
                <option value="Assessor">Assessor</option>
                <option value="OIC Municilap Assessor">OIC Municilap Assessor</option>
                <option value="Technical Supervisor">Technical Supervisor</option>
                <option value="Assessment Clerk 1">Assessment Clerk 1</option>
              </select>
              <label for="role">Role</label>
            </div>

            <div class="form-floating form-floating-outline mb-5">
              <input type="text" class="form-control" id="address" name="address" placeholder="Enter property address..." autofocus>
              <label for="address">Address</label>
            </div>


          </form>
        </div>
        <div>
          <button type="button" class="btn btn-primary d-grid w-100 mb-5" id="AddAssessorBtn">Submit</button>
      </div>
      </div>
    </div>
  </div>
</div>
{{-- Edit Modal --}}
<div class="modal fade" id="EditAssessor" tabindex="-1" aria-hidden="true">
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

          <form id="PropertyDataUpdate" class="mb-5">
            @csrf
            <div class="form-floating form-floating-outline mb-5">
              <input type="text" class="form-control" id="Edit_encrypted_id" name="encrypted_id" placeholder="Enter encrypted_id..." hidden>
              <input type="text" class="form-control" id="Edit_firstname" name="firstname" placeholder="Enter firstname..." autofocus>
              <label for="Edit_firstname">First Name</label>
            </div>
            <div class="form-floating form-floating-outline mb-5">
              <input type="text" class="form-control" id="Edit_middlename" name="middlename" placeholder="Enter middlename..." autofocus>
              <label for="Edit_middlename">Middle Name</label>
            </div>
            <div class="form-floating form-floating-outline mb-5">
              <input type="text" class="form-control" id="Edit_lastname" name="lastname" placeholder="Enter lastname..." autofocus>
              <label for="Edit_lastname">Last Name</label>
            </div>
            <div class="form-floating form-floating-outline mb-5">
              <input type="number" max="11" class="form-control" id="Edit_phone" name="phone" placeholder="Enter phone number..." autofocus>
              <label for="Edit_phone">Phone Number</label>
            </div>
            <div class="form-floating form-floating-outline mb-4">
              <select class="form-select" id="Edit_role" name="role" aria-label="Default select example">
                <option value="" selected disabled>Select Position</option>
                <option value="Assessor">Assessor</option>
                <option value="OIC Municilap Assessor">OIC Municilap Assessor</option>
                <option value="Technical Supervisor">Technical Supervisor</option>
                <option value="Assessment Clerk 1">Assessment Clerk 1</option>
              </select>
              <label for="role">Role</label>
            </div>
            <div class="form-floating form-floating-outline mb-5">
              <input type="text" class="form-control" id="Edit_address" name="address" placeholder="Enter property address..." autofocus>
              <label for="Edit_address">Address</label>
            </div>

          </form>
        </div>
        <div>
          <button type="button" class="btn btn-primary d-grid w-100 mb-5" id="UpdateAssessorBtn">Submit</button>
      </div>
      </div>
    </div>
  </div>
</div>
@php
  $assessors = collect($assessors)->map(function($assessor) {
  return [
      'encrypted_id' => Crypt::encryptString($assessor->id),
      'firstname' => $assessor->firstname,
      'middlename' => $assessor->middlename,
      'lastname' => $assessor->lastname,
      'address' => $assessor->address,
      'phone' => $assessor->phone,
      'status' => $assessor->status,
      'created_at' => $assessor->created_at->format('M d, Y'),
      'role' => $assessor->role,
  ];
  })->values()->toArray();
@endphp
<script>
  window.assessors = @json($assessors);
</script>
@endsection
