
    <a href="{{$attributes->get('href')}}"
       @if(str_contains(url()->current(),$attributes->get('href')))
           class="bg-primary"
        @else
            class=""
        @endif
        >
        {{$slot}}
    </a>

