@if(is_array($data))
    @if($enter)
        <p>{{gettype($data)}}({{count($data)}}) {</p>
    @else
        <span>{{gettype($data)}}({{count($data)}}) {</span>
    @endif
    <ul>
        @foreach($data as $k=>$vo)
            @if(is_array($vo))
                @if(empty($vo))
                    <li class="arr-next">"{{$k}}" => array(0){}</li>
                @else
                    <li class="arr-next">
                        "{{$k}}" => @include('dump/arr',['data'=>$vo,'enter'=>false])
                    </li>
                @endif
            @else
                <li class="arr-next">
                    "{{$k}}" =>
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
                        @default
                        {{gettype($vo)}}({{strlen($vo)}}) "{{$vo}}"
                    @endswitch
                </li>
            @endif
        @endforeach
    </ul>
    @if(is_array($data)&&$enter)
        <li>}</li>
    @elseif(is_array($data))
        <li class="arr-next">}</li>
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
            @default
            {{gettype($data)}}({{strlen($data)}}) "{{$data}}"
        @endswitch
    </div>
@endif

<style>
    .arr-next {
        margin-left: 30px;
    }
</style>
