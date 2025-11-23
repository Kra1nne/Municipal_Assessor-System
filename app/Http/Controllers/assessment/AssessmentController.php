<?php

namespace App\Http\Controllers\assessment;

use App\Models\Log;
use App\Models\Assessor;
use App\Models\Property;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Assessment;
use App\Models\MarketValue;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\BuildingType;
use App\Models\Building;

class AssessmentController extends Controller
{
  public function index()
  {
    $countall = Property::count();
    $countReview = Property::where('status', '=', 'Under Review')->count();
    $countComplete = Property::where('status', '=', 'Complete')->count();

    $assessor = Assessor::whereNull('deleted_at')->orderBy('created_at', 'DESC')->get();
    $marketValue = MarketValue::leftjoin('property_list', 'market_value.property_list', '=', 'property_list.id')
      ->orderBy('market_value.created_at', 'Asc')
      ->Select('market_value.*', 'property_list.name as type_name')
      ->get();


    $properties = Property::leftjoin('assessment', 'properties.id', '=', 'assessment.properties_id')
      ->leftjoin('assessor', 'assessment.assessor_id', '=', 'assessor.id')
      ->leftjoin('property_type', 'assessment.property_type', '=', 'property_type.id')
      ->leftjoin('market_value', 'assessment.market_id', '=', 'market_value.id')
      ->leftjoin('property_list', 'market_value.property_list', '=', 'property_list.id')
      ->select('assessor.*', 'properties.*', 'assessment.id as assessment_id', 'assessment.date as assessment_date', 'market_value.value as market_value_data', 'property_type.assessment_rate', 'properties.status as property_status', 'property_list.name as ActualUse', 'properties.created_at as created_at', 'assessor.id as assessor_id', 'market_value.id as market_value_id', 'property_type.id as property_type_id', 'assessment.*', 'properties.id as property_id')
      ->whereNull('properties.deleted_at')
      ->orderBy('properties.created_at', 'asc')->get();

    $totalAssessedValue = $properties
      ->where('property_status', 'Complete')
      ->sum(function ($item) {
          return $item->area * $item->market_value_data * ($item->assessment_rate / 100);
      });

    $total = $this->formatAbbreviatedPHP($totalAssessedValue);

    $properties->transform(function ($property) {
        $property->property_id = Crypt::encryptString($property->property_id);
        return $property;
    });

    $actualUse = PropertyType::leftjoin('property_list', 'property_type.property_list', '=', 'property_list.id')
      ->whereNull('property_type.deleted_at')
      ->select('property_type.*', 'property_list.name as type_name')
      ->orderBy('property_type.created_at', 'Desc')->get();

    return view('content.assessement.assessment-list', compact('properties', 'assessor', 'marketValue', 'actualUse', 'countall', 'countReview', 'countComplete', 'total'));
  }
  public function lazyLoad(Request $request)
  {
      $page = $request->input('page', 1);
      $perPage = 20;
      $search = $request->input('search');
      $status = $request->input('status');

      $query = Property::leftjoin('assessment', 'properties.id', '=', 'assessment.properties_id')
          ->leftjoin('assessor', 'assessment.assessor_id', '=', 'assessor.id')
          ->leftjoin('property_type', 'assessment.property_type', '=', 'property_type.id')
          ->leftjoin('market_value', 'assessment.market_id', '=', 'market_value.id')
          ->leftjoin('property_list', 'market_value.property_list', '=', 'property_list.id')
          ->select(
              'assessor.*',
              'properties.*',
              'assessment.id as assessment_id',
              'assessment.date as assessment_date',
              'market_value.value as market_value_data',
              'property_type.assessment_rate',
              'properties.status as property_status',
              'property_list.name as ActualUse',
              'properties.created_at as created_at',
              'assessor.id as assessor_id',
              'market_value.id as market_value_id',
              'property_type.id as property_type_id',
              'assessment.*',
              'properties.id as property_id'
          )
          ->whereNull('properties.deleted_at');

      if (!empty($search)) {
          $query->where(function ($q) use ($search) {
              $q->where('properties.lot_number', 'LIKE', "%{$search}%")
                ->orWhere('properties.address', 'LIKE', "%{$search}%")
                ->orWhere('properties.owner', 'LIKE', "%{$search}%");
          });
      }

      if (!empty($status)) {
          $query->where('properties.status', $status);
      }

      $properties = $query->orderBy('properties.created_at', 'asc')
          ->skip(($page - 1) * $perPage)
          ->take($perPage)
          ->get();

      $properties->transform(function ($item) {
        $item->property_id = Crypt::encryptString($item->property_id);
          return $item;
      });

      return response()->json([
          'data' => $properties,
          'hasMore' => $properties->count() === $perPage,
      ]);
  }


  public function store(Request $request)
  {
    Log::insert([
      'user_id' => Auth::id(),
      'action' => 'Add',
      'table_name' => 'Assessment',
      'description' => 'Added a new Assessment data',
      'ip_address' => request()->ip(),
      'created_at' => now(),
    ]);

    $data = [
      'properties_id' => Crypt::decryptString($request->properties_id),
      'property_type' => $request->property_id,
      'assessor_id' => $request->assessor_id,
      'market_id' => $request->market_id,
      'date' => $request->date,
      'survey_no' => $request->survey_no,
      'arp_no' => $request->arp_no,
      'otc' => $request->otc,
      'pin' => $request->pin,
      'istaxable' => $request->taxable == 1 ? 1 : 0,
      'sub_classification' => $request->sub_classification,
      'isexempted' => $request->taxable == 1 ? 0 : 1,
      'outlet_road' => $request->outlet_road ?? 0,
      'dirt_road' => $request->dirt_road ?? 0,
      'weather_road' => $request->weather_road ?? 0,
      'provincial_road' => $request->provincial_road ?? 0,
      'created_at' => now(),
    ];
    $propertiesStatus = [
      'status' => 'Complete',
      'updated_at' => now(),
    ];
    $property = Property::where('id', Crypt::decryptString($request->properties_id))->update($propertiesStatus);
    $assessment = Assessment::insert($data);

    if ($assessment) {
      return response()->json(['Error' => 0, 'Message' => 'Successfully added a data']);
    }
  }
  public function update(Request $request)
  {
    Log::insert([
      'user_id' => Auth::id(),
      'action' => 'Add',
      'table_name' => 'Assessment',
      'description' => 'Added a new Assessment data',
      'ip_address' => request()->ip(),
      'created_at' => now(),
    ]);

    $data = [
      'property_type' => $request->property_id,
      'assessor_id' => $request->assessor_id,
      'market_id' => $request->market_id,
      'date' => $request->date,
      'survey_no' => $request->survey_no,
      'arp_no' => $request->arp_no,
      'otc' => $request->otc,
      'pin' => $request->pin,
      'istaxable' => $request->taxable == 1 ? 1 : 0,
      'sub_classification' => $request->sub_classification,
      'isexempted' => $request->taxable == 1 ? 0 : 1,
      'outlet_road' => $request->outlet_road ?? 0,
      'dirt_road' => $request->dirt_road ?? 0,
      'weather_road' => $request->weather_road ?? 0,
      'provincial_road' => $request->provincial_road ?? 0,
      'created_at' => now(),
    ];

    $assessment = Assessment::where('id', $request->assessment_id)->update($data);

    if ($assessment) {
      return response()->json(['Error' => 0, 'Message' => 'Successfully edited a data']);
    }
  }
  public function viewPdf($id)
  {
    $OIC_Municilap_Assessor = Assessor::where('role', '=', 'OIC Municilap Assessor')
                                ->whereNull('deleted_at')
                                ->selectRaw("CONCAT(assessor.firstname, ' ', assessor.middlename, ' ', assessor.lastname) as fullname")
                                ->first();
    $Technical_Supervisor = Assessor::where('role', '=', 'Technical Supervisor')
                                ->whereNull('deleted_at')
                                ->selectRaw("CONCAT(assessor.firstname, ' ', assessor.middlename, ' ', assessor.lastname) as fullname")
                                ->first();

    $Assessment_Clerk_1 = Assessor::where('role', '=', 'Assessment Clerk 1')
                                ->whereNull('deleted_at')
                                ->selectRaw("CONCAT(assessor.firstname, ' ', assessor.middlename, ' ', assessor.lastname) as fullname")
                                ->first();

    $properties = Property::leftjoin('assessment', 'properties.id', '=', 'assessment.properties_id')
      ->leftjoin('assessor', 'assessment.assessor_id', '=', 'assessor.id')
      ->leftjoin('property_type', 'assessment.property_type', '=', 'property_type.id')
      ->leftjoin('market_value', 'assessment.market_id', '=', 'market_value.id')
      ->leftjoin('property_list', 'market_value.property_list', '=', 'property_list.id')
      ->select('assessor.*', 'property_list.name as property_classification','properties.*', 'assessment.id as assessment_id', 'assessment.date as assessment_date', 'market_value.value as market_value_data', 'property_type.assessment_rate', 'properties.status as property_status', 'property_list.name as ActualUse', 'properties.created_at as created_at', 'assessment.*','properties.address as property_address')
      ->selectRaw("CONCAT(assessor.firstname, ' ', assessor.middlename, ' ', assessor.lastname) as fullname")
      ->where('properties.id', '=', Crypt::decryptString($id))
      ->whereNull('assessment.deleted_at')
      ->orderBy('properties.created_at', 'Desc')->first();

    $pdf = Pdf::loadView('pdf.assessment-pdf', [
        'properties' => $properties,
        'Assessment_Clerk_1' => $Assessment_Clerk_1,
        'Technical_Supervisor' => $Technical_Supervisor,
        'OIC_Municilap_Assessor' => $OIC_Municilap_Assessor
    ]);

    return $pdf->setPaper('A4', 'portrait')
               ->stream('Real Property Field Appraisal & Assessment Sheet.pdf');
  }


  public function buidingAssessment(){
    $classification = BuildingType::whereNull('deleted_at')->orderBy('created_at', 'Desc')->get();

    $properties = Property::leftjoin('assessment', 'properties.id', '=', 'assessment.properties_id')
      ->leftjoin('assessor', 'assessment.assessor_id', '=', 'assessor.id')
      ->leftjoin('property_type', 'assessment.property_type', '=', 'property_type.id')
      ->leftjoin('market_value', 'assessment.market_id', '=', 'market_value.id')
      ->leftjoin('property_list', 'market_value.property_list', '=', 'property_list.id')
      ->leftjoin('building', 'building.assessment_id', '=','assessment.id')
      ->leftjoin('building_type', 'building.building_type', '=', 'building_type.id')
      ->select('assessor.*', 'properties.*', 'assessment.id as assessment_id',
                'assessment.date as assessment_date', 'market_value.value as market_value_data',
                'property_type.assessment_rate', 'properties.status as property_status',
                'property_list.name as ActualUse', 'properties.created_at as created_at',
                'building.status as building_status', 'properties.id as property_id',
                'building.construction_cost as cost', 'building.storey as storey',
                'building.storey_size as size', 'building_type.percentage as percentage',
                'building_type.classification as classification', 'building.complete as complete_date',
                'building.building_type as structure_id', 'building.roof as roof',
                'building.flooring as flooring', 'building.walls as walls',
                'building.structural_type as structural_type', 'building.id as building_id')
      ->whereNull('assessment.deleted_at')
      ->where('assessment.id', '!=', null)
      ->orderBy('properties.created_at', 'Desc')->get();

    $properties->transform(function ($item) {
        $item->assessment_id = Crypt::encryptString($item->assessment_id);
        $item->property_id = Crypt::encryptString($item->building_id);
        $item->ids = Crypt::encryptString($item->id);
          return $item;
      });
    $assessmentCount = $properties->count();
    $complete = Building::count();
    $pending = $assessmentCount - $complete;

    return view('content.building.building-assessment', compact('properties','classification', 'assessmentCount', 'complete', 'pending'));
  }
  public function lazyLoadBuilding(Request $request)
  {
      $page = $request->input('page', 1);
      $perPage = 20;
      $search = $request->input('search');
      $status = $request->input('status');

      $query = Property::leftjoin('assessment', 'properties.id', '=', 'assessment.properties_id')
      ->leftjoin('assessor', 'assessment.assessor_id', '=', 'assessor.id')
      ->leftjoin('property_type', 'assessment.property_type', '=', 'property_type.id')
      ->leftjoin('market_value', 'assessment.market_id', '=', 'market_value.id')
      ->leftjoin('property_list', 'market_value.property_list', '=', 'property_list.id')
      ->leftjoin('building', 'building.assessment_id', '=','assessment.id')
      ->leftjoin('building_type', 'building.building_type', '=', 'building_type.id')
      ->select('assessor.*', 'properties.*', 'assessment.id as assessment_id',
                'assessment.date as assessment_date', 'market_value.value as market_value_data',
                'property_type.assessment_rate', 'properties.status as property_status',
                'property_list.name as ActualUse', 'properties.created_at as created_at',
                'building.status as building_status', 'properties.id as property_id',
                'building.construction_cost as cost', 'building.storey as storey',
                'building.storey_size as size', 'building_type.percentage as percentage',
                'building_type.classification as classification', 'building.complete as complete_date',
                'building.building_type as structure_id', 'building.roof as roof',
                'building.flooring as flooring', 'building.walls as walls',
                'building.structural_type as type', 'building.id as building_id')
      ->where('assessment.id', '!=', null);


      if ($search) {
            $query->where('properties.lot_number', 'LIKE', "%{$search}%");
      }

      if (!empty($status)) {
          $query->where('properties.status', $status);
      }

      $properties = $query->orderBy('properties.created_at', 'asc')
          ->skip(($page - 1) * $perPage)
          ->take($perPage)
          ->get();

      $properties->transform(function ($item) {
        $item->assessment_id = Crypt::encryptString($item->assessment_id);
        $item->property_id = Crypt::encryptString($item->building_id);
        $item->ids = Crypt::encryptString($item->id);
          return $item;
      });

      return response()->json([
          'data' => $properties,
          'hasMore' => $properties->count() === $perPage,
      ]);
  }
  public function buildingAssessmentStore(Request $request)
  {
    Log::insert([
      'user_id' => Auth::id(),
      'action' => 'Add',
      'table_name' => 'Building',
      'description' => 'Added a new Assessment data of the building',
      'ip_address' => request()->ip(),
      'created_at' => now(),
    ]);

    $data = [
      'assessment_id' => Crypt::decryptString($request->assessment_id),
      'building_type' => $request->type,
      'status' => "Complete",
      'storey' => $request->storey,
      'storey_size' => $request->storey_size,
      'construction_cost' => $request->totalcost,
      'structural_type' => $request->structural,
      'complete' => $request->complete,
      'roof' => $request->roof,
      'flooring' => $request->flooring,
      'walls' => $request->walls,
      'created_at' => now(),
    ];

    $result = Building::insert($data);

    if($result){
     return response()->json(['Error' => 0, 'Message' => 'Successfully added a data']);
    }
  }
  public function buildingAssessmentUpdate(Request $request)
  {
    Log::insert([
      'user_id' => Auth::id(),
      'action' => 'Update',
      'table_name' => 'Building',
      'description' => 'Update a Assessment data of the building',
      'ip_address' => request()->ip(),
      'created_at' => now(),
    ]);

    $data = [
      'building_type' => $request->type,
      'storey' => $request->storey,
      'storey_size' => $request->storey_size,
      'construction_cost' => $request->totalcost,
      'structural_type' => $request->structural,
      'complete' => $request->complete,
      'roof' => $request->roof,
      'flooring' => $request->flooring,
      'walls' => $request->walls,
      'created_at' => now(),
    ];
    $result = Building::where('id', Crypt::decryptString($request->building_id))->update($data);

    if($result){
      return response()->json(['Error' => 0, 'Message' => 'Successfully updated a data']);
    }
  }
  public function buildingPDF($id)
  {
    $OIC_Municilap_Assessor = Assessor::where('role', '=', 'OIC Municilap Assessor')
                                ->selectRaw("CONCAT(assessor.firstname, ' ', assessor.middlename, ' ', assessor.lastname) as fullname")
                                ->first();
    $Technical_Supervisor = Assessor::where('role', '=', 'Technical Supervisor')
                                ->selectRaw("CONCAT(assessor.firstname, ' ', assessor.middlename, ' ', assessor.lastname) as fullname")
                                ->first();

    $Assessment_Clerk_1 = Assessor::where('role', '=', 'Assessment Clerk 1')
                                ->selectRaw("CONCAT(assessor.firstname, ' ', assessor.middlename, ' ', assessor.lastname) as fullname")
                                ->first();

    $properties = Property::leftjoin('assessment', 'properties.id', '=', 'assessment.properties_id')
      ->leftjoin('assessor', 'assessment.assessor_id', '=', 'assessor.id')
      ->leftjoin('property_type', 'assessment.property_type', '=', 'property_type.id')
      ->leftjoin('market_value', 'assessment.market_id', '=', 'market_value.id')
      ->leftjoin('property_list', 'market_value.property_list', '=', 'property_list.id')
      ->leftjoin('building', 'building.assessment_id', '=', 'assessment.id')
      ->leftjoin('building_type', 'building_type.id', '=', 'building.building_type')
      ->select('assessor.*', 'properties.property_type as property_classification','properties.*',
                'assessment.id as assessment_id', 'assessment.date as assessment_date',
                'market_value.value as market_value_data', 'property_type.assessment_rate',
                'properties.status as property_status', 'property_list.name as ActualUse',
                'properties.created_at as created_at', 'assessment.*',
                'properties.address as property_address', 'building.*', 'building_type.*', 'building.created_at as inspection_date')
      ->selectRaw("CONCAT(assessor.firstname, ' ', assessor.middlename, ' ', assessor.lastname) as fullname")
      ->where('properties.id', '=', Crypt::decryptString($id))
      ->whereNull('assessment.deleted_at')
      ->orderBy('properties.created_at', 'Desc')->first();

    $pdf = Pdf::loadView('pdf.assessment-building', [
        'properties' => $properties,
        'OIC_Municilap_Assessor' => $OIC_Municilap_Assessor,
        'Technical_Supervisor' => $Technical_Supervisor,
        'Assessment_Clerk_1' => $Assessment_Clerk_1
    ]);
  //dd($properties);
    return $pdf->setPaper('A4', 'portrait')
               ->stream('Real Property Field Appraisal & Assessment Sheet.pdf');
  }
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
}
