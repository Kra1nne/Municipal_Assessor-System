@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('content')
@if(Auth::user()->role == "Admin" || Auth::user()->role == "Employee")
<div class="row gy-6">
  <div class="col-xl-3 col-md-6">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-2 fw-bolder">Total Users</p>
          <h3 class="fw-bold mb-0">{{ $user->count() }}</h3>
          <small class="text-success">Number of users</small>
        </div>
        <div class="text-primary fs-2">
          <i class="ri-group-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-2 fw-bolder">Active Users</p>
          <h3 class="fw-bold mb-0">{{ $activeUsers }}</h3>
          <small class="text-muted">
            @if($user->count() > 0)
              {{ round(($activeUsers / $user->count()) * 100, 1) }}% of total users
            @else
              0% of total users
            @endif
          </small>
        </div>
        <div class="text-success fs-2">
          <i class="ri-pulse-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-2 fw-bolder">Inactive Users</p>
          <h3 class="fw-bold mb-0">{{ $inactiveUsers }}</h3>
          <small class="text-muted">
            @if($user->count() > 0)
              {{ round(($inactiveUsers / $user->count()) * 100, 1) }}% of total users
            @else
              0% of total users
            @endif
          </small>
        </div>
        <div class="text-warning fs-2">
          <i class="ri-user-unfollow-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-2 fw-bolder">System Health</p>
          <h3 class="fw-bold text-success mb-0">Healthy</h3>
          <small class="text-muted">All services operational</small>
        </div>
        <div class="text-success fs-2">
          <i class="ri-checkbox-circle-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>



  <!-- Data Tables -->
  <div class="col-12">
    <div class="card overflow-hidden">
      <div class="table-responsive">
        <table class="table table-sm">
          <thead>
            <tr>
              <th class="text-truncate">User</th>
              <th class="text-truncate">Action</th>
              <th class="text-truncate">Table</th>
              <th class="text-truncate">Timestamp</th>
              <th class="text-truncate">Role</th>
              <th class="text-truncate">Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($log as $item)
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-sm me-4">
                    <img src="{{asset('assets/img/avatars/1.png')}}" alt="Avatar" class="rounded-circle">
                  </div>
                  <div>
                    <h6 class="mb-0 text-truncate">{{ $item->firstname }}</h6>
                    <small class="text-truncate">{{ $item->email}}</small>
                  </div>
                </div>
              </td>
              <td class="text-truncate">{{ $item->action }}</td>
              <td class="text-truncate">{{ $item->table_name }}</td>
              <td class="text-truncate">{{ date('M. d, Y', strtotime($item->created_at)) }}</td>
              <td class="text-truncate">
                <div class="d-flex align-items-center">
                  @if ($item->role == "Admin")
                      <i class="ri-vip-crown-line ri-22px text-primary me-2"></i>
                      <span>Admin</span>
                  @endif
                  @if ($item->role == "Employee")
                      <i class="ri-briefcase-line ri-22px text-info me-2"></i>
                      <span>Employee</span>
                  @endif
                  @if ($item->role == "User")
                      <i class="ri-user-3-line ri-22px text-success me-2"></i>
                      <span>User</span>
                  @endif
                </div>
              </td>
              <td><span class="badge bg-label-success rounded-pill">Success</span></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!--/ Data Tables -->
</div>
@endif
@if(Auth::user()->role == "User")
<div class="row gy-6">
  <div class="col-xl-3 col-md-6">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-2 fw-bolder">Own Property</p>
          <h3 class="fw-bold mb-0">{{ $count }}</h3>
          <small class="text-success">Number of property own</small>
        </div>
        <div class="text-primary fs-2">
          <i class="ri-building-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-2 fw-bolder">Under Review Request</p>
          <h3 class="fw-bold mb-0" id="reviewRequest">{{ $completepending  }}</h3>
          <small class="text-muted">Under review request</small>
        </div>
        <div class="text-muted fs-2">
          <i class="ri-error-warning-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-2 fw-bolder">Request Complete</p>
          <h3 class="fw-bold mb-0" id="completeRequest">{{ $completecount}}</h3>
          <small class="text-success">Request completed</small>
        </div>
        <div class="text-success fs-2">
          <i class="ri-checkbox-circle-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="py-5">
          <p class="text-muted mb-2 fw-bolder">Total Value</p>
          <h3 class="fw-bold text-success mb-0" id="totalValue">{{ $total}}</h3>
          <small class="text-muted">Total Assessed Value</small>
        </div>
        <div class="text-success fs-2">
          <i class="ri-money-dollar-box-line" style="font-size: 2.8rem;"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12">
    <div class="card overflow-hidden">
      <div class="table-responsive">
        <table class="table table-sm">
          <thead>
            <tr>
              <th class="text-truncate">Parcel ID</th>
              <th class="text-truncate">Lot Number</th>
              <th class="text-truncate">Address</th>
              <th class="text-truncate">Type</th>
              <th class="text-truncate">Area</th>
              <th class="text-truncate">Coordinates</th>
              <th class="text-truncate">Assessement Value</th>
              <th class="text-truncate">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($properties as $item)
            <tr>
              <td>{{ $item->parcel_id ?? "" }}</td>
              <td>
                {{ $item->lot_number ?? "" }}
              </td>
              <td class="text-truncate">{{ $item->address }}</td>
              <td class="text-truncate">{{ $item->name }}</td>
              <td class="text-truncate">{{ $item->area }} sq. m</td>
              <td class="text-truncate">
                <div>{{ $item->longitude}}&deg; E</div>
                <div>{{$item->longitude}}&deg; N</div>
              </td>
              <td>₱ {{ number_format($item->area * $item->value *  ($item->assessment_rate / 100), 2)}}</td>
              <td>
                <a href="{{ route('myproperty', $item->encrypted)}}" class="btn btn-success" target="_blank">View</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endif
@endsection
