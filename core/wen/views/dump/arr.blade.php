@if(is_array($data))
    @if(count($data))
        @if($enter)
            <p>{{gettype($data)}}({{count($data)}}) {</p>
        @else
            <span>{{gettype($data)}}({{count($data)}}) {</span>
        @endif
        <ul>
            @foreach($data as $k=>$vo)
                @if(is_array($vo))
                    @if(empty($vo))
                        <li class="arr-next"><span>"{{$k}}"</span> <span class="arrowhead"></span> array(0){}</li>
                    @else
                        <li class="arr-next">
                            <span>"{{$k}}"</span><span
                                    class="arrowhead"></span></span> @include('dump/arr',['data'=>$vo,'enter'=>false])
                        </li>
                    @endif
                @else
                    <li class="arr-next">
                        "{{$k}}" <span class="arrowhead"></span></span>
                        @switch(gettype($vo))
                            @case('integer')
                            int({{strlen($vo)}}) {{$vo}}
                            @break
                            @case('object')
                            {{var_dump($vo)}}
                            @break
                            @case('boolean')
                            boolean({{$vo ? 'true' : 'false'}})
                            @break;
                            @case('NULL')
                            NULL
                            @break;
                            @default
                            {{gettype($vo)}}({{strlen($vo)}}) "{{$vo}}"
                        @endswitch
                    </li>
                @endif
            @endforeach
        </ul>
        @if(is_array($data) && $enter)
            <li>}</li>
        @elseif(is_array($data))
            <li class="arr-next">}</li>
        @endif
    @else
        <span>{{gettype($data)}}({{count($data)}}) {}</span>
    @endif
@else
    <div class="one-string-data">
        @switch(gettype($data))
            @case('integer')
            int({{strlen($data)}}) {{$data}}
            @break
            @case('object')
            {{var_dump($data)}}
            @break
            @case('boolean')
            boolean({{$data ? 'true' : 'false'}})
            @break;
            @case('NULL')
            @case('null')
            NULL
            @break;
            @default
            {{gettype($data)}}({{strlen($data)}}) "{{$data}}"
        @endswitch
    </div>
@endif
