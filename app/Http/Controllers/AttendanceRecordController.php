<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendanceRecordRequest;
use App\Services\AttendanceRecordService;
use App\Models\AttendanceRecord;

class AttendanceRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('history');
    }

    /**
     * Display the start page
     *
     * @return \Illuminate\Http\Response
     */
    public function start()
    {
        $user = Auth::user();

        $attendanceRecordService = new AttendanceRecordService();
        $attendanceStatus = $attendanceRecordService->getAttendanceStatus($user);

        if (!$attendanceRecordService->canStartRegister($attendanceStatus)) {
            return view('started');
        }

        return view('start');
    }

    /**
     * Display the end page
     *
     * @return \Illuminate\Http\Response
     */
    public function end()
    {
        $user = Auth::user();

        $attendanceRecordService = new AttendanceRecordService();
        $attendanceStatus = $attendanceRecordService->getAttendanceStatus($user);

        if (!$attendanceRecordService->canEndRegister($attendanceStatus)) {
            return view('ended');
        }

        return view('end');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttendanceRecordRequest $request)
    {
        $userID = Auth::id();
        AttendanceRecord::create(['user_id' => $userID, 'start_time' => $request->time]);

        return redirect()->route('home');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('end');
    }

    /**
     * Updates the record of the most recent start of the day in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(AttendanceRecordRequest $request)
    {
        $user = \Auth::user();

        $todayStartedRecord = AttendanceRecordService::getTodayStartedRecord($user);
        $endTime = new Carbon($request->time);

        if ($endTime <= $todayStartedRecord->start_time) {
            \Log::error('終了時刻が開始時刻以前の値で登録されています : ', [
                'user_id' => $user->id,
            ]);
        }

        $todayStartedRecord->update([
            'end_time' => $request->time,
        ]);
        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AttendanceRecord::where('id', $id)->delete();
        return redirect()->route('home');
    }
}
