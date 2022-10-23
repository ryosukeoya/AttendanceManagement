<x-app-layout>
    <x-primary-wrapper>
        <x-primary-form headingTitle="勤務開始" action="{{route('attendance_record.store')}}" method="POST" />
        <x-historyBackLink />
    </x-primary-wrapper>
</x-app-layout>