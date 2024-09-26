<head>
    <meta charset="utf-8"/>
    <title>
        @if(isset($title))
            {{ $title }}&nbsp;-&nbsp;
        @endif
        {{ config('app.name') }}
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/man.png') }}">
    <link href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-heatmap@0.2.0/dist/leaflet-heatmap.min.css" />
   <script src="//unpkg.com/alpinejs" defer></script>
   <meta name="csrf-token" content="{{ csrf_token() }}">
   {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/5.1.0/introjs.min.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/5.1.0/intro.min.js"></script>
   <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
   {{-- <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script> --}}
<!-- Bootstrap CSS -->
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

<!-- FontAwesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Chart.js for graphs -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


   {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}

   @livewireStyles


</head>
