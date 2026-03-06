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
.ddis{
  background-color: #FF6961;
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

<?php
$date=date('Y-m-d');
$yesterday = date('Y-m-d',strtotime("-1 days"));
$colors=['0'=>'','1'=>'btn-success','2'=>'cdis','3'=>'ddis'];
function getColorCode($visits,$date,$yesterday){
	$login_type=0;
	foreach($visits as $key => $visit){
		//var_dump($visit->userid,$visit->flag_login,$visit->id,$date,$yesterday,$visit->created_date);
			if($date==$visit->created_date){
				if($visit->flag_login=='1') { $login_type = '1';  return $login_type;}
				if($visit->flag_login=='0') { $login_type = '2'; return $login_type;}
			}
			else if($yesterday==$visit->created_date){
				if($visit->flag_login=='1') {  $login_type = '3'; return $login_type; }
				if($visit->flag_login=='0') {  $login_type = '0'; return $login_type; }
			}
	}
	return $login_type;
}
?>
@if(!empty($data))
@foreach($data as $val)
<!-- <div class="fleft">
 
<div class="form-group">
{{Form::text('username',$val->first_name,['class'=>'form-control','readonly'=>'true'])}}	
</div>-->
<div class="form-group">
<?php 
$visits=TimeSheet::where('userid',$val->staff_id)->whereIn('created_date',[$date,$yesterday])
->where('site_id',$site_id)->orderby('id','DESC')->get();
//if($val->staff_id=="17") echo "<pre>";print_r($visits);echo "</pre>";
$login_type='0';//0-nologin,1-login,2-loginandlogout,3-loggedin previous day and not logged out
$vistCount=count($visits);

if($vistCount > 0){
	$login_type = getColorCode($visits,$date,$yesterday);
}
//var_dump($login_type);
?>
{{ html_entity_decode(HTML::LinkRoute('post_edit',Form::button(substr($val->first_name, 0, 1).".". $val->last_name, array('class'=>"btn ".$colors[$login_type]." btn-lg")),[$val->site_id,$val->staff_id])) }}

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