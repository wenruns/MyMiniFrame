<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>test</title>
</head>
<body>
@foreach($a as $k=>$vo)
    <table>
        <tr>
            <td>{$k}</td>
            <td>{!! $vo !!}</td>
        </tr>
    </table>
@endforeach

@foreach ( $a as $key =>$item ) <h2>{$key}:{$item}</h2>{isset($a[1])?"hello":"bye"}

@endforeach

@if(isset($a[456]))
    <h3>a[1]</h3>
@elseif(isset($b))
    <h3>b</h3>
@else
    <h3>none</h3>
@endif


{{--@method(dump($a))--}}

</body>
</html>
