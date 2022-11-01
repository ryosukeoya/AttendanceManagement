<x-app-layout>
    <x-primary-wrapper>
        {{-- TODO 1 --}}
        {{-- TODO Refactor --}}
        {{--
        <x-primary-form headingTitle="勤務終了" action="{{route('attendance_record.update',[1])}}" method="PATCH" /> --}}
        <form action="{{route('attendance_record.update',[1])}}" method="POST">
            @method('PATCH')
            @csrf
            {{-- TODO --}}
            <input type="time" name="time" class="block mb-4">
            <x-primary-button>送信</x-primary-button>
        </form>
        <x-historyBackLink />
    </x-primary-wrapper>
</x-app-layout>