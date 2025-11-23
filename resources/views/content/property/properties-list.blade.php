@extends('layouts/contentNavbarLayout')

@section('title', 'Properties - List')

@section('page-script')
@vite('resources/assets/js/properties.js')
@endsection

@section('content')
{{-- content here --}}
<header class="row mb-8">
  <div class="col-xl-3 col-md-6 mb-3">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-2 fw-bolder">Total Properties</p>
          <h3 class="fw-bold mb-0" id="totalProperties">{{ $countall }}</h3>
          <small class="text-primary">All register properties</small>
        </div>
        <div class="text-primary fs-2">
          <i class="ri-building-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-3">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-2 fw-bolder">Active Properties</p>
          <h3 class="fw-bold mb-0" id="activeProperties">{{ $countComplete }}</h3>
          <small class="text-success">Current active</small>
        </div>
        <div class="text-success fs-2">
          <i class="ri-building-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-3">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-2 fw-bolder">Under Review Properties</p>
          <h3 class="fw-bold mb-0" id="reviewProperties">{{ $countReview }}</h3>
          <small class="text-muted">Under Review Properties</small>
        </div>
        <div class="text-muted fs-2">
          <i class="ri-building-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-3">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-2 fw-bolder">Total Value</p>
          <h3 class="fw-bold text-success mb-0" id="totalValue">{{ $total }}</h3>
          <small class="text-muted">Total Assessed Value</small>
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
      <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#AddAccount">
        <span class="tf-icons ri-add-circle-line ri-16px me-1_5"></span>Add
      </button>
    </div>
  </div>
  <div class="table-responsive text-nowrap overflow-auto" style="max-height: 500px;">
    <table class="table table-hover">
      <thead class="position-sticky top-0 bg-body">
        <tr>
          <th>Parcel ID</th>
          <th>Lot Number</th>
          <th>Address</th>
          <th>Type</th>
          <th>Area</th>
          <th>Coordinates</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0" id="propertylist">
      </tbody>
    </table>
  </div>
</section>
  {{-- add data modal --}}
<div class="modal fade" id="AddAccount" tabindex="-1" aria-hidden="true">
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
              <input type="text" class="form-control" id="owner" name="owner" placeholder="Enter property owner" autofocus>
              <label for="owner">Owner</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="address" name="address" placeholder="Enter property address" autofocus>
              <label for="address">Address</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="tin" name="tin" placeholder="Enter property address" autofocus>
              <label for="Tin">TIN Number</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="lot_number" name="lot_number" placeholder="Enter lot number" autofocus>
              <label for="lot_number">Lot Number</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="previous_owner" name="previous_owner" placeholder="Enter property owner" autofocus>
              <label for="previous_owner">Previous Owner</label>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="number" class="form-control" id="area" name="area" placeholder="Enter area sq.m.">
                  <label for="area">Area (sq.m.)</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="number" class="form-control" id="longitude" name="longitude" placeholder="Enter longitude">
                  <label for="longitude">Longitude</label>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="number" class="form-control" id="latitude" name="latitude" placeholder="Enter latitude">
                  <label for="latitude">Latitude</label>
                </div>
              </div>
            </div>
            <div class="form-floating form-floating-outline mb-4">
              <select class="form-select" id="type" name="type" aria-label="Default select example">
                <option value="" selected disabled>Select Land Classification</option>
                <option value="Residential">Residential</option>
                <option value="Commercial">Commercial</option>
                <option value="Industrial">Industrial</option>
                <option value="Agricultural">Agricultural</option>
                <option value="Institutional">Institutional</option>
                <option value="Special Use">Special Use</option>
              </select>
              <label for="exampleFormControlSelect1">Property Type</label>
            </div>

             <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" class="form-control" id="street" name="street" placeholder="Enter street where the lot located">
                  <label for="street">Street</label>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" class="form-control" id="brgy" name="brgy" placeholder="Enter Brgy. where the lot located">
                  <label for="brgy">District/Brgy</label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" class="form-control" id="south" name="south" placeholder="South">
                  <label for="south">South</label>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" class="form-control" id="east" name="east" placeholder="East">
                  <label for="east">East</label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" class="form-control" id="north" name="north" placeholder="North">
                  <label for="north">North</label>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" class="form-control" id="west" name="west" placeholder="West">
                  <label for="west">West</label>
                </div>
              </div>
            </div>

          </form>
        </div>
        <div>
          <button type="button" class="btn btn-primary d-grid w-100 mb-3" id="AddAccountBtn">Submit</button>
      </div>
      </div>
    </div>
  </div>
</div>
 {{-- edit data modal --}}
<div class="modal fade" id="EditPropertyData" tabindex="-1" aria-hidden="true">
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

          <form id="UpdatedPropertyData" class="mb-3">
            @csrf
            <div class="form-floating form-floating-outline mb-3">
              <input type="hidden" name="id" id="Edit_id">
              <input type="text" class="form-control" id="Edit_owner" name="owner" placeholder="Enter property owner" autofocus>
              <label for="Edit_owner">Owner</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="Edit_address" name="address" placeholder="Enter property address" autofocus>
              <label for="Edit_address">Address</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="Edit_tin" name="tin" placeholder="Enter property address" autofocus>
              <label for="Edit_Tin">TIN Number</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="Edit_lot_number" name="lot_number" placeholder="Enter lot number" autofocus>
              <label for="Edit_lot_number">Lot Number</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="Edit_previous_owner" name="previous_owner" placeholder="Enter property owner" autofocus>
              <label for="owner">Previous Owner</label>
            </div>
           <div class="row">
              <div class="col-12">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="number" class="form-control" id="Edit_area" name="area" placeholder="Enter area sq.m.">
                  <label for="area">Area (sq.m.)</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="number" class="form-control" id="Edit_longitude" name="longitude" placeholder="Enter longitude">
                  <label for="longitude">Longitude</label>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="number" class="form-control" id="Edit_latitude" name="latitude" placeholder="Enter latitude">
                  <label for="latitude">Latitude</label>
                </div>
              </div>
            </div>
            <div class="form-floating form-floating-outline mb-4">
              <select class="form-select" id="Edit_type" name="type" aria-label="Default select example">
                <option value="" selected disabled>Select Land Classification</option>
                <option value="Residential">Residential</option>
                <option value="Commercial">Commercial</option>
                <option value="Industrial">Industrial</option>
                <option value="Agricultural">Agricultural</option>
                <option value="Institutional">Institutional</option>
                <option value="Special Use">Special Use</option>
              </select>
              <label for="exampleFormControlSelect1">Property Type</label>
            </div>

            <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" class="form-control" id="Edit_street" name="street" placeholder="Enter street where the lot located">
                  <label for="longitude">Street</label>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" class="form-control" id="Edit_brgy" name="brgy" placeholder="Enter Brgy. where the lot located">
                  <label for="latitude">District/Brgy</label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" class="form-control" id="Edit_south" name="south" placeholder="South">
                  <label for="south">South</label>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" class="form-control" id="Edit_east" name="east" placeholder="East">
                  <label for="east">East</label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" class="form-control" id="Edit_north" name="north" placeholder="North">
                  <label for="north">North</label>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" class="form-control" id="Edit_west" name="west" placeholder="West">
                  <label for="west">West</label>
                </div>
              </div>
            </div>

          </form>
        </div>
        <div>
          <button type="submit" class="btn btn-primary d-grid w-100 mb-3" id="EditBtn">Submit</button>
      </div>
      </div>
    </div>
  </div>
</div>
@endsection
