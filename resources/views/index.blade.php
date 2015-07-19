<!DOCTYPE html>
<html class="no-js">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="Is Wendell Bar (90014) in downtown Los Angeles open? Find out here or update their status.">
    <title>Is Wendell Open | DTLA 90014</title>
    <link rel="shortcut icon" href="/img/favicon.ico">
    <link rel="stylesheet" href="/css/main.css">
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-65312801-1', 'auto');
      ga('send', 'pageview');
    </script>
  </head>
  <body>
    <section class="wrapper">
      <article>
        @if (session('state'))
          <p>Updated Successfully.</p>
        @endif
        <div class="block">
          <span class="title">Currently Wendell is</span>
        </div>
        <div class="block">
          <h1>{{{ $status ? 'open' : 'closed' }}}</h1>
        </div>
        <div class="block">
          <span class="hours">{{{ $hours }}}</span>
        </div>
        <form class="change" method="post">
          <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
          <input type="hidden" name="state" value="{{{ $status ? 'closed' : 'open' }}}">
          <button type="submit" id="changeState">Just kidding, they're actually {{{ $status ? 'closed' : 'open' }}}.<br>(if so click here..)</button>
        </form>
      </article>
      <footer>
        <p>{{{ date('h:i A') }}}  //  {{{ "<3 DTLA" }}}  //  <a href="https://github.com/shampine">GITHUB</a></p>
      </footer>
    </section>
    <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="/js/main.js"></script>
  </body>
</html>