<!DOCTYPE html>
<html class="no-js">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="Is Wendell Bar in downtown Los Angeles open? Find out here or update their status.">
    <title>Is Wendell Open</title>
    <link rel="shortcut icon" href="/img/favicon.ico">
    <link rel="stylesheet" href="/css/main.css">
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
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
          <button type="submit" id="changeState">Just kidding, they're actually {{{ $status ? 'closed' : 'open' }}}.</button>
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