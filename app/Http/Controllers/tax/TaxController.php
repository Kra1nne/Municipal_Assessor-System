<?php

namespace App\Http\Controllers\tax;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Assessor;


class TaxController extends Controller
{
    public function viewPdf(){
       $properties = Property::leftjoin('assessment', 'properties.id', '=', 'assessment.properties_id')
        ->leftjoin('assessor', 'assessment.assessor_id', '=', 'assessor.id')
        ->leftjoin('property_type', 'assessment.property_type', '=', 'property_type.id')
        ->leftjoin('market_value', 'assessment.market_id', '=', 'market_value.id')
        ->leftjoin('property_list', 'market_value.property_list', '=', 'property_list.id')
        ->select('assessor.*', 'properties.*', 'assessment.id as assessment_id', 'assessment.date as assessment_date', 'market_value.value as market_value_data', 'property_type.assessment_rate', 'properties.status as property_status', 'property_list.name as ActualUse', 'properties.created_at as created_at')
        ->selectRaw("CONCAT(assessor.firstname, ' ', assessor.middlename, ' ', assessor.lastname) as fullname")
        ->where('properties.id', '=', 4)
        ->whereNull('assessment.deleted_at')
        ->orderBy('properties.created_at', 'Desc')->first();

      $OIC_Municilap_Assessor = Assessor::where('role', '=', 'OIC Municilap Assessor')
                                ->selectRaw("CONCAT(assessor.firstname, ' ', assessor.middlename, ' ', assessor.lastname) as fullname")
                                ->first();

      $pdf = Pdf::loadView('pdf.tax-declaration', [
          'properties' => $properties,
          'OIC_Municilap_Assessor' => $OIC_Municilap_Assessor
      ]);

      return $pdf->setPaper('A4', 'portrait')
                ->stream('Tax_Declaration_Reports.pdf');
    }
}
