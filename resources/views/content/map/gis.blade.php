@extends('layouts/contentNavbarLayout')

@section('title', 'GIS Map')

@section('content')

<div class="container mb-2">

        <!-- Admin Dashboard Stats (unchanged) -->
        <header class="row mb-3">

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card h-100 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="py-5">
                    <p class="text-muted mb-2 fw-bolder">Total Properties</p>
                    <h3 class="fw-bold mb-0">{{ $countall }}</h3>
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
                    <h3 class="fw-bold mb-0">{{ $countComplete }}</h3>
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
                    <p class="text-muted mb-2 fw-bolder">Under Review</p>
                    <h3 class="fw-bold mb-0">{{ $countReview }}</h3>
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
                    <h3 class="fw-bold text-success mb-0">{{ $total }}</h3>
                    <small class="text-muted">Total Assessed Value</small>
                    </div>
                    <div class="text-success fs-2">
                    <i class="ri-money-dollar-box-line" style="font-size: 2.8rem;"></i>
                    </div>
                </div>
                </div>
            </div>

        </header>

    <main>
        <input type="text" id="search-lot" placeholder="Search parcel id" class="form-control form-control-sm mb-2" style="width: 500px;">

        <div class="card">
          <div id="map" style="height: 700px; width: 100%; position: relative;"></div>
        </div>
    </main>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    .lot-label {
        font-size: 12px;
        font-weight: bold;
        color: black;
        background: rgba(255, 255, 255, 0.9);
        padding: 2px 4px;
        border: 1px solid #666;
        border-radius: 4px;
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Force labels when user role = User
    @if(Auth::user()->role == "User")
        const forceLabel = true;
    @else
        const forceLabel = false;
    @endif

    // Create map
    var map = L.map('map').setView([10.518201, 124.768402], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);


    // GEOJSON Layer
    let geojsonLayer = L.geoJSON(null, {
        onEachFeature: function (feature, layer) {

            let lot = feature.properties.LotNumber ?? "Unknown";
            let owner = feature.properties.Owner ?? "Unknown";

            layer.bindPopup(`
                <b>Lot Number:</b> ${lot}<br>
                <b>Owner:</b> ${owner}
            `);

            // Always show labels for users
            if (forceLabel) {
                layer.bindTooltip(
                    `LOT: ${lot}<br>OWNER: ${owner}`,
                    {
                        permanent: true,
                        direction: "center",
                        className: "lot-label",
                    }
                ).openTooltip();
            }

            layer.setStyle({
                color: "gray",
                weight: 1,
                fillColor: "transparent",
                fillOpacity: 0.3
            });
        }
    }).addTo(map);


    /** Load visible parcels **/
    function loadVisibleParcels() {
        let b = map.getBounds();
        let bbox = [b.getWest(), b.getSouth(), b.getEast(), b.getNorth()].join(',');

        fetch(`{{ route('map-gis-parcel') }}?bbox=` + bbox)
            .then(res => res.json())
            .then(data => {
                geojsonLayer.clearLayers();
                geojsonLayer.addData(data);
            });
    }

    map.on('moveend', loadVisibleParcels);
    loadVisibleParcels();


    /** Search Lot **/
    document.getElementById("search-lot").addEventListener("keypress", function(e){
        if (e.key !== "Enter") return;

        let val = this.value.trim();
        if (!val) return;

        fetch(`/gis-map/search?lot_number=${val}`)
            .then(res => res.json())
            .then(data => {

                geojsonLayer.clearLayers();

                if (data.features.length === 0) {
                    Swal.fire("Error", "Lot not found or no access", "error");
                    return;
                }

                geojsonLayer.addData(data);
                let lyr = geojsonLayer.getLayers()[0];

                map.fitBounds(lyr.getBounds());
                lyr.openPopup();

            });
    });

});
</script>

@endsection
