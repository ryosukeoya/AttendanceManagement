<x-app-layout>
    <div class="mt-8 flex flex-col justify-end max-w-5xl m-auto bg-white">
        <x-menu-item id="workingStart" title="勤務開始" href="{{route('attendance_record.create')}}"></x-menu-item>
        {{-- TODO attendance_record --}}
        <x-menu-item id="workingEnd" title="勤務終了" href="{{route('attendance_record.edit',['attendance_record' => 1])}}"></x-menu-item>
        <x-menu-item id="workingHistory" title="勤務履歴" href="{{route('attendance_record.index')}}"></x-menu-item>
    </div>
</x-app-layout>