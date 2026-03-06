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
	{{ HTML::script('js/navigation.js') }}
	
	{{ HTML::script('js/jquery.dataTables.min.js') }}
	{{ HTML::style('css/jquery.dataTables.min.css') }}

	<!-- {{ HTML::style('css/bootstrap-responsive.min.css') }} -->
	
	{{ HTML::script('js/dataTables.responsive.js') }}
	{{ HTML::style('css/dataTables.responsive.css') }}

	
	
	
	
	<script type="text/javascript">

function noBack()
         {
             window.history.forward()
         }
        noBack();
        window.onload = noBack;
        window.onpageshow = function(evt) { if (evt.persisted) noBack() }
        window.onunload = function() { void (0) }

  </script>

<script type="text/javascript">
/*$(document).ready(function(){


alert(Browser.Platform.android);
if((Browser.Platform.ios) || (Browser.Platform.android) && (Browser.safari)) {
  //For iPhone and Andriod To remove Address bar when viewing website on Safari Mobile
  // When ready...
  window.addEventListener("load",function() {
    // Set a timeout...
    setTimeout(function(){
    // Hide the address bar!
    window.scrollTo(0, 1);
    }, 0);
  });
}

});	*/

/*function hideAddressBar()
{
  if(!window.location.hash)
  {
      if(document.height < window.outerHeight)
      {
          document.body.style.height = (window.outerHeight + 50) + 'px';
      }
 
      setTimeout( function(){ window.scrollTo(0, 1); }, 50 );
  }
}
 
window.addEventListener("load", function(){ if(!window.pageYOffset){ hideAddressBar(); } } );
window.addEventListener("orientationchange", hideAddressBar );

$(function() {
    function orientationChange(e) {
        $("body").scrollTop(1);
    }
    $("body").css({ height: "+=300" }).scrollTop(1);
    $(window).bind("orientationchange", orientationChange);
});*/

</script>
	
</head>
<body >
@include('layouts.navigation')
@yield('content')
@include('layouts.footer')
</body>
</html> 