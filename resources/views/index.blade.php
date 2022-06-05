<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
        <!-- Styles -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">


    </head>
    <body class="antialiased">
        <div id="app">


            <div class="wrapper">

                <url-component></url-component>
            </div>
        </div>
    </body>

    <script src="{{  mix('js/app.js')  }}"></script>
</html>
