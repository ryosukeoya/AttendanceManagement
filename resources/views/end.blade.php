<x-app-layout>
    <x-primary-content-wrapper>
        <x-primary-form headingTitle="勤務終了" action="{{route('attendance_record.update')}}" method="PATCH" />
        <x-backLink />
    </x-primary-content-wrapper>
</x-app-layout>