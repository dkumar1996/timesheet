@extends('layouts.default_user')

@section('content')
<style type="text/css">

.fleft {
    background: #185A9A none repeat scroll 0 0;
    border: 1px solid #ddd;
    border-radius: 10px;
    float: left;
    margin: 10px;
    padding: 25px 20px;
    width: auto;
}
h4
{
    background: #ccebc9 none repeat scroll 0 0;
    border: 1px solid green;
    padding: 10px;
}
button{background: #FCB825;color: #fff;}
button,.form-control{
	width: 100%;
}
</style>

@if(Auth::user()->check())
<div class="container">
<div class="row">

<div class="col-md-12">

@if(Session::has('smessage'))

<h4 class="text-center text-success">{{Session::get('smessage')}}</h4>

@endif

@if(!empty($data))
@foreach($data as $val)
<div class="fleft">

<div class="form-group">
{{Form::text('username',$val->first_name,['class'=>'form-control','readonly'=>'true'])}}	
</div>
<div class="form-group">

@if(Auth::user()->check())

    @if(Auth::user()->get()->id==$val->staff_id)
      {{ html_entity_decode(HTML::LinkRoute('show_logout',Form::button('Logout', array('class'=>"btn")),[$val->staff_id,$val->site_id])) }}
  
    @else
        {{ html_entity_decode(HTML::LinkRoute('post_edit',Form::button('Login', array('class'=>"btn")),[$val->site_id,$val->staff_id])) }}

    @endif
    
@endif
   

</div>

</div>

@endforeach
@else
<div class="fleft" align="center" style="float:none">
<div style="color:#fff">No user found for given site.</div>
</div>
@endif

	
</div>

</div>
	
</div>
@else
    <?php return Redirect::route('login.index'); ?>
 @endif
@endsection