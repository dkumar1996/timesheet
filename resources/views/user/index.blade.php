@extends('layouts.default_user')

@section('content')
<style type="text/css">
.btn{
    width: 100%;
}
.form-control {width: 100%}
h4
{
	background: #ccebc9 none repeat scroll 0 0;
    border: 1px solid green;
    padding: 10px;
}
</style>
<div class="container">
<div class="row">
<div class="col-md-3"></div>
<div class="col-md-6 col-xs-12 col-sm-12">

@if(Session::has('logoutmsg'))

<h4 class="text-center text-success">{{Session::get('logoutmsg')}}</h4>

@endif
@if(Auth::admin()->check())
<div class="">
{{Form::open(['action'=>['UserController@post_site'],'Method'=>'Post','role'=>'form'])}} 
<div class="form-group">
{{Form::select('site_id',$site,null,['class'=>'form-control','id'=>'pin','required'])}}

</div>

<div class="form-group">
    {{Form::submit('Submit',['class'=>'btn btn-primary btn-lg'])}}
</div>

{{Form::close()}}
</div>
	
</div>
<div class="col-md-3"></div>	
</div>
	
</div>


@else
    <?php return Redirect::route('login.index'); ?>
 @endif
@endsection