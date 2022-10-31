@props(['title','href'])

{{-- TODO data → role? --}}
<div data="menu-item" class="shadow-sm border-gray-200">
    <a href={{$href}} class="block p-4 hover:bg-gray-50">
        {{$title}}
    </a>
</div>