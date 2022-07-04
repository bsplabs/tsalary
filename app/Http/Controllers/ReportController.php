<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

header('Content-Type: text/html; charset=utf-8');

class ReportController extends Controller
{
    public function showExports()
    {
        $getMonth = DB::table("working_details")
            ->select("working_date")
            ->distinct()
            ->get();

        $sql = "SELECT DISTINCT(YEAR(working_date)) AS work_year FROM working_details";
        $yearLists = DB::select($sql);

        $currentPath = "export";
        return view("reports.export", compact("currentPath", "yearLists"));
    }

    public function generalReport()
    {
        $month = 6;
        $year = 2022;

        $fileName = "รายงานรายได้พนักงาน_" . $year . ".xlsx";

        // code, name_th, type, enroll_no, bank_name, bank_account, tell, work_day, revenue_work_day
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle("A1:P1")->getAlignment()->setVertical("center");
        $sheet->setCellValue('A1', "รหัสพนักงาน");
        $sheet->setCellValue('B1', "ชื่อพนักงาน");
        $sheet->setCellValue('C1', "ประเภท");
        $sheet->setCellValue('D1', "Enroll No.");
        $sheet->setCellValue('E1', "ธนาคาร");
        $sheet->setCellValue('F1', "เลขบัญชี");
        $sheet->setCellValue('G1', "เบอร์โทร");
        $sheet->setCellValue('H1', "วันทำงานทั้งหมด");
        $sheet->setCellValue('I1', "รายได้จากวันทำงานทั้งหมด");
        $sheet->setCellValue('J1', "โอทีทั้งหมด");
        $sheet->setCellValue('K1', "รายได้จากโอทีทั้งหมด");
        $sheet->setCellValue('L1', "รายการเพิ่มทั้งหมด");
        $sheet->setCellValue('M1', "รายการหักทั้งหมด");
        $sheet->setCellValue('N1', "รายการเพิ่มประจำเดือน");
        $sheet->setCellValue('O1', "รายการหักประจำเดือน");
        $sheet->setCellValue('P1', "รายได้ทั้งหมด");

        $sumEmpWorkTime = DB::table("working_details")
            ->selectRaw("
                employee_id,
                count(working_details.id) AS work_days,
                sum(work_time) as total_work_time,
                sum(revenue_work_time) as total_revenue_work_time,
                sum(ot_time) as total_ot,
                sum(revenue_ot_time) as total_revenue_ot")
            ->whereYear("working_date", "=", $year)
            ->whereMonth("working_date", "=", $month)
            ->groupBy("employee_id")
            ->get();

        // get all items lists
        $allItemLists = DB::table("items")
            ->where("value_type", "=", "other")
            ->get();

        $itemsContinue = [
            "increase" => 0,
            "deduction" => 0
        ];

        $itemsDiscontinue = [
            "increase" => 0,
            "deduction" => 0
        ];

        foreach ($allItemLists as $item)
        {
            if ($item->is_continued == 1) {
                if ($item->type === "increase") {
                    $itemsContinue["increase"] += (int)$item->value;
                } else {
                    $itemsContinue["deduction"] += (int)$item->value;
                }
            } elseif ($item->is_continued == 0) {
                if ($item->type === "increase") {
                    $itemsDiscontinue["increase"] += (int)$item->value;
                } else {
                    $itemsDiscontinue["deduction"] += (int)$item->value;
                }
            }
        }

        error_log("---------------->");
        $i = 2;
        foreach ($sumEmpWorkTime as $sumEmp)
        {
            $empId = $sumEmp->employee_id;

            error_log(print_r($sumEmpWorkTime, true));
            $workDays = $sumEmp->work_days;
            $totalOT = $sumEmp->total_ot;
            $totalRevenueWorkTime = $sumEmp->total_revenue_work_time;
            $totalRevenueOT = $sumEmp->total_revenue_ot;

            $increaseTotal = DB::table("increase_lists")
                ->selectRaw("employee_id, sum(item_value) as total_increase_value")
                ->where("employee_id", $empId)
                /*->where("item_type", "=", "other")*/
                ->whereYear("item_date", "=", $year)
                ->whereMonth("item_date", "=", $month)
                ->groupBy("employee_id")
                ->get();

            $totalIncValue = 0;
            if ($increaseTotal->count()) {
                $totalIncValue = $increaseTotal[0];
                $totalIncValue = $totalIncValue->total_increase_value;
            }

            $deductionTotal = DB::table("deduction_lists")
                ->selectRaw("employee_id, sum(item_value) as total_deduction_value")
                ->where("employee_id", $sumEmp->employee_id)
                /*->where("item_type", "=", "other")*/
                ->whereYear("item_date", "=", $year)
                ->whereMonth("item_date", "=", $month)
                ->groupBy("employee_id")
                ->get();

            $totalDeductValue = 0;
            if ($deductionTotal->count()) {
                $totalDeductValue = $deductionTotal[0];
                $totalDeductValue = $totalDeductValue->total_deduction_value;
            }

            // items increase/deduct
            $totalItemIncrease = (int)$itemsDiscontinue["increase"] + ((int)$itemsContinue["increase"] * (int)$workDays);
            $totalItemDeduction = (int)$itemsDiscontinue["deduction"] + ((int)$itemsContinue["deduction"] * (int)$workDays);

            $totalIncrease = $totalRevenueWorkTime + $totalRevenueOT + $totalIncValue + $totalItemIncrease;
            $totalDeduction = $totalDeductValue + $totalItemDeduction;

            $totalIncome = $totalIncrease - $totalDeduction;

            // Get emp info
            $empInfo = DB::table("employees")->where("id", "=", $empId);
            error_log(" after ---------------> ");
            if (!$empInfo->count()) continue;
            $empInfo = $empInfo->get()[0];

            $empType = "ชั่วคราว";
            if ($empInfo->type === "permanent") {
                $empType = "ประจำ";
            }

            // set document
            $sheet->setCellValue("A{$i}", $empInfo->code);
            $sheet->setCellValue("B{$i}", $empInfo->name_th);
            $sheet->setCellValue("C{$i}", $empType);
            $sheet->setCellValue("D{$i}", $empInfo->enroll_no);
            $sheet->setCellValue("E{$i}", $empInfo->bank_name);
            $sheet->setCellValue("F{$i}", $empInfo->bank_account_number);
            $sheet->setCellValue("G{$i}", $empInfo->tel);
            $sheet->setCellValue("H{$i}", $workDays);
            $sheet->setCellValue("I{$i}", $totalRevenueWorkTime);
            $sheet->setCellValue("J{$i}", $sumEmp->total_ot);
            $sheet->setCellValue("K{$i}", $totalRevenueOT);
            $sheet->setCellValue("L{$i}", $totalIncValue);
            $sheet->setCellValue("M{$i}", $totalDeductValue);
            $sheet->setCellValue("N{$i}", $totalItemIncrease);
            $sheet->setCellValue("O{$i}", $totalItemDeduction);
            $sheet->setCellValue("P{$i}", $totalIncome);

            $i++;
        }

        foreach (range("A", "P") as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle('A:P')->getAlignment()->setHorizontal('center');

        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(20);

        $writer = new Xlsx($spreadsheet);
        header('Content-Encoding: UTF-8');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    public function increaseReport(Request $request)
    {
        $year = $request->input("year");
        $month = $request->input("month");

        $fileName = "รายการเพิ่ม_{$month}_{$year}.xlsx";

        $increaseLists = DB::table("increase_lists")
            ->leftJoin("employees AS emp", "emp.id", "=", "increase_lists.employee_id")
            ->select("emp.id AS empId", "emp.code", "emp.name_th", "increase_lists.*")
            ->whereYear("item_date", "=", $year)
            ->whereMonth("item_date", "=", $month)
            ->orderBy("empId", "asc")
            ->orderBy("item_date", "asc")
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle("A1:P1")->getAlignment()->setVertical("center");
        $sheet->setCellValue('A1', "รหัสพนักงาน");
        $sheet->setCellValue('B1', "ชื่อพนักงาน");
        $sheet->setCellValue('C1', "รายการ");
        $sheet->setCellValue('D1', "จำนวน");
        $sheet->setCellValue('E1', "วันที่");

        $i = 2;
        foreach ($increaseLists as $key => $item) {
            $sheet->setCellValue("A{$i}", $item->code);
            $sheet->setCellValue("B{$i}", $item->name_th);
            $sheet->setCellValue("C{$i}", $item->item_name);
            $sheet->setCellValue("D{$i}", $item->item_value);
            $sheet->setCellValue("E{$i}", $item->item_date);

            $i++;
        }

        foreach (range("A", "E") as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle('A:E')->getAlignment()->setHorizontal('center');

        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(20);

        $writer = new Xlsx($spreadsheet);
        header('Content-Encoding: UTF-8');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    public function deductionReport(Request $request)
    {
        $year = $request->input("year");
        $month = $request->input("month");

        $fileName = "รายการหัก_{$month}_{$year}.xlsx";

        $increaseLists = DB::table("deduction_lists")
            ->leftJoin("employees AS emp", "emp.id", "=", "deduction_lists.employee_id")
            ->select("emp.id AS empId", "emp.code", "emp.name_th", "deduction_lists.*")
            ->whereYear("item_date", "=", $year)
            ->whereMonth("item_date", "=", $month)
            ->orderBy("empId", "asc")
            ->orderBy("item_date", "asc")
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle("A1:P1")->getAlignment()->setVertical("center");
        $sheet->setCellValue('A1', "รหัสพนักงาน");
        $sheet->setCellValue('B1', "ชื่อพนักงาน");
        $sheet->setCellValue('C1', "รายการ");
        $sheet->setCellValue('D1', "จำนวน");
        $sheet->setCellValue('E1', "วันที่");

        $i = 2;
        foreach ($increaseLists as $key => $item) {
            $sheet->setCellValue("A{$i}", $item->code);
            $sheet->setCellValue("B{$i}", $item->name_th);
            $sheet->setCellValue("C{$i}", $item->item_name);
            $sheet->setCellValue("D{$i}", $item->item_value);
            $sheet->setCellValue("E{$i}", $item->item_date);

            $i++;
        }

        foreach (range("A", "E") as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle('A:E')->getAlignment()->setHorizontal('center');

        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(20);

        $writer = new Xlsx($spreadsheet);
        header('Content-Encoding: UTF-8');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    public function getMonth(Request $request)
    {
        $monthTextLists = [
            "มกราคม",
            "กุมภาพันธ์",
            "มีนาคม",
            "เมษายน",
            "พฤษภาคม",
            "มิถุนายน",
            "กรกฎาคม",
            "สิงหาคม",
            "กันยายน",
            "ตุลาคม",
            "พฤศจิกายน",
            "ธันวาคม"
        ];

        $year = $request->input("year");

        error_log("Hello ---------------------------> ");

        $sql = "SELECT DISTINCT(MONTH(working_date)) AS working_month FROM working_details WHERE YEAR(working_date) = :year";
        $monthLists = DB::select($sql, ["year" => $year]);

        $months = [];
        foreach ($monthLists as $month)
        {
            $months[$month->working_month] = $monthTextLists[$month->working_month - 1];
        }

        return response()->json([
            "error" => 0,
            "data" => $months
        ]);
    }
}
