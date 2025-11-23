@extends('layouts/contentNavbarLayout')

@section('title', 'Building Assessment - List')

@section('page-script')
@vite('resources/assets/js/buildingassessment.js')
@endsection

@section('content')
<header class="row mb-3">
  <div class="col-xl-3 col-md-6 mb-3">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-3 fw-bolder">Total Building Assessement</p>
          <h3 class="fw-bold mb-3" id="totalAssessment">{{ $assessmentCount }}</h3>
          <small class="text-primary">All building assessment records</small>
        </div>
        <div class="text-primary fs-2">
          <i class="ri-file-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-3">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-3 fw-bolder">Building Assessement Complete</p>
          <h3 class="fw-bold mb-3" id="completeAssessment">{{ $complete }}</h3>
          <small class="text-success">Assessments completed</small>
        </div>
        <div class="text-success fs-2">
          <i class="ri-checkbox-circle-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-3">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-3 fw-bolder">Under Review Building Assessment</p>
          <h3 class="fw-bold mb-3" id="reviewAssessment">{{ $pending }}</h3>
          <small class="text-muted">Under review assessments</small>
        </div>
        <div class="text-muted fs-2">
          <i class="ri-error-warning-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-3">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-3 fw-bolder">Total Building Value</p>
          <h3 class="fw-bold text-success mb-3" id="totalValue"></h3>
          <small class="text-muted">Total assessed building value</small>
        </div>
        <div class="text-success fs-2">
          <i class="ri-money-dollar-box-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>
</header>
<section class="card">
  <div class="mb-3 navbar-nav-right d-flex align-items-center px-3 mt-3">
    <div class="navbar-nav align-items-start">
      <div class="nav-item d-flex align-items-center">
        <i class="ri-search-line ri-22px me-1_5"></i>
        <input type="search" id="search" class="form-control border-0 shadow-none ps-1 ps-sm-2 ms-50" placeholder="Search land number..." aria-label="Search...">
      </div>
    </div>
    <div class="navbar-nav flex-row align-items-center ms-auto gap-5">
      <select class="form-select btn btn-outline-primary" id="statusFilter" style="width: auto; min-width: 150px; appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: none; padding-right: 0.75rem;">
        <option value="">All Status</option>
        <option value="Complete">Complete</option>
        <option value="Under Review">Under Review</option>
      </select>
    </div>
  </div>
  <div class="table-responsive text-nowrap overflow-auto" style="max-height: 500px;">
    <table class="table table-hover">
      <thead class="position-sticky top-0 bg-body">
        <tr>
          <th>Parcel ID</th>
          <th>Land Property</th>
          <th>Number of Storey</th>
          <th>Building Type</th>
          <th>Assessed Value</th>
          <th>Construction Cost</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0" id="propertylist">
      </tbody>
    </table>
  </div>
</section>
<div class="modal fade" id="AddModal" tabindex="-1" aria-hidden="true">
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
          <form id="PropertyData" class="mb-3">
            @csrf
            <input type="hidden" id="assessment_id" name="assessment_id">
            <div class="form-floating form-floating-outline mb-4">
              <select class="form-select" id="type" name="type" aria-label="Default select example">
                <option value="" selected disabled>Select Land Classification</option>
                @foreach($classification as $item)
                  <option value="{{ $item->id }}">{{ $item->classification }} - {{ $item->percentage }}%</option>
                @endforeach
              </select>
              <label for="exampleFormControlSelect1">Property Type</label>
            </div>

            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" name="totalcost" id="totalcost" placeholder="Enter building total cost">
              <label for="totalcost">Total Cost</label>
            </div>

            <div class="form-floating form-floating-outline mb-3">
              <input type="date" class="form-control" id="complete" name="complete">
              <label for="complete">Construction Complete</label>
            </div>

            <!-- Number of storeys input -->
            <div class="form-floating form-floating-outline mb-3">
              <input type="number" class="form-control" id="storey" name="storey" placeholder="Enter number of storeys">
              <label for="storey">Number of Storeys</label>
            </div>

            <!-- Container where dynamic inputs will appear -->
            <div id="storeyInputsContainer"></div>

            <div class="form-floating form-floating-outline mb-4">
              <select class="form-select" id="roof" name="roof" aria-label="Default select example">
                <option value="" selected disabled>Roof Materials</option>
                <option value="Reinforced Concrete">Reinforced Concrete</option>
                <option value="Tiles">Tiles</option>
                <option value="G.I. Sheet">G.I. Sheet</option>
                <option value="Aluminum">Aluminum</option>
                <option value="Asbestos">Asbestos</option>
                <option value="Long Span">Long Span</option>
                <option value="Concrete Deck">Concrete Deck</option>
                <option value="Nipa/Anahaw/Cogon">Nipa/Anahaw/Cogon</option>
                <option value="Color Roof">Color Roof</option>
                <option value="Others">Others</option>
              </select>
              <label for="roof">Roof</label>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <select class="form-select" id="flooring" name="flooring" aria-label="Default select example">
                <option value="" selected disabled>Flooring Materials</option>
                <option value="Reinforced Concrete">Reinforced Concrete</option>
                <option value="Plain Cement">Plain Cement</option>
                <option value="Marble">Marble</option>
                <option value="Wood">Wood</option>
                <option value="Tiles">Tiles</option>
                <option value="Others">Others</option>
              </select>
              <label for="flooring">Flooring</label>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <select class="form-select" id="walls" name="walls" aria-label="Default select example">
                <option value="" selected disabled>Walls Materials</option>
                <option value="Reinforced Concrete">Reinforced Concrete</option>
                <option value="Plain Cement">Plain Cement</option>
                <option value="Wood">Wood</option>
                <option value="CHB">CHB</option>
                <option value="G.I. Sheet">G.I. Sheet</option>
                <option value="Build-a-wall">Build-a-wall</option>
                <option value="Sawali">Sawali</option>
                <option value="Bamboo">Bamboo</option>
                <option value="Others">Others</option>
              </select>
              <label for="walls">Walls</label>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <select class="form-select" id="structural" name="structural">
                <option value="" selected disabled>Structural Type</option>
                <option value="A-I">A-I</option>
                <option value="B-I">B-I</option>
                <option value="C-I">C-I</option>
                <option value="A-II">A-II</option>
                <option value="B-II">B-II</option>
                <option value="C-II">C-II</option>
                <option value="A-III">A-III</option>
                <option value="B-III">B-III</option>
                <option value="C-III">C-III</option>
              </select>
              <label for="Structural Type">Structural Type</label>
            </div>
            <input type="hidden" name="storey_size" id="storey_size">

          </form>
        </div>

        <div>
          <button type="button" class="btn btn-primary d-grid w-100 mb-3" id="BuildingAssessment">Submit</button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="UpdateModal" tabindex="-1" aria-hidden="true">
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
          <form id="PropertyDataUpdate" class="mb-3">
            @csrf
            <input type="hidden" id="building_id" name="building_id">
            <div class="form-floating form-floating-outline mb-4">
              <select class="form-select" id="Edit_type" name="type" aria-label="Default select example">
                <option value="" selected disabled>Select Land Classification</option>
                @foreach($classification as $item)
                  <option value="{{ $item->id }}">{{ $item->classification }} - {{ $item->percentage }}%</option>
                @endforeach
              </select>
              <label for="exampleFormControlSelect1">Property Type</label>
            </div>

            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" name="totalcost" id="Edit_totalcost" placeholder="Enter building total cost">
              <label for="totalcost">Total Cost</label>
            </div>

            <div class="form-floating form-floating-outline mb-3">
              <input type="date" class="form-control" id="Edit_complete" name="complete">
              <label for="complete">Construction Complete</label>
            </div>

            <!-- Number of storeys input -->
            <div class="form-floating form-floating-outline mb-3">
              <input type="number" class="form-control" id="Edit_storey" name="storey" placeholder="Enter number of storeys">
              <label for="storey">Number of Storeys</label>
            </div>

            <!-- Container where dynamic inputs will appear -->
            <div id="Edit_storeyInputsContainer"></div>

            <div class="form-floating form-floating-outline mb-4">
              <select class="form-select" id="Edit_roof" name="roof" aria-label="Default select example">
                <option value="" selected disabled>Roof Materials</option>
                <option value="Reinforced Concrete">Reinforced Concrete</option>
                <option value="Tiles">Tiles</option>
                <option value="G.I. Sheet">G.I. Sheet</option>
                <option value="Aluminum">Aluminum</option>
                <option value="Asbestos">Asbestos</option>
                <option value="Long Span">Long Span</option>
                <option value="Concrete Deck">Concrete Deck</option>
                <option value="Nipa/Anahaw/Cogon">Nipa/Anahaw/Cogon</option>
                <option value="Color Roof">Color Roof</option>
                <option value="Others">Others</option>
              </select>
              <label for="roof">Roof</label>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <select class="form-select" id="Edit_flooring" name="flooring" aria-label="Default select example">
                <option value="" selected disabled>Flooring Materials</option>
                <option value="Reinforced Concrete">Reinforced Concrete</option>
                <option value="Plain Cement">Plain Cement</option>
                <option value="Marble">Marble</option>
                <option value="Wood">Wood</option>
                <option value="Tiles">Tiles</option>
                <option value="Others">Others</option>
              </select>
              <label for="flooring">Flooring</label>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <select class="form-select" id="Edit_walls" name="walls" aria-label="Default select example">
                <option value="" selected disabled>Walls Materials</option>
                <option value="Reinforced Concrete">Reinforced Concrete</option>
                <option value="Plain Cement">Plain Cement</option>
                <option value="Wood">Wood</option>
                <option value="CHB">CHB</option>
                <option value="G.I. Sheet">G.I. Sheet</option>
                <option value="Build-a-wall">Build-a-wall</option>
                <option value="Sawali">Sawali</option>
                <option value="Bamboo">Bamboo</option>
                <option value="Others">Others</option>
              </select>
              <label for="walls">Walls</label>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <select class="form-select" id="Edit_structural" name="structural">
                <option value="" selected disabled>Structural Type</option>
                <option value="A-I">A-I</option>
                <option value="B-I">B-I</option>
                <option value="C-I">C-I</option>
                <option value="A-II">A-II</option>
                <option value="B-II">B-II</option>
                <option value="C-II">C-II</option>
                <option value="A-III">A-III</option>
                <option value="B-III">B-III</option>
                <option value="C-III">C-III</option>
              </select>
              <label for="Structural Type">Structural Type</label>
            </div>
            <input type="hidden" name="storey_size" id="Edit_storey_size">

          </form>
        </div>

        <div>
          <button type="button" class="btn btn-primary d-grid w-100 mb-3" id="BuildingAssessmentUpdate">Submit</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
