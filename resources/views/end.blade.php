<x-app-layout>
    <x-primary-content-wrapper>
        {{-- TODO Refactor --}}
        <div class="flex flex-col justify-end max-w-5xl m-auto">
            <h1 class="text-xl pb-4">勤務終了</h1>
            {{-- TODO: ライブラリに変更、ブラウザ対応 --}}
            <form action={{route('attendance_record.update')}} method="POST">
                @method("PATCH")
                @csrf

                <input type="time" required min={{ $startTime }} name="time" class="block mb-4">
                @error('time')
                <p class="text-red-500">{{ $message }}</p>
                @enderror
                <x-primary-button>送信</x-primary-button>
            </form>
        </div>
        <x-backLink />
    </x-primary-content-wrapper>
</x-app-layout>