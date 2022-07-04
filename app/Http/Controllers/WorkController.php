<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkController extends Controller
{
    public function showWorkingTimeLine()
    {
        $currentPath = "working-timeline";
        return view("working-timeline", compact("currentPath"));
    }

    public function getWorkingTimeLine(Request $request)
    {
        $dateRange = $request->input("filters.dateRange");
        $empGroup = $request->input("filters.empGroup");
        $searchKw = $request->input("filters.searchKeyword");
        $length = $request->input("length");
        $start = $request->input("start");

        $dateExp = explode(" - ", $dateRange);
        $dateStart = Carbon::createFromFormat("d/m/Y", $dateExp[0])->format("Y-m-d");
        $dateEnd = Carbon::createFromFormat("d/m/Y", $dateExp[1])->format("Y-m-d");

        if (isset($searchKw) && $searchKw !== "") {
            $wkTimeLineQry = DB::table("working_details")
                ->leftJoin("employees AS emp", "emp.id", "=", "working_details.employee_id")
                ->whereBetween("working_date", [$dateStart, $dateEnd])
                ->where(function($query) use ($searchKw) {
                  $query->where("code", "LIKE", "{$searchKw}%")->orWhere("name_th", "LIKE", "{$searchKw}%");
                });

            if ($empGroup === "late") {
                $wkTimeLineQry->where("work_time", "<", 9);
            }

            $total = $wkTimeLineQry->count();
            $totalFiltered = $total;
            $wkTimeLine = $wkTimeLineQry->skip($start)->take($length)->get();
        } else {
            $wkTimeLineQry = DB::table("working_details")
                ->leftJoin("employees AS emp", "emp.id", "=", "working_details.employee_id")
                ->whereBetween("working_date", [$dateStart, $dateEnd]);

            if ($empGroup === "late") {
                $wkTimeLineQry->where("work_time", "<", 9);
            }

            $total = $wkTimeLineQry->count();
            $totalFiltered = $total;
            $wkTimeLine = $wkTimeLineQry->skip($start)->take($length)->get();
        }

        foreach ($wkTimeLine as $wtl)
        {
            $wtl->shift_start =  Carbon::parse($wtl->shift_start)->toTimeString("minute") . " น.";
            $wtl->shift_end =  Carbon::parse($wtl->shift_end)->toTimeString("minute") . " น.";;
        }

        $resData = [
            "data" => $wkTimeLine,
            "recordsTotal" => $total,
            "recordsFiltered" => $totalFiltered
        ];

        return response()->json($resData);
    }
}
