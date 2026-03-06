@extends('layouts.default_user')

@section('content')

<style type="text/css">
#showandhide {
    background: #ececec none repeat scroll 0 0;
    border: 2px solid #87c540;
    border-radius: 2px;
    font-weight: normal;
    padding: 5px;
    width: 100%;
}
.fleft {
    background: #185A9A none repeat scroll 0 0;
    border: 1px solid #ddd;
    border-radius: 10px;
    float: left;
    margin: 10px;
    padding: 25px 20px;
    width: auto;
}
.form-group {
    float: left;
    margin: 2%;
    min-width: 21%;
    width: auto;
    font-size: 16px !important;
}.cdis
{
    background-color: grey;
}

.btn{ text-transform: capitalize;}
button{background: #FCB825;color: #fff;}
.btn,.form-control{
	width: 100%;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
    $("#showandhide").show().delay(2000).fadeOut();
});

var now = new Date();
var night = new Date(
    now.getFullYear(),
    now.getMonth(),
    now.getDate() + 1, // the next day, ...
    0, 0, 0 // ...at 00:00:00 hours
);
var msTillMidnight = night.getTime() - now.getTime();

setTimeout('document.location.reload()', msTillMidnight);
    
</script>

<div class="container">
<div class="row">

<div class="col-md-12 col-sm-12 col-xs-12">
@if(Session::has('smessage'))

<h4 class="text-center" id="showandhide">{{Session::get('smessage')}}</h4>

@endif


@if(!empty($data))
@foreach($data as $val)
<!-- <div class="fleft">
 
<div class="form-group">
{{Form::text('username',$val->first_name,['class'=>'form-control','readonly'=>'true'])}}	
</div>-->
<div class="form-group">
<?php 
$date=date('Y-m-d');
$tvalue=TimeSheet::where('userid',$val->staff_id)->where('created_date',$date)->where('site_id',$site_id)->orderby('login_time','DESC')->pluck('id');
$idval=TimeSheet::find($tvalue);
?>
@if( !is_null($idval['login_time']) && !is_null($idval['logout_time']) )

{{ html_entity_decode(HTML::LinkRoute('post_edit',Form::button(substr($val->first_name, 0, 1).".". $val->last_name, array('class'=>"btn cdis btn-lg")),[$val->site_id,$val->staff_id])) }}

@elseif(!is_null($idval['login_time']))

{{ html_entity_decode(HTML::LinkRoute('post_edit',Form::button(substr($val->first_name, 0, 1).".". $val->last_name, array('class'=>"btn btn-success btn-lg")),[$val->site_id,$val->staff_id])) }}

@else
{{ html_entity_decode(HTML::LinkRoute('post_edit',Form::button(substr($val->first_name, 0, 1).".". $val->last_name, array('class'=>"btn btn-lg")),[$val->site_id,$val->staff_id])) }}
@endif

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

@endsection