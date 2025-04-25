<div>
    <li>
        <!-- Current: "bg-indigo-700 text-white", Default: "text-indigo-200 hover:text-white hover:bg-indigo-700" -->
        <a href="{{$attributes->get('href')}}"
           @if(str_contains(url()->current(),$attributes->get('href')))
               class="group flex gap-x-3 rounded-md bg-indigo-700 p-2 text-sm/6 font-semibold text-white">
           @else
                class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-indigo-200 hover:bg-indigo-700 hover:text-white">
           @endif
            {{$slot}}
        </a>
    </li>
</div>
