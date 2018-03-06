<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">

    <title>Album example for Bootstrap</title>

    <link rel="stylesheet" href="/public/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>

<body>

@include('layouts.nav')

<main role="main">

    @if($flash = session('message'))
        <div class="alert alert-success" role="alert"
             style="position: absolute; z-index: 10; bottom: 20px; right: 20px">
            {{ $flash }}
        </div>
    @elseif($flash = session('error'))
        <div class="alert alert-danger" role="alert" style="position: absolute; z-index: 10; bottom: 20px; right: 20px">
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
<script src="/public/js/popper.min.js"></script>
<script src="/public/js/bootstrap.min.js"></script>
<script src="/public/js/holder.min.js"></script>
<script>
    Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
    });
</script>
</body>
</html>
