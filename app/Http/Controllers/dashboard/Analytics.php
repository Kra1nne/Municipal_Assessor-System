<?php

namespace App\Http\Controllers\dashboard;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class Analytics extends Controller
{
  public function index()
  {
    $properties = Property::leftjoin('assessment', 'properties.id', '=', 'assessment.properties_id')
                            ->leftjoin('property_type', 'assessment.property_type', '=', 'property_type.id')
                            ->leftjoin('market_value', 'assessment.market_id', '=', 'market_value.id')
                            ->leftjoin('property_list', 'market_value.property_list', '=', 'property_list.id')
                            ->leftjoin('request', 'request.assessment_id', '=', 'assessment.id')
                            ->where('request.users_id', '=', Auth::id())
                            ->where('request.assessment_id', '!=', null)
                            ->get();


    $count = Property::leftjoin('assessment', 'properties.id', '=', 'assessment.properties_id')
                            ->leftjoin('property_type', 'assessment.property_type', '=', 'property_type.id')
                            ->leftjoin('market_value', 'assessment.market_id', '=', 'market_value.id')
                            ->leftjoin('property_list', 'market_value.property_list', '=', 'property_list.id')
                            ->leftjoin('request', 'request.assessment_id', '=', 'assessment.id')
                            ->where('request.users_id', '=', Auth::id())
                            ->where('request.assessment_id', '!=', null)
                            ->count();

    $completecount = Requests::where('status', '=', 'Success')->count();
    $completepending = Requests::where('status', '=', 'Request')->count();


    $totalAssessedValue = $properties
      ->sum(function ($item) {
          return $item->area * $item->value * ($item->assessment_rate / 100);
      });
    $total = $this->formatAbbreviatedPHP($totalAssessedValue);
    $properties->transform(function ($item) {
      $item->encrypted = Crypt::encryptString($item->assessment_id);
        return $item;
    });

    $user = User::all();
    $activeUsers = User::whereNull('deleted_at')->count();
    $inactiveUsers = User::whereNotNull('deleted_at')->count();
    $log = Log::leftjoin('users', 'logs.user_id', '=', 'users.id')
                ->leftjoin('person', 'users.person_id', '=', 'person.id')
                ->orderby('logs.created_at', 'desc')
                ->limit(10)
                ->get();
    return view('content.dashboard.dashboards-analytics', compact('user', 'log', 'activeUsers', 'inactiveUsers','properties', 'total', 'count', 'completecount', 'completepending'));
  }
  public function logslist(){
    $log = Log::leftjoin('users', 'logs.user_id', '=', 'users.id')
          ->leftjoin('person', 'users.person_id', '=', 'person.id')
          ->where('logs.created_at', '>=', Carbon::now()->subDays(30))
          ->orderBy('logs.created_at', 'desc')
          ->get();


    return view('content.logs-list.account-log',compact('log'));
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
