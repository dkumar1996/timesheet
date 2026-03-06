<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;minimal-ui">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-status-bar-style" content="black" />
 <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<link rel="shortcut icon"  href={{ URL::asset('images/favicon.png') }} type="image/png" />

	<title>@if(isset($title)){{$title}}@endif</title>


	



	{{ HTML::script('js/jquery.min.js') }}

	{{ HTML::script('js/bootstrap.min.js') }}
	{{ HTML::style('css/base.css') }}
	{{ HTML::style('css/bootstrap.min.css') }}
	
	{{ HTML::style('css/font-awesome/css/font-awesome.min.css') }}
  {{ HTML::style('css/font-awesome/css/font-awesome.css') }}
  
  
  
	
	
	<script type="text/javascript">

function noBack()
         {
             window.history.forward()
         }
        noBack();
        window.onload = noBack;
        window.onpageshow = function(evt) { if (evt.persisted) noBack() }
      //  window.onunload = function() { void (0) }

     /*  window.onload =
  function() {
    // Force reload in 60 minutes3600000
    window.setTimeout(function(){location.reload(true);}, 60000);
  };*/

  var idleTime = 0;
$(document).ready(function () {
    //Increment the idle time counter every minute.
    var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

    //Zero the idle timer on mouse movement.
    $(this).mousemove(function (e) {
        idleTime = 0;
    });
    $(this).keypress(function (e) {
        idleTime = 0;
    });
});

function timerIncrement() {
    idleTime = idleTime + 1;
    //alert(idleTime);
    if (idleTime > 59) { // 20 minutes
        window.location.reload();
    }
}


  </script>


	
</head>
<body >
@include('layouts.navigation')
@yield('content')
@include('layouts.footer')
</body>
</html> 