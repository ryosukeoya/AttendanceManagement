<x-app-layout>
    <ul class="mt-8 flex flex-col justify-end max-w-5xl m-auto bg-white">
        <x-menu-item id="workingStart" title="勤務開始" href="{{route('attendance_record.start')}}"></x-menu-item>
        <x-menu-item id="workingEnd" title="勤務終了" href="{{route('attendance_record.end')}}"></x-menu-item>
        <x-menu-item id="workingHistory" title="勤務履歴" href="{{route('attendance_record.index')}}"></x-menu-item>
    </ul>
</x-app-layout>