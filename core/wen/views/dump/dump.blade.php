<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <style>
        .one-group-data {
            background: #444;
            color: lawngreen;
            padding: 10px;
            margin-top: 10px;
        }

        * {
            padding: 0px;
            margin: 0px;
            list-style: none;
        }
    </style>
</head>
<body>
@foreach($data as $key =>$var)
    <section class="one-group-data">
        @include('dump/arr',['data'=>$var,'enter'=>true])
    </section>
@endforeach
</body>
</html>