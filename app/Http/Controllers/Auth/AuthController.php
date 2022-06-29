<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except(["logout", "resetPassword"]);
    }

    public function showLogin()
    {
        return view("auth.login");
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "username" => "required",
            "password" => "required"
        ]);

        if ($validator->fails()) {
            return redirect("/login")->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if (Auth::attempt($validated)) {
            return redirect()->intended("/");
        } else {
            return back()->withErrors(["errorLogin" => "อีเมลหรือรหัสผ่านของคุณไม่ถูกต้อง"])->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function resetPassword(Request $request)
    {
        $id = Auth::user()->id;
        $newPassword = $request->input("newPassword");
        error_log(" reset password ------------------------> ");
        error_log(print_r($id, true));
        error_log(print_r($newPassword, true));
        if (isset($newPassword) && $newPassword != "") {
            DB::table("users")
                ->where("id", $id)
                ->update([
                    "password" => Hash::make($newPassword)
                ]);

            return redirect("/profile")->with("success", "เปลี่ยนรหัสผ่านเเล้ว");

            /*return response()->json([
                "error" => 0,
                "message" => "เปลี่ยนรหัสผ่านเรียบร้อยเเล้ว"
            ]);*/
        } else {
            return back()->withErrors(["newPassword" => "กรุณากรอกรหัสผ่านที่ต้องการเปลี่ยน"]);
            /*return response()->json([
                "error" => 1,
                "message" => "กรุณากรอกรหัสผ่านที่ต้องการเปลี่ยน"
            ]);*/
        }

    }
}
