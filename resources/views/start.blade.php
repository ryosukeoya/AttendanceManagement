<x-app-layout>
    <x-primary-content-wrapper>
        <x-primary-form headingTitle="勤務開始" action="{{route('attendance_record.store')}}" method="POST" />
        <x-backLink />
    </x-primary-content-wrapper>
</x-app-layout>