<x-app-layout>
    <x-primary-content-wrapper>
        {{-- TODO 1 --}}
        <x-primary-form headingTitle="勤務終了" action="{{route('attendance_record.update',[1])}}" method="PATCH" />
        <x-backLink />
    </x-primary-content-wrapper>
</x-app-layout>