<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Album example for Bootstrap</title>

    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/main.6.css') }}">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/simple-line-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/landing-page.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>

<body>

@include('layouts.nav')

<main role="main">

    @if($flash = session('message'))
        <div class="alert alert-success close fade in" data-dismiss="alert" role="alert"
             style="position: absolute; z-index: 10; bottom: 20px; right: 20px">
            {{ $flash }}
        </div>
    @elseif($flash = session('error'))
        <div class="alert alert-danger close fade in" data-dismiss="alert" role="alert" style="position: absolute; z-index: 10; bottom: 20px; right: 20px">
            {{ $flash }}
        </div>
    @endif
    <div class="container">
        @yield('content')
    </div>

</main>

@include('layouts.footer')

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script type="text/javascript" src="{{ asset('/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/holder.min.js') }}"></script>
<script>
    Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
    });
</script>
</body>
</html>
