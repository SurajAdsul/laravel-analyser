<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Web Analyser</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:100,300,600|Roboto Mono" rel="stylesheet"
          type="text/css">
    <link href="{{ asset('css/analyser.css?v=1.0.0')}}" rel="stylesheet"/>

</head>
<body>
<div id="main" class="">

    <header class="site-header">
        <h1><span class="name">Laravel</span> <span class="description">. Web page analyser</span></h1>
        <form method="GET" action="/check" autocomplete="off" class="form form-check" id="analyser">
            <input type="url" name="url" id="site_url" placeholder="Enter your URL" required="required"
                   autofocus="autofocus">
            <input type="submit" value="Check">
        </form>
    </header>

    <div data-v-af05fa2e="" class="results">
        <ul data-v-af05fa2e="">
        </ul> <!---->
    </div>

</div>

<script type="text/html" id="template">
    <li class="item critical" data-class="passed" style="opacity: 1;">
        <div class="msg">
            <p data-content="message">
            </p>
        </div>
    </li>
</script>

<script src="{{ asset('js/jquery-3.3.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.loadTemplate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/analyser.js') }}" type="text/javascript"></script>
</body>
</html>
