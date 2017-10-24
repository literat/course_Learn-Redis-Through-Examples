<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

    </head>

    <body>
        <h1>Some Video</h1>

        <p>
            This video has been downloaded {{ $downloads ?? 'no' }} times;
        </p>

    </body>

</html>
