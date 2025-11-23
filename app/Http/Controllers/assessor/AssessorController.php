<?php

namespace App\Http\Controllers\assessor;

use App\Models\Log;
use App\Models\Assessor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AssessorController extends Controller
{
    public function index()
    {
      $assessors = Assessor::whereNull('deleted_at')->orderBy('created_at', 'Desc')->get();
      return view('content.assessor.assessor_list', compact('assessors'));
    }
    public function store(Request $request)
    {
      Log::insert([
        'user_id' => Auth::id(),
        'action' => 'Add',
        'table_name' => 'Assessor',
        'description' => 'Added a Assessor',
        'ip_address' => request()->ip(),
        'created_at' => now(),
      ]);

      $data = Assessor::insert([
        'firstname' => $request->firstname,
        'middlename' => $request->middlename,
        'lastname' => $request->lastname,
        'address' => $request->address,
        'role' => $request->role,
        'phone' => $request->phone,
        'status' => "Active",
        'created_at' => now(),
      ]);

      if($data){
        return response()->json(['Error' => 0, 'Message' => 'Successfully added a data']);
      }
    }
    public function update(Request $request)
    {
      Log::insert([
        'user_id' => Auth::id(),
        'action' => 'Update',
        'table_name' => 'Assessor',
        'description' => 'Updated an Assessor',
        'ip_address' => request()->ip(),
        'created_at' => now(),
      ]);

      $data = [
        'firstname' => $request->firstname,
        'middlename' => $request->middlename,
        'lastname' => $request->lastname,
        'role' => $request->role,
        'address' => $request->address,
        'phone' => $request->phone,
        'updated_at' => now(),
      ];
      $assessor = Assessor::where('id', Crypt::decryptString($request->encrypted_id))->update($data);

      if($assessor){
        return response()->json(['Error' => 0, 'Message' => 'Successfully updated a data']);
      }
    }
    public function delete(Request $request)
    {
      Log::insert([
        'user_id' => Auth::id(),
        'action' => 'Delete',
        'table_name' => 'Assessor',
        'description' => 'Deleted an Assessor',
        'ip_address' => request()->ip(),
        'created_at' => now(),
      ]);

      $assessor = Assessor::where('id', Crypt::decryptString($request->id))->update([
        'deleted_at' => now(),
      ]);

      if($assessor){
        return response()->json(['Error' => 0, 'Message' => 'Successfully deleted a data']);
      }
    }
}
