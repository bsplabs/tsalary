<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function index()
    {
        /*$employees = DB::table("employees")->paginate(1);*/
        $currentPath = "employee";
        return view("employee", compact("currentPath"));
    }

    public function addEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "code" => "required|unique:employees",
            "enroll_no" => "required",
            "name_th" => "required|unique:employees",
            "type" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => 1,
                "errorMessages" => $validator->errors()
            ]);
        }

        $empData = $request->all();

        DB::table("employees")->insert([
            "code" => $empData["code"],
            "enroll_no" => $empData["enroll_no"],
            "name_th" => $empData["name_th"],
            "name_en" => $empData["name_en"],
            "type" => $empData["type"],
            "bank_name" => $empData["bank_name"],
            "bank_account_number" => $empData["bank_account_number"],
            "tel" => $empData["tel"],
            "created_at" => Carbon::now()
        ]);

        return response()->json([
            "error" => 0,
            "messages" => "เพิ่มข้อมูลพนักงานเรียบร้อยเเล้ว"
        ]);
    }

    public function editEmp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "code" => "required|unique:employees,code," . $request->input("id"),
            "enroll_no" => "required",
            "name_th" => "required|unique:employees,name_th," . $request->input("id"),
            "type" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => 1,
                "errorMessages" => $validator->errors()
            ]);
        }

        $empData = $request->all();

        DB::table("employees")
            ->where("id", $empData["id"])
            ->update([
                "code" => $empData["code"],
                "enroll_no" => $empData["enroll_no"],
                "name_th" => $empData["name_th"],
                "name_en" => $empData["name_en"],
                "type" => $empData["type"],
                "bank_name" => $empData["bank_name"],
                "bank_account_number" => $empData["bank_account_number"],
                "tel" => $empData["tel"],
                "updated_at" => Carbon::now()
            ]);

        return response()->json([
            "error" => 0,
            "messages" => "แก้ไขข้อมูลพนักงานเรียบร้อยเเล้ว"
        ]);

    }

    public function deleteEmp(Request $request)
    {
        $id = $request->input("id");
        DB::table("employees")->where("id", $id)->delete();

        // delete foreign
        DB::table("working_details")
            ->where("employee_id", "=", $id)
            ->delete();

        DB::table("increase_lists")
            ->where("employee_id", "=", $id)
            ->delete();

        DB::table("deduction_lists")
            ->where("employee_id", "=", $id)
            ->delete();

        return response()->json([
            "success" => 1,
            "messages" => "ลบข้อมูลพนักงานเเล้ว"
        ]);
    }

    public function getEmployees(Request $request)
    {
        $searchKw = $request->input("search")["value"];
        error_log(print_r($searchKw, true));
        $length = $request->input("length");
        $start = $request->input("start");

        $total = DB::table("employees")->count();
        $totalFiltered =$total;
        if ($searchKw == "") {
            $empData = DB::table("employees")->skip($start)->take($length)->get();
        } else {
            $empDataQry = DB::table("employees")
                ->where("code", "LIKE", "{$searchKw}%")
                ->orWhere("name_th", "LIKE", "{$searchKw}%")
                ->orWhere("tel", "LIKE", "{$searchKw}%");
            $totalFiltered = $empDataQry->count();
            $empData = $empDataQry->skip($start)->take($length)->get();
        }


        $res = [
            "data" => $empData,
            "recordsTotal" => $total,
            "recordsFiltered" => $totalFiltered
        ];
        error_log(print_r($empData, true));

        return response()->json($res);
    }
}
