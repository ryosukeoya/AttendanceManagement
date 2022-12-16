<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Entities\TodayAttendanceRecord;
use App\AttendanceStatusFactory;
use App\Queries\AttendanceRecordQuery;
use App\Http\Requests\AttendanceRecordRequest;
use App\Models\AttendanceRecord;

class AttendanceRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        return view('history');
    }

    /**
     * Display the start page
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function start()
    {
        $attendanceStatus = AttendanceStatusFactory::create();

        if (!$attendanceStatus->canRegisterToStartWork()) {
            return view('started');
        }

        return view('start');
    }

    /**
     * Display the end page
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function end()
    {
        $user = Auth::user();

        $attendanceStatus = AttendanceStatusFactory::create();

        if (!$attendanceStatus->canRegisterForEndOfWork()) {
            return view('ended');
        }

        $todayStartedRecord = AttendanceRecordQuery::getTodayStartedRecord($user);
        $startTimeStr = $todayStartedRecord->start_time->toTimeString();

        return view('end', ['startTime' => $startTimeStr]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\AttendanceRecordRequest $request
     * @return \Illuminate\Http\RedirectResponse
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
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit()
    {
        return view('end');
    }

    /**
     * Updates the record of the most recent start of the day in storage.
     *
     * @param  App\Http\Requests\AttendanceRecordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AttendanceRecordRequest $request)
    {
        $user = \Auth::user();

        $todayStartedRecord = AttendanceRecordQuery::getTodayStartedRecord($user);
        $endTime = new Carbon($request->time);
        $todayAttendanceRecord = new TodayAttendanceRecord($todayStartedRecord->start_time, $endTime);
        $todayStartedRecord->update([
            'end_time' => $todayAttendanceRecord->getEndTime(),
        ]);
        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        AttendanceRecord::where('id', $id)->delete();
        return redirect()->route('home');
    }
}
