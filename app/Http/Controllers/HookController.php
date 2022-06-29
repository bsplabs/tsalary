<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HookController extends Controller
{
    public function dataHandler(Request $request)
    {
        $headerAuth = $request->header("Authorization");

        if (empty($headerAuth)) {
            return response(null, 401);
        }

        if ($headerAuth !== env("API_KEY")) {
            return response(null, 401);
        }

        $data = $request->all();

        foreach ($data as $key => $d)
        {
            $workingDate = $d["WorkingDate"];
            $total = $d["Total"];
            $workTime = $d["WorkTime"];
            $otTime = $d["OTTime"];
            $enrollNo = $d["EnrollNo"];
            $code = $d["Code"];
            $nameTH = $d["NameTh"];
            $nameEN = $d["NameEn"];
            $shiftStart = $d["ShiftStart"];
            $shiftEnd = $d["ShiftEnd"];

            if (empty($code) && empty($nameTH)) {
                continue;
            }

            $checkEmpQry = DB::table("employees")
                ->where(function($query) use ($code) {
                    $query->where("code", "=", $code)->whereNotNull("code");
                })
                ->orWhere(function($query) use ($nameTH) {
                    $query->where("name_th", "=", $nameTH)->whereNotNull("name_th");
                });

            $checkEmp = $checkEmpQry->count();
            if ($checkEmp > 0) {
                $empId = $checkEmpQry->select("id")->get()[0]->id;
            } else {
                // Insert new emp
                $empId = DB::table("employees")
                    ->insertGetId([
                        "code" => $code,
                        "enroll_no" => $enrollNo,
                        "name_th" => $nameTH,
                        "name_en" => $nameEN,
                        "created_at" => Carbon::now()
                    ]);
            }

            // Get increase revenue
            $incData = DB::table("increase_lists")
                ->whereIn("item_type", ["revenue", "ot"])
                ->where("item_date", "=", $workingDate)
                ->where("employee_id", "=", $empId)
                ->get();

            $itemData = DB::table("items")
                ->whereIn("value_type", ["revenue", "ot"])
                ->get();

            $revenue = null;
            $ot = null;
            foreach ($incData as $inc)
            {
                if ($inc->item_type === "revenue") $revenue = $inc->item_value;
                if ($inc->item_type === "ot") $ot = $inc->item_value;
            }

            if (!empty($itemData) && $itemData->count() > 0) {
                foreach ($itemData as $item)
                {
                    if ($item->value_type == "revenue" && $revenue == null) {
                        $revenue = $item->value;
                    }

                    if ($item->value_type == "ot" && $ot == null) {
                        $ot = $item->value;
                    }
                }
            }

            if ($ot == null) $ot = 0;
            if ($revenue == null) $revenue = 0;

            // Store working time
            $checkEmpWorkId = DB::table("working_details")
                ->where("employee_id", "=", $empId)
                ->where("working_date", "=", $workingDate)
                ->count();
            if ($checkEmpWorkId == 0) {
                DB::table("working_details")
                    ->insert([
                        "working_date" => $workingDate,
                        "total" => $total,
                        "work_time" => $workTime,
                        "ot_time" => $otTime,
                        "revenue_work_time" => $revenue ,
                        "revenue_ot_time" => $otTime * $ot,
                        "shift_start" => $shiftStart,
                        "shift_end" => $shiftEnd,
                        "employee_id" => $empId,
                        "created_at" => Carbon::now()
                    ]);
            }

            // Income table

        }

        return response()->json([
            "error" => 0,
            "message" => "จัดเก็บข้อมูลเรียบร้อยเเล้ว"
        ]);
    }
}
