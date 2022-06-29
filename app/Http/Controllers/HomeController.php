<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $countEmployees = DB::table("employees")->count();
        $countIncreaseLists = DB::table("increase_lists")->count();
        $countDeductionLists = DB::table("deduction_lists")->count();
        $countItemLists = DB::table("items")->count();
        $currentPath = "";
        return view("home",
            compact("currentPath",
                "countEmployees",
                "countIncreaseLists",
                "countDeductionLists",
                "countItemLists"
            )
        );
    }

    public function profile()
    {
        $currentPath = "profile";
        return view("profile", compact("currentPath"));
    }
}
