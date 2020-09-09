<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ST Seo</title>
        <link rel="stylesheet" href="{{mix('')}}">
    </head>
    <body>
    <div id="app">
        <mainapp></mainapp>
    </div>
    </body>
    <script src="{{mix('/js/app.js')}}"></script>
</html>
