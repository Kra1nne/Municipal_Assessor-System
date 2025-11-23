<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use App\Models\Requests;

class MapController extends Controller
{
    private function formatAbbreviatedPHP($value) {
        $value = floatval($value);
        $absValue = abs($value);
        $formatted = '';

        if ($absValue >= 1_000_000_000) {
            $formatted = number_format($value / 1_000_000_000, 2) . 'B';
        } elseif ($absValue >= 1_000_000) {
            $formatted = number_format($value / 1_000_000, 2) . 'M';
        } elseif ($absValue >= 1_000) {
            $formatted = number_format($value / 1_000, 2) . 'K';
        } else {
            $formatted = number_format($value, 2);
        }

        return '₱' . $formatted;
    }

    public function index()
    {

        $role = Auth::user()->role;

        if ($role == "Admin" || $role == "Employee") {

          $countall = Property::count();
          $countReview = Property::where('status', '=', 'Under Review')->count();
          $countComplete = Property::where('status', '=', 'Complete')->count();

          $properties = Property::leftjoin('assessment', 'properties.id', '=', 'assessment.properties_id')
              ->leftjoin('assessor', 'assessment.assessor_id', '=', 'assessor.id')
              ->leftjoin('property_type', 'assessment.property_type', '=', 'property_type.id')
              ->leftjoin('market_value', 'assessment.market_id', '=', 'market_value.id')
              ->leftjoin('property_list', 'market_value.property_list', '=', 'property_list.id')
              ->select(
                  'properties.*',
                  'properties.address as property_address',
                  'assessment.id as assessment_id',
                  'assessment.date as assessment_date',
                  'assessor.*',
                  'market_value.value as market_value_data',
                  'property_type.assessment_rate',
                  'properties.status as property_status',
                  'property_list.name as ActualUse',
                  'properties.id as property_id'
              )
              ->whereNull('properties.deleted_at')
              ->orderBy('properties.created_at', 'Desc')
              ->get();

          $totalAssessedValue = $properties
            ->where('property_status', 'Complete')
            ->sum(function ($item) {
                return $item->area * $item->market_value_data * ($item->assessment_rate / 100);
            });

        } else {
            // USER: only properties requested by them

            $countall = Property::leftjoin('assessment', 'properties.id', '=', 'assessment.properties_id')
                ->leftjoin('request', 'request.assessment_id', '=', 'assessment.id')
                ->where('request.users_id', Auth::id())
                ->count();
            $countReview = Property::leftjoin('assessment', 'properties.id', '=', 'assessment.properties_id')
                ->leftjoin('request', 'request.assessment_id', '=', 'assessment.id')
                ->where('request.users_id', Auth::id())
                ->where('request.status', '=', 'Under Review')
                ->count();
            $countComplete = Property::leftjoin('assessment', 'properties.id', '=', 'assessment.properties_id')
                ->leftjoin('request', 'request.assessment_id', '=', 'assessment.id')
                ->where('request.users_id', Auth::id())
                ->where('request.status', '=', 'Success')
                ->count();

            $properties = Property::leftjoin('assessment', 'properties.id', '=', 'assessment.properties_id')
                ->leftjoin('assessor', 'assessment.assessor_id', '=', 'assessor.id')
                ->leftjoin('property_type', 'assessment.property_type', '=', 'property_type.id')
                ->leftjoin('market_value', 'assessment.market_id', '=', 'market_value.id')
                ->leftjoin('property_list', 'market_value.property_list', '=', 'property_list.id')
                ->leftjoin('request', 'request.assessment_id', '=', 'assessment.id')
                ->select(
                    'properties.*',
                    'properties.address as property_address',
                    'assessment.id as assessment_id',
                    'assessment.date as assessment_date',
                    'assessor.*',
                    'market_value.value as market_value_data',
                    'property_type.assessment_rate',
                    'properties.status as property_status',
                    'property_list.name as ActualUse',
                    'properties.id as property_id'
                )
                ->whereNull('properties.deleted_at')
                ->where('request.users_id', Auth::id())
                ->orderBy('properties.created_at', 'Desc')
                ->get();

            $totalAssessedValue = $properties
              ->where('property_status', 'Complete')
              ->sum(function ($item) {
                  return $item->area * $item->market_value_data * ($item->assessment_rate / 100);
              });
        }


        $total = $this->formatAbbreviatedPHP($totalAssessedValue);

        return view('content.map.gis', compact('countall', 'countReview', 'countComplete', 'total'));
    }




    // ------------------------------------------------------------
    // LAZY LOADING OF MAP PARCELS (FILTER BY USER ACCESS)
    // ------------------------------------------------------------
    public function getParcels(Request $request)
    {
        if (!$request->bbox) {
            return ['type' => 'FeatureCollection', 'features' => []];
        }

        $bbox = explode(',', $request->bbox);
        [$minLon, $minLat, $maxLon, $maxLat] = $bbox;

        $geojsonPath = public_path('geojson/inopacan_geojson.geojson');
        $geoData = json_decode(file_get_contents($geojsonPath), true);

        $role = Auth::user()->role;

        // --------------------------
        // ADMIN & EMPLOYEE → ALL DATA
        // --------------------------
        if ($role == "Admin" || $role == "Employee") {
            $properties = Property::select('lot_number', 'owner', 'parcel_id')
                ->get()
                ->keyBy('parcel_id');
        } else {
            $properties = Property::leftJoin('assessment', 'properties.id', '=', 'assessment.properties_id')
                ->leftjoin('assessor', 'assessment.assessor_id', '=', 'assessor.id')
                ->leftjoin('property_type', 'assessment.property_type', '=', 'property_type.id')
                ->leftjoin('market_value', 'assessment.market_id', '=', 'market_value.id')
                ->leftjoin('property_list', 'market_value.property_list', '=', 'property_list.id')
                ->leftjoin('request', 'request.assessment_id', '=', 'assessment.id')
                ->where('request.users_id', Auth::id())
                ->select('properties.lot_number', 'properties.owner', 'properties.parcel_id')
                ->get()
                ->keyBy('parcel_id');
        }

        $filtered = [];

        foreach ($geoData['features'] as &$feature) {

            $geometry = $feature['geometry'] ?? null;
            if (!$geometry) continue;

            $coordinates = $geometry['coordinates'] ?? null;
            if (!$coordinates) continue;

            $firstCoord = null;
            if ($geometry['type'] === 'Polygon') {
                $firstCoord = $coordinates[0][0] ?? null;
            } elseif ($geometry['type'] === 'MultiPolygon') {
                $firstCoord = $coordinates[0][0][0] ?? null;
            }

            if (!$firstCoord) continue;

            $lon = $firstCoord[0];
            $lat = $firstCoord[1];

            // Check if inside BBOX
            if (
                $lon >= $minLon && $lon <= $maxLon &&
                $lat >= $minLat && $lat <= $maxLat
            ) {
                $lot = $feature['properties']['ID'] ?? null;

                // Only attach owner if user has permission
                if ($lot && isset($properties[$lot])) {
                    $feature['properties']['Owner'] = $properties[$lot]->owner;
                    $filtered[] = $feature;
                }
            }
        }

        return [
            "type" => "FeatureCollection",
            "features" => $filtered
        ];
    }




    // ------------------------------------------------------------
    // SEARCH LOT (FILTER BY USER ACCESS)
    // ------------------------------------------------------------
    public function searchLot(Request $request)
    {
        $lot = $request->lot_number;
        if (!$lot) {
            return response()->json(['error' => 'Lot number required'], 400);
        }

        $role = Auth::user()->role;

        if ($role == "Admin" || $role == "Employee") {
            $properties = Property::select('lot_number', 'owner')->get()->keyBy('lot_number');
        } else {

            $properties = Property::leftJoin('assessment', 'properties.id', '=', 'assessment.properties_id')
                ->leftjoin('assessor', 'assessment.assessor_id', '=', 'assessor.id')
                ->leftjoin('property_type', 'assessment.property_type', '=', 'property_type.id')
                ->leftjoin('market_value', 'assessment.market_id', '=', 'market_value.id')
                ->leftjoin('property_list', 'market_value.property_list', '=', 'property_list.id')
                ->leftjoin('request', 'request.assessment_id', '=', 'assessment.id')
                ->where('request.users_id', Auth::id())
                ->select('properties.lot_number', 'properties.owner','properties.parcel_id')
                ->get()
                ->keyBy('parcel_id');
        }

        // Block unauthorized search results
        if (!isset($properties[$lot])) {
            return [
                'type' => 'FeatureCollection',
                'features' => []
            ];
        }

        // Load GeoJSON
        $geojsonPath = public_path('geojson/inopacan_geojson.geojson');
        $geoData = json_decode(file_get_contents($geojsonPath), true);

        foreach ($geoData['features'] as $feature) {
            if (($feature['properties']['ID'] ?? '') == $lot) {

                $feature['properties']['Owner'] = $properties[$lot]->owner;

                return [
                    'type' => 'FeatureCollection',
                    'features' => [$feature]
                ];
            }
        }

        return [
            'type' => 'FeatureCollection',
            'features' => []
        ];
    }

}
