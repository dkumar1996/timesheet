<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon"  href={{ URL::asset('images/favicon.png') }} type="image/png" />
	<title>@if(isset($title)){{$title}}@endif</title>

	
	
	{{ HTML::script('js/jquery.min.js') }}
	{{ HTML::script('js/bootstrap.min.js') }}
	{{ HTML::style('css/base.css') }}
	{{ HTML::style('css/bootstrap.min.css') }}
	
	
	{{ HTML::style('css/login.css') }}
	


	
	
	
</head>
<body>




<div class="container">
        <div class="card card-container">
          
            {{HTML::image('images/logo.png','Logo',['id'=>'profile-img','class'=>'profile-img-card'])}}
            <p id="profile-name" class="profile-name-card"> 
           
            @if(isset($errors))
			{{$errors->first('message','<span class="text-danger">:message</span>')}}
			@endif

			@if(Session::has('message'))
	         <span class="text-danger"> {{Session::get('message')}}</span><br>
	       	 
	    	@endif
          
            </p>
            {{ Form::open(array('route' => 'login.store','class'=>'form-signin')) }}
                <span id="reauth-email" class="reauth-email"></span>
                <input type="text" id="inputEmail" class="form-control" placeholder="User Name" required autofocus name="email">
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" required name="password">
                <!-- <div class="form-group">
                {{Form::select('site_id',$site,null,['class'=>'form-control','required','id'=>'inputPassword'])}}
                </div> -->
                <!-- <div id="remember" class="checkbox">
                    <label>
                        <input type="checkbox" value="remember-me"> Remember me
                    </label>
                </div> -->
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Sign in</button>
            </form><!-- /form -->
           
           
        </div><!-- /card-container -->
    </div><!-- /container -->

</body>
</html> 