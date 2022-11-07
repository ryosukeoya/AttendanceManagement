@props(['headingTitle','action','method' => 'POST', 'maxTime'])

<div class="flex flex-col justify-end max-w-5xl m-auto">
    <h1 class="text-xl pb-4">{{$headingTitle}}</h1>
    {{-- TODO: ライブラリに変更、ブラウザ対応 --}}
    <form action={{$action}} method="POST">
        @method($method)
        @csrf

        <input type="time" required name="time" class="block mb-4">
        @error('time')
        <p class="text-red-500">{{ $message }}</p>
        @enderror
        <x-primary-button>送信</x-primary-button>
    </form>
</div>