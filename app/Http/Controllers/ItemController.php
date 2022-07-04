<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ItemController extends Controller
{
    public function __construct()
    {
        error_log("Item Controller");
    }

    public function showIncreaseLists()
    {
        $currentPath = "increase";
        return view("items.increase", compact("currentPath"));
    }

    /* Increase Item Lists */
    public function getIncreaseLists(Request $request)
    {
        $searchKw = $request->input("search")["value"];
        $length = $request->input("length");
        $start = $request->input("start");

        if ($searchKw == "") {
            $increaseListsQry = DB::table("increase_lists")
                ->leftJoin("employees", "increase_lists.employee_id", "=", "employees.id")
                ->select("employees.id AS emp_id", "employees.code", "employees.name_th", "increase_lists.*")
                ->orderBy("id", "desc");
            $total = $increaseListsQry->count();
            $totalFiltered = $total;
            $increaseLists = $increaseListsQry->skip($start)->take($length)->get();
        } else {
            $increaseListsQry = DB::table("increase_lists")
                ->leftJoin("employees", "increase_lists.employee_id", "=", "employees.id")
                ->select("employees.id AS emp_id", "employees.code", "employees.name_th", "increase_lists.*")
                ->where("employees.code", "LIKE", "{$searchKw}%")
                ->orWhere("employees.name_th", "LIKE", "{$searchKw}%")
                ->orWhere("increase_lists.item_name", "LIKE", "{$searchKw}%")
                ->orderBy("id", "desc");
            $total = $increaseListsQry->count();
            $totalFiltered = $total;
            $increaseLists = $increaseListsQry->skip($start)->take($length)->get();
        }

        $resData = [
            "data" => $increaseLists,
            "recordsTotal" => $total,
            "recordsFiltered" => $totalFiltered
        ];

        return response()->json($resData);
    }

    public function getEmployees(Request $request)
    {
        $kw = $request->input("term");
        $empData = [];
        if (!empty($kw)) {
            $empData = DB::table("employees")
                ->where("code", "LIKE", "{$kw}%")
                ->orWhere("name_th", "LIKE", "{$kw}%")
                ->take(50)
                ->get();
        }

        $empSelection = [];
        foreach ($empData as $emp) {
            $empSelection[] = array("id" => $emp->id, "text" => $emp->name_th);
        }

        $resData = [
            "results" => $empSelection
        ];

        return response()->json($resData);
    }

    public function addIncreaseItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "employee_id" => "required|numeric",
            "item_name" => "required",
            "item_value" => "required|numeric",
            "item_date" => "required|date_format:d/m/Y",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => 1,
                "messages" => $validator->errors()
            ]);
        }

        $empId = $request->input("employee_id");
        $itemDate = Carbon::createFromFormat(
            "d/m/Y",
            $request->input("item_date"))->format("Y-m-d"
        );

        $itemName = $request->input("item_name");
        if (empty($itemName)) {
            return response()->json([
                "error" => 2,
                "message" => "ต้องระบุรายการเพิ่ม"
            ]);
        }

        $increaseItem = [
            "employee_id" => $empId,
            "item_name" => $itemName,
            "item_value" => $request->input("item_value"),
            "item_date" => $itemDate,
            "created_at" => Carbon::now()
        ];

        DB::table("increase_lists")->insert($increaseItem);

        return response()->json([
            "error" => 0,
            "message" => "สร้างรายการเพิ่มเเล้ว"
        ]);
    }

    public function editIncreaseItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "item_name" => "required",
            "item_value" => "required|numeric",
            "item_date" => "required|date_format:d/m/Y"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => 1,
                "messages" => $validator->errors()
            ]);
        }

        $increaseData = [];
        if (!empty($request->input("item_name"))) {
            $increaseData["item_name"] = $request->input("item_name");
        }
        if (!empty($request->input("item_value"))) {
            $increaseData["item_value"] = $request->input("item_value");
        }
        if (!empty($request->input("item_date"))) {
            $increaseData["item_date"] = Carbon::createFromFormat(
                "d/m/Y",
                $request->input("item_date"))->format("Y-m-d"
            );
        }

        $increaseData["updated_at"] = Carbon::now();
        $id = $request->input("id");
        if (!empty($id)) {
            DB::table("increase_lists")
                ->where("id", $id)
                ->update($increaseData);

            return response()->json([
                "error" => 0,
                "message" => "แก้ไข้รายการเพิ่มเเล้ว"
            ]);
        } else {
            return response()->json([
                "error" => 2,
                "message" => "ไอดี ไม่ถูกต้อง"
            ]);
        }
    }

    public function deleteIncreaseItem(Request $request)
    {
        $id = $request->input("id");
        if (isset($id)) {
            DB::table("increase_lists")->where("id", $id)->delete();
            return response()->json([
                "error" => 0,
                "message" => "ลบ รายเกินเพิ่ม เรียบร้อยเเล้ว"
            ]);
        } else {
            return response()->json([
                "error" => 1,
                "message" => "โปรดระบุ id"
            ]);
        }
    }

    /* Deduction Item Lists */
    public function showDeductionLists()
    {
        $currentPath = "deduction";
        return view("items.deduction", compact("currentPath"));
    }

    public function getDeductionLists(Request $request)
    {
        $searchKw = $request->input("search")["value"];
        $length = $request->input("length");
        $start = $request->input("start");

        if ($searchKw == "") {
            $deductionQry = DB::table("deduction_lists")
                ->leftJoin("employees", "employees.id", "=", "deduction_lists.employee_id")
                ->select("employees.id AS emp_id", "employees.code", "employees.name_th", "deduction_lists.*")
                ->orderBy("id", "desc");
            $total = $deductionQry->count();
            $totalFiltered = $total;
            $deductionLists = $deductionQry->skip($start)->take($length)->get();
        } else {
            $deductionQry = DB::table("deduction_lists")
                ->leftJoin("employees", "employees.id", "=", "deduction_lists.employee_id")
                ->select("employees.id AS emp_id", "employees.code", "employees.name_th", "deduction_lists.*")
                ->where("employees.code", "LIKE", "{$searchKw}%")
                ->orWhere("employees.name_th", "LIKE", "{$searchKw}%")
                ->orWhere("deduction_lists.item_name", "LIKE", "{$searchKw}%")
                ->orderBy("id", "desc");
            $total = $deductionQry->count();
            $totalFiltered = $total;
            $deductionLists = $deductionQry->skip($start)->take($length)->get();
        }

        $resData = [
            "data" => $deductionLists,
            "recordsTotal" => $total,
            "recordsFiltered" => $totalFiltered
        ];

        return response()->json($resData);
    }

    public function addDeductionItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "employee_id" => "required|numeric",
            "item_name" => "required",
            "item_value" => "required|numeric",
            "item_date" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => 1,
                "messages" => $validator->errors()
            ]);
        }

        $empId = $request->input("employee_id");
        $deductionData = [
            "employee_id" => $empId,
            "item_name" => $request->input("item_name"),
            "item_value" => $request->input("item_value"),
            "item_type" => "other",
            "item_date" => Carbon::createFromFormat(
                "d/m/Y",
                $request->input("item_date"))->format("Y-m-d"
            ),
            "created_at" => Carbon::now()
        ];

        DB::table("deduction_lists")->insert($deductionData);

        return response()->json([
            "error" => 0,
            "message" => "สร้าง รายการหัก เเล้ว"
        ]);
    }

    public function editDeductionItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "item_name" => "required",
            "item_value" => "required|numeric",
            "item_date" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => 1,
                "messages" => $validator->errors()
            ]);
        }

        $id = $request->input("id");
        $deductionData = [
            "item_name" => $request->input("item_name"),
            "item_value" => $request->input("item_value"),
            "item_date" => Carbon::createFromFormat(
                "d/m/Y",
                $request->input("item_date"))->format("Y-m-d"
            ),
            "updated_at" => Carbon::now()
        ];

        DB::table("deduction_lists")->where("id", $id)->update($deductionData);

        return response()->json([
            "error" => 0,
            "message" => "แก้ไข รายการหัก เเล้ว"
        ]);
    }

    public function deleteDeductionItem(Request $request)
    {
        $id = $request->input("id");
        if (!empty($id) && is_numeric($id)) {
            DB::table("deduction_lists")->where("id", "=", $id)->delete();

            return response()->json([
                "error" => 0,
                "message" => "ลบ รายการหัก เรียบร้อยเเล้ว"
            ]);
        } else {
            return response()->json([
                "error" => 1,
                "messages" => "ไอดี ไม่ถูกต้อง"
            ]);
        }
    }

    /* Item Lists */
    public function showItemLists()
    {
        $currentPath = "items";
        return view("items.list", compact("currentPath"));
    }

    public function getItemLists(Request $request)
    {
        $searchKw = $request->input("search")["value"];
        $length = $request->input("length");
        $start = $request->input("start");

        if ($searchKw == "") {
            $itemQry = DB::table("items")->orderBy("id", "asc");
            $total = $itemQry->count();
            $totalFiltered = $total;
            $items = $itemQry->skip($start)->take($length)->get();
        } else {
            $itemQry = DB::table("items")
                ->where("name", "LIKE", "{$searchKw}%")
                ->orWhere("value", "LIKE", "{$searchKw}%")
                ->orderBy("id", "asc");
            $total = $itemQry->count();
            $totalFiltered = $total;
            $items = $itemQry->skip($start)->take($length)->get();
        }

        $resData = [
            "data" => $items,
            "recordsTotal" => $total,
            "recordsFiltered" => $totalFiltered
        ];

        return response()->json($resData);
    }

    public function addItem(Request $request)
    {
        $type = $request->input("type");
        $name = $request->input("name");
        $value = $request->input("value");
        $isContinue = $request->input("is_continue");

        $validateBox = [
            "type" => "required|in:increase,deduct",
            "name" => "required",
            "value" => "required|numeric",
            "is_continue" => "required|in:0,1"

        ];
        $validator = Validator::make($request->all(), $validateBox);
        if ($validator->fails()) {
          return response()->json([
              "error" => 1,
              "messages" => $validator->errors()
          ]);
        }

        // Check revenue and ot
        if ($name == "รายได้ต่อวัน") {
            return response()->json([
                "error" => 2,
                "message" => "มีกาารสร้างรายการ รายได้ต่อวัน ไปเเล้ว"
            ]);
        } elseif ($name == "โอที") {
            return response()->json([
                "error" => 2,
                "message" => "มีกาารสร้างรายการ โอที ไปเเล้ว"
            ]);
        }

        $itemData = [
            "name" => $name,
            "type" => $type,
            "value" => $value,
            "is_continued" => $isContinue,
            "created_at" => Carbon::now()
        ];

        DB::table("items")
            ->insert($itemData);

        return response()->json([
            "error" => 0,
            "message" => "สร้าง รายการหัก/เพิ่ม เรียบร้อยเเล้ว"
        ]);
    }

    public function editItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
           "value" => "required|numeric"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => 1,
                "messages" => $validator->errors()
            ]);
        }

        $id = $request->input("id");
        $name = $request->input("name");
        $value = $request->input("value");

        $itemUpdate = [];
        if (empty($name)) {
            $itemUpdate["value"] = $value;
        } else {
            $itemUpdate["name"] = $name;
            $itemUpdate["value"] = $value;
        }
        $itemUpdate["updated_at"] = Carbon::now();

        DB::table("items")
            ->where("id", "=", $id)
            ->update($itemUpdate);

        return response()->json([
           "error" => 0,
           "message" => "แก้ไข รายการหัก/เพิ่ม เรียบร้อยเเล้ว"
        ]);
    }

    public function deleteItem(Request $request)
    {
        $id = $request->input("id");
        if (!empty($id) && is_numeric($id)) {
            DB::table("items")->where("id", "=", $id)->delete();

            return response()->json([
                "error" => 0,
                "message" => "ลบ รายการหัก/เพิ่ม เรียบร้อยเเล้ว"
            ]);
        } else {
            return response()->json([
                "error" => 1,
                "messages" => "ไอดี ไม่ถูกต้อง"
            ]);
        }
    }

    public function updateTimeType(Request $request)
    {
        $id = $request->input("id");
        $timeType = $request->input("isContinued");

        $validator = Validator::make($request->all(), [
            "id" => "required|numeric",
            "isContinued" => "required|in:0,1"
        ]);

        if ($validator->fails()) {
            return response()->json([
               "error" => 1,
               "messages" => $validator->errors()
            ]);
        }

        $isContinued = !$timeType;

        DB::table("items")
            ->where("id", "=", $id)
            ->update([
                "is_continued" => $isContinued,
                "updated_at" => Carbon::now()
            ]);

        return response()->json([
            "error" => 0,
            "message" => "เปลี่ยนสถานะเวลาของรายการหัก/เพิ่มเเล้ว"
        ]);
    }
}
