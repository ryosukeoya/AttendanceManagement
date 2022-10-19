@props(['headingTitle','action','method'])

<div class="mt-8 flex flex-col justify-end max-w-5xl m-auto">
    <h1 class="text-2xl pb-4">{{$headingTitle}}</h1>
    {{-- TODO: ライブラリに変更、ブラウザ対応 --}}
    <form action={{$action}} method={{$method}}>
        @csrf
        {{-- TODO --}}
        <input type="time" name="time" class="block mb-4">
        <x-primary-button>送信</x-primary-button>
    </form>
</div>