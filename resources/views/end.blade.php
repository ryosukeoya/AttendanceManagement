<x-app-layout>
    <x-primary-wrapper>
        {{-- TODO 1 --}}
        <x-primary-form headingTitle="勤務終了" action="{{route('attendance_record.update',[1])}}" method="PATCH" />
        <x-backLink />
    </x-primary-wrapper>
</x-app-layout>