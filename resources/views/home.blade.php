<x-app-layout>
    <div class="mt-8 flex flex-col justify-end max-w-5xl m-auto bg-white">
        <x-menu-item title="勤務開始" href="{{route('attendance_record.create')}}"></x-menu-item>
        {{-- TODO attendance_record --}}
        <x-menu-item title="勤務終了" href="{{route('attendance_record.edit',['attendance_record' => 2])}}"></x-menu-item>
        <x-menu-item title="勤務履歴" href="{{route('attendance_record.index')}}"></x-menu-item>
    </div>
</x-app-layout>