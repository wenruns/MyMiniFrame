@foreach($data as $k =>$item)
    @if(is_array($item))
        @include('exception/arr',['data'=>$item])
    @else
        <tr>
            <td style="box-sizing: border-box; padding: 0px 10px;width: 245px;">{{$k}}</td>
            <td>{{$item}}</td>
        </tr>
    @endif
@endforeach