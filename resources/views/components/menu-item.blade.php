@props(['title','href'])

<div class="shadow-sm border-gray-200 cursor-pointer">
    <a href={{$href}} class="block p-4 hover:bg-gray-50">
        {{$title}}
    </a>
</div>