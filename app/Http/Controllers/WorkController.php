<?php

namespace App\Http\Controllers;

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
        $searchKw = $request->input("search");
        $length = $request->input("length");
        $start = $request->input("start");

        if (isset($searchKw) && $searchKw !== "") {
            $wkTimeLineQry = DB::table("working_details")
                ->leftJoin("employees AS emp", "emp.id", "=", "working_details.employee_id");
            $total = $wkTimeLineQry->count();
            $totalFiltered = $total;
            $wkTimeLine = $wkTimeLineQry->skip($start)->take($length)->get();
        } else {
            $wkTimeLineQry = DB::table("working_details")
                ->leftJoin("employees AS emp", "emp.id", "=", "working_details.employee_id");
            $total = $wkTimeLineQry->count();
            $totalFiltered = $total;
            $wkTimeLine = $wkTimeLineQry->skip($start)->take($length)->get();
        }

        $resData = [
            "data" => $wkTimeLine,
            "recordsTotal" => $total,
            "recordsFiltered" => $totalFiltered
        ];

        return response()->json($resData);
    }
}
