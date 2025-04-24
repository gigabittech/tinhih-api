<?php

namespace App\Http\Controllers\Workspace\CalendarSttings;

use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarSettingResource;
use App\Repository\CalendarSettingsRepository;
use Illuminate\Http\Request;

class CalendarSettingsController extends Controller
{
    
    public function __construct(private CalendarSettingsRepository $repository){}


    public function getCalendar(Request $request, $id){
        $workspace = $request->user()->currentWorkspace()->calendarSettings;
        return $workspace;
    }
    public function createCalendar(Request $request){
        $workspace = $request->user()->currentWorkspace()->calendarSettings()->create();
        return new CalendarSettingResource($workspace);
    }
}
