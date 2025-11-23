<?php

namespace App\Http\Controllers\land;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Requests;
use App\Models\Log;
use App\Models\Property;
use App\Models\Assessor;

class LandPropertyController extends Controller
{
    public function index()
    {

      $request = Requests::whereNull('request.deleted_at')
                  ->leftjoin('users', 'users.id', '=', 'request.users_id')
                  ->leftjoin('person', 'person.id', '=', 'users.person_id')
                  ->orderBy('request.created_at', 'desc')
                  ->where('users.id', '=', Auth::id())
                  ->select('request.*','users.*', 'person.*', 'request.id as request_id')
                  ->get()
                  ->map(function ($req) {
                        $req->encrypted_assessment_id = Crypt::encryptString($req->assessment_id);
                        return $req;
                    });

      return view('content.request.land_property', compact('request'));
    }
    public function request(Request $request)
    {

      $path_request = $request->request_form->store('uploads', 'public');
      $path_certificate = $request->certificate->store('uploads', 'public');
      $path_pot = $request->proff_of_transfer->store('uploads', 'public');
      $path_authorizing = $request->authorizing->store('uploads', 'public');
      $path_updated_tax = $request->updated_tax->store('uploads', 'public');
      $path_transfer_tax = $request->transfer_tax->store('uploads', 'public');
      $path_tax_reciept = $request->tax_reciept->store('uploads', 'public');

      $data = [
        'users_id' => Auth::id(),
        'status' => "Request",
        'request_form' => $path_request,
        'certificate'=> $path_certificate,
        'proff_of_transfer' => $path_pot,
        'authorizing' => $path_authorizing,
        'updated_tax' => $path_updated_tax,
        'transfer_tax' => $path_transfer_tax,
        'tax_reciept' => $path_tax_reciept,
        'created_at' => now(),
      ];
      Requests::insert($data);
      $log = [
        'user_id' => Auth::id(),
        'action' => 'Add',
        'table_name' => 'Request',
        'description' => 'Added a Request',
        'ip_address' => request()->ip(),
        'created_at' => now(),
      ];
      $logData = Log::insert($log);

      if($logData){
        return response()->json(['Error' => 0, 'Message' => 'Request has successfully submited.']);
      }
    }
    public function viewPdf($id)
    {
      $properties = Property::leftjoin('assessment', 'properties.id', '=', 'assessment.properties_id')
        ->leftjoin('assessor', 'assessment.assessor_id', '=', 'assessor.id')
        ->leftjoin('property_type', 'assessment.property_type', '=', 'property_type.id')
        ->leftjoin('market_value', 'assessment.market_id', '=', 'market_value.id')
        ->leftjoin('property_list', 'market_value.property_list', '=', 'property_list.id')
        ->leftjoin('request', 'request.assessment_id', '=', 'assessment.id')
        ->select('assessor.*', 'property_list.name as property_classification','properties.*', 'assessment.id as assessment_id', 'assessment.date as assessment_date', 'market_value.value as market_value_data', 'property_type.assessment_rate', 'properties.status as property_status', 'property_list.name as ActualUse', 'properties.created_at as created_at', 'assessment.*', 'properties.address as property_address')
        ->selectRaw("CONCAT(assessor.firstname, ' ', assessor.middlename, ' ', assessor.lastname) as fullname")
        ->where('assessment.id', '=', Crypt::decryptString($id))
        ->whereNull('assessment.deleted_at')
        ->orderBy('properties.created_at', 'Desc')->first();

      $OIC_Municilap_Assessor = Assessor::where('role', '=', 'OIC Municilap Assessor')
                                ->whereNull('deleted_at')
                                ->selectRaw("CONCAT(assessor.firstname, ' ', assessor.middlename, ' ', assessor.lastname) as fullname")
                                ->first();

      $pdf = Pdf::loadView('pdf.tax-declaration', [
          'properties' => $properties,
          'OIC_Municilap_Assessor' => $OIC_Municilap_Assessor
      ]);

      return $pdf->setPaper('A4', 'portrait')
                ->stream('Real Property Field Appraisal & Assessment Sheet.pdf');
    }
}
