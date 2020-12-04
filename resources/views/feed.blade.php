<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel Feed Aggregator</title>
</head>

<body>
    <div id="root_component" data-feeds="{{$feeds}}" data-title="{{$title}}" data-items="{{$feed_items}}" data-active="{{$active}}" data-link="{{$link}}"></div>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js')}}"></script>
</body>

</html>