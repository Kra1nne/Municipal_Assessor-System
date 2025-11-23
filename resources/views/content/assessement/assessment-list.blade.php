@extends('layouts/contentNavbarLayout')

@section('title', 'Land Assessment - List')

@section('page-script')
@vite('resources/assets/js/assessment.js')
@endsection

@section('content')
<header class="row mb-3">
  <div class="col-xl-3 col-md-6 mb-3">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-3 fw-bolder">Total Assessement</p>
          <h3 class="fw-bold mb-3" id="totalAssessment">{{ $countall }}</h3>
          <small class="text-primary">All land assessment records</small>
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
          <p class="text-muted mb-3 fw-bolder">Assessement Complete</p>
          <h3 class="fw-bold mb-3" id="completeAssessment">{{ $countReview }}</h3>
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
          <p class="text-muted mb-3 fw-bolder">Under Review Assessment</p>
          <h3 class="fw-bold mb-3" id="reviewAssessment">{{ $countComplete }}</h3>
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
          <p class="text-muted mb-3 fw-bolder">Total Land Value</p>
          <h3 class="fw-bold text-success mb-3" id="totalValue">{{ $total }}</h3>
          <small class="text-muted">Total assessed land value</small>
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
          <th>Assessor</th>
          <th>Area</th>
          <th>Assessed Value</th>
          <th>Actual Use</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0" id="propertylist">
      </tbody>
    </table>
  </div>
</section>
<div class="modal fade" id="assessmentModal" tabindex="-1" aria-hidden="true">
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

          <form id="ProperyData" class="mb-3">
            @csrf
            <div class="form-floating form-floating-outline mb-3">
              <input type="hidden" name="properties_id" id="properties_id">
              <select class="form-select" id="assessor_id" name="assessor_id" aria-label="Default select example">
                <option value="" selected disabled>Select Assessor</option>
                @foreach($assessor as $assessorItem)
                <option value="{{ $assessorItem->id }}">{{ $assessorItem->firstname }} {{ $assessorItem->middlename ?? ""}} {{ $assessorItem->lastname }}</option>
                @endforeach
              </select>
              <label for="assessor">Assessor Name</label>
            </div>

            <div class="form-floating form-floating-outline mb-3">
              <select class="form-select" id="market_id" name="market_id" aria-label="Default select example">
                <option value="" selected disabled>Schedule Market Value</option>
                @foreach($marketValue as $marketValueItem)
                <option value="{{ $marketValueItem->id }}">{{ $marketValueItem->type_name }} - Class {{ $marketValueItem->class }}</option>
                @endforeach
              </select>
              <label for="market value">Market Value</label>
            </div>

            <div class="form-floating form-floating-outline mb-3">
              <select class="form-select" id="property_id" name="property_id" aria-label="Default select example">
                <option value="" selected disabled>Select Property Type</option>
                @foreach($actualUse as $actualUseItem)
                <option value="{{ $actualUseItem->id }}">{{ $actualUseItem->type_name }} {{ $actualUseItem->assessment_rate}}%</option>
                @endforeach
              </select>
              <label for="actual use">Actual Use</label>
            </div>

             <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="sub_classification" name="sub_classification" placeholder="Enter Sub Classification" autofocus>
              <label for="sub_classification">Sub Classification</label>
            </div>

            <div class="form-floating form-floating-outline mb-3">
              <input type="date" class="form-control" id="date" name="date" placeholder="Enter date" autofocus>
              <label for="date">Date</label>
            </div>

             <div>
              <label  for="flexCheckDefault">
                Type of road
              </label>
            </div>
            <div class="row m-3">
              <div class="col-12 col-md-6">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="outlet-road" name="outlet_road" value="1">
                  <label class="form-check-label" for="flexCheckDefault">
                    No road outlet
                  </label>
                </div>
              </div>

              <div class="col-12 col-md-6">
                 <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="dirt-road" name="dirt_road" value="1">
                  <label class="form-check-label" for="flexCheckChecked">
                    Along dirt road
                  </label>
                </div>
              </div>
            </div>

            <div class="row m-3">
              <div class="col-12 col-md-6">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="weather-road" name="weather_road" value="1">
                  <label class="form-check-label" for="flexCheckDefault">
                    Along weather road
                  </label>
                </div>
              </div>

              <div class="col-12 col-md-6">
                 <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="provincial-road" name="provincial_road" value="1">
                  <label class="form-check-label" for="flexCheckChecked">
                    Along national and provincial road
                  </label>
                </div>
              </div>
            </div>

             <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="arp_no" name="arp_no" placeholder="Enter arp number" autofocus>
              <label for="arp_no">APR_No</label>
            </div>

             <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="pin" name="pin" placeholder="Enter pin" autofocus>
              <label for="pin">PIN</label>
            </div>

             <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="survey_no" name="survey_no" placeholder="Enter survey number" autofocus>
              <label for="survey_no">Survey Number</label>
            </div>

            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="otc" name="otc" placeholder="Enter oct/tct/cloa" autofocus>
              <label for="oct/tct/cloa">OCT/TCT/CLOA</label>
            </div>

            <div class="form-floating form-floating-outline mb-3">
              <select class="form-select" id="taxable" name="taxable" aria-label="Default select example">
                <option value="" selected disabled>Select</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
              </select>
              <label for="taxable">Taxable</label>
            </div>

          </form>
        </div>
        <div>
          <button type="button" class="btn btn-primary d-grid w-100 mb-3" id="AssessmentBtn">Submit</button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="EditassessmentModal" tabindex="-1" aria-hidden="true">
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

          <form id="ProperyDataUpdate" class="mb-3">
            @csrf
            <input type="hidden" name="assessment_id" id="Edit_assessment_id">
            <div class="form-floating form-floating-outline mb-3">
              <select class="form-select" id="Edit_assessor_id" name="assessor_id" aria-label="Default select example">
                <option value="" selected disabled>Select Assessor</option>
                @foreach($assessor as $assessorItem)
                <option value="{{ $assessorItem->id }}">{{ $assessorItem->firstname }} {{ $assessorItem->middlename ?? ""}} {{ $assessorItem->lastname }}</option>
                @endforeach
              </select>
              <label for="assessor">Assessor Name</label>
            </div>

            <div class="form-floating form-floating-outline mb-3">
              <select class="form-select" id="Edit_market_id" name="market_id" aria-label="Default select example">
                <option value="" selected disabled>Schedule Market Value</option>
                @foreach($marketValue as $marketValueItem)
                <option value="{{ $marketValueItem->id }}">{{ $marketValueItem->type_name }} - Class {{ $marketValueItem->class }}</option>
                @endforeach
              </select>
              <label for="market value">Market Value</label>
            </div>

            <div class="form-floating form-floating-outline mb-3">
              <select class="form-select" id="Edit_property_id" name="property_id" aria-label="Default select example">
                <option value="" selected disabled>Select Property Type</option>
                @foreach($actualUse as $actualUseItem)
                <option value="{{ $actualUseItem->id }}">{{ $actualUseItem->type_name }} {{ $actualUseItem->assessment_rate}}%</option>
                @endforeach
              </select>
              <label for="actual use">Actual Use</label>
            </div>

             <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="Edit_sub_classification" name="sub_classification" placeholder="Enter Sub Classification" autofocus>
              <label for="sub_classification">Sub Classification</label>
            </div>

            <div class="form-floating form-floating-outline mb-3">
              <input type="date" class="form-control" id="Edit_date" name="date" placeholder="Enter date" autofocus>
              <label for="date">Date</label>
            </div>

             <div>
              <label  for="flexCheckDefault">
                Type of road
              </label>
            </div>
            <div class="row m-3">
              <div class="col-12 col-md-6">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="Edit_outlet-road" name="outlet_road" value="1">
                  <label class="form-check-label" for="flexCheckDefault">
                    No road outlet
                  </label>
                </div>
              </div>

              <div class="col-12 col-md-6">
                 <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="Edit_dirt-road" name="dirt_road" value="1">
                  <label class="form-check-label" for="flexCheckChecked">
                    Along dirt road
                  </label>
                </div>
              </div>
            </div>

            <div class="row m-3">
              <div class="col-12 col-md-6">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="Edit_weather-road" name="weather_road" value="1">
                  <label class="form-check-label" for="flexCheckDefault">
                    Along weather road
                  </label>
                </div>
              </div>

              <div class="col-12 col-md-6">
                 <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="Edit_provincial-road" name="provincial_road" value="1">
                  <label class="form-check-label" for="flexCheckChecked">
                    Along national and provincial road
                  </label>
                </div>
              </div>
            </div>

             <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="Edit_arp_no" name="arp_no" placeholder="Enter arp number" autofocus>
              <label for="arp_no">APR_No</label>
            </div>

             <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="Edit_pin" name="pin" placeholder="Enter pin" autofocus>
              <label for="pin">PIN</label>
            </div>

             <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="Edit_survey_no" name="survey_no" placeholder="Enter survey number" autofocus>
              <label for="survey_no">Survey Number</label>
            </div>

            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="Edit_otc" name="otc" placeholder="Enter oct/tct/cloa" autofocus>
              <label for="oct/tct/cloa">OCT/TCT/CLOA</label>
            </div>

            <div class="form-floating form-floating-outline mb-3">
              <select class="form-select" id="Edit_taxable" name="taxable" aria-label="Default select example">
                <option value="" selected disabled>Select</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
              </select>
              <label for="taxable">Taxable</label>
            </div>

          </form>
        </div>
        <div>
          <button type="button" class="btn btn-primary d-grid w-100 mb-3" id="UpdateAssessmentBtn">Submit</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
