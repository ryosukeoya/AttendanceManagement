<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AttendanceRecord;

class TopController extends Controller
{
    public function index()
    {
        // TODO hoge
        $hoge = true;
        return view('home', compact('hoge'));
    }

    // TODO Rename, 場所変える
    public function foo()
    {
        $user = User::find(1);
        $records = $user->attendanceManagement()->where('start_time', 'like', '%2022-10-24%');
        return response()->json(['isReported' => isset($records)]);
    }
}
