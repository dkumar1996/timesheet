@extends('layouts.default_user')

@section('content')

<style type="text/css">
	.fleft {
    float: left;
    margin: 10px;
    text-align: center;
    width: auto;
}
.form-control{width: 100%}
#showtime {
   /* width: 720px;*/
}
.form-control
{
	display: unset;
}
#form
{
	margin-bottom: 2em;
}
.btn{margin-left: 10px;}
/*#break{display: none;}*/

#addbreak,#cancel{display: none;}

</style>
<script type="text/javascript">

$(document).ready(function(){

    var date = $('#date');
  function getCurrentTime() {
  var d = new Date();

  function z(n){ return (n<10? '0':'') + n;}
  return z(d.getHours()) + ':' +
         z(d.getMinutes()) + ':' +
         z(d.getSeconds());
}
   

   function updateTime() {
        var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth()+1; //January is 0!
      var ctdate=today.getDay();
      var yyyy = today.getFullYear();
      if(dd<10){
          dd='0'+dd
      }
      if(mm<10){
          mm='0'+mm
      }
      var today =yyyy+'-'+mm+'-'+dd;
        date.val(today +" "+getCurrentTime());
        $('#timer').html('<div class="time"><i class="fa fa-clock-o"></i>'+getCurrentTime()+"</div>");
       //DateTime.Now.ToString("dd/MM/yyyy");
    }

    updateTime();
    setInterval(updateTime, 1000); // 5 * 1000 miliseconds
  });
  

</script>

{{HTML::style('css/jquery.signaturepad.css')}}
 {{HTML::script('js/jquery.signaturepad.js')}}


<div class="container">
<div class="row">
<div class="col-md-1">
</div>
<div class="col-md-9">

 @if(Session::has('message'))
 <div class="alert alert-danger" style="clear:both">
							<strong>Whoops!</strong> You didn't complete some previous day Timesheet.The Details are shown below.<br><br>
							<ul>
								@foreach(Session::get('message') as $val)
						         <li > {{$val->created_date}}</li>
						         @endforeach
							</ul>
						</div>
 @endif
 @if(Session::has('error'))
 <div class="alert alert-danger" style="clear:both">
	<strong>Whoops!</strong> {{Session::get('error')}}.<br><br>
 </div>
 @endif
{{Form::open(['route' =>'logoutupdate','method'=>'POST','role'=>'form','id'=>'form','class'=>'sigPad form'])}}


	<div class="form-group">
	<strong>{{Form::hidden('date',null,['class'=>'form-control text-bold','id'=>'date','readonly'=>true])}}</strong>
	</div>
   {{Form::hidden('login_type',null,['id'=>'login_type'])}}
   {{Form::hidden('planned_start',$r_end_time)}}

   <div class="form-group">
    

  <div id="tabs">  
    <ul  class="nav nav-pills">
      <li class="active"><a data-value="1" href="#planned" data-toggle="tab"><stron>Planned Finish Time</strong></a></li>
      <li><a data-value="0" href="#actual-time" data-toggle="tab"><strong>Actual Finish Time</strong></a></li>
    </ul>

      <div class="tab-content clearfix">
  
        <div class="tab-pane fade in active" id="planned">
                
          <div class="col-sm-12 col-md-12 col-xs-12 pull-left act_ros" align="center"><span class="timer-roster text-center timer"><i class="fa fa-clock-o"></i>{{$r_end_time}}</span></div>
          <!-- <div class="col-sm-6 col-md-6 col-xs-6 pull-left default-time" align="center"></div> -->
        </div>
            <div class="tab-pane fade" id="actual-time">
                
          <span class="col-sm-12 col-md-12 col-xs-12 timer-actual timer act_ros"  id="timer" align="center"></span>
          <!-- <div class="col-sm-6 col-md-6 col-xs-6 default-time" align="center"></div> -->
        </div>
              
      </div>
  </div>
</div>



	<?php $dvalues=[
	''		=>	'0 Minutes',
	'15'	=>	'15 Minutes',
    '30'	=>	'30 Minutes',
    '45'	=>	'45 Minutes',
    '60'	=>	'1 Hour',
    '75'	=>	'1 Hour 15 Minutes',
    '90'	=>	'1 Hour 30 Minutes',
    '105'	=>	'1 Hour 45 Minutes',
    '120'	=>	'2 Hour',
    '135'	=>	'2 Hour 15 Minutes',
    '150'	=>	'2 Hour 30 Minutes',
    '165'	=>	'2 Hour 45 Minutes',
    '180'	=>	'3 Hour',
    '195'	=>	'3 Hour 15 Minutes',
    '210'	=>	'3 Hour 30 Minutes',
    '225'	=>	'3 Hour 45 Minutes',
    '240'	=>	'4 Hour',
    '255'	=>	'4 Hour 15 Minutes',
	];

	$default_break=DB::table('user')->where('id',$data->id)->pluck('unpaid_break');
	 ?>
	<div  class="form-group " id='break'>
	<div class=""><strong>Unpaid Break</Strong> </div>
	<strong>{{Form::select('break',$dvalues,$default_break,['class'=>'form-control text-bold','id'=>'bkselect'])}}</strong>
	<br>
	<button  type="button"  class="btn-link pull-right" id="cancel">Cancel</button>
	</div>

 {{Form::hidden('site_id',$site_id)}}
	<div  class="form-group">
	<div class=""><strong>Please Sign Here</Strong> </div>
		<div class="sig sigWrapper">
		    <div class="typed"></div>
		    <canvas class="pad" width="720" height="242"></canvas>
		    <input type="hidden" name="customer_signature"  class="output" id="customer_sign">

		    {{Form::hidden('userid',$data->id)}}
		    {{Form::hidden('site_id',$site_id)}}
		 </div>

	<span class="error" id="sign-error"></span><bR>





	{{Form::submit('Log Off',array('class'=>'btn btn-primary btn-lg pull-right ','id'=>'showtime'))}}
	{{Form::close()}}

	 <button  type="button"  class="btn btn-primary btn-lg pull-right " id="addbreak" >Add Unpaid Break</button>

	 <button  type="button"  class="btn btn-primary btn-lg clearButton pull-right">Clear</button>

	 {{html_entity_decode(HTML::LinkRoute('user.show',Form::button('Cancel',['class'=>'btn btn-primary btn-lg pull-right']),['id'=>$site_id]))}}




		</div>

 	{{HTML::script('js/json2.min.js')}}


<script>

$(document).ready(function () {

/*Sign Pad*/
$('.sigPad').signaturePad({drawOnly : true});
/*Sign Pad*/



/* To display current day */

 var date=new Date();
 
 var weekdays =['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
 
  var monthNames = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
  ];
  var dd=date.getDate();
  if (dd < 10) {
      dd='0'+date.getDate();
    }
  var date_head= '<span class="weekdays"><i class="fa fa-calendar"></i>'+weekdays[date.getDay()] +'</span><span class="time">'+dd+'-'+monthNames[date.getMonth()]+'-'+date.getFullYear()+'</span>';
  $(".default-time").html(date_head);
/* To display current day */


/*Based on date and time manipulate actual and planned time*/

        var d = new Date(); // for now
        var h=d.getHours(); // => 9
         var m=d.getMinutes();
         var s=d.getSeconds();

          if(h<10){
              h='0'+h
          }
          if(m<10){
              m='0'+m
          }
     
        var current_time=h +':' + m +':' + s;
        var end_time="<?php echo $r_end_time; ?>";

        
         if(current_time < end_time){

          $('#login_type').val('0');
          $('a[href="#actual-time"]').tab('show');
          
        }
        else
        { 
          $('#login_type').val('1');
          $('a[href="#planned"]').tab('show');
        }
/*Based on date and time manipulate actual and planned time*/


/*During week end*/
    var weekends=new Date();
    var day = weekends.getDay();

    if((day == 6) || (day == 0)){
      $('a[href="#planned"]').parent().remove();
      $('a[href="#actual-time"]').parent().css('width','100%');
      $('#login_type').val('0');
      $('a[href="#actual-time"]').tab('show');
    }
/*During week end*/



/*Changing the login type value based on the selection*/
$('.nav-pills > li > a').click( function() {

$('#login_type').val($(this).attr('data-value'));

});
/*Changing the login type value based on the selection*/





});     
        
</script>


</div>
<div class="col-md-2">
</div>

</div>

</div>
<bR>

  <style type="text/css">
  .nav-pills > li{font-size: 20px;}
  .nav-pills .active::after {
  -moz-border-bottom-colors: none;
  -moz-border-left-colors: none;
  -moz-border-right-colors: none;
  -moz-border-top-colors: none;
  border-color: #195A9A transparent transparent;
  border-image: none;
  border-style: solid;
  border-width: 10px;
  content: " ";
  left: 50%;
  margin-left: -10px;
  position: absolute;
}

  .fa{
    margin-right: 6px;
  }
  .timer{
    text-align: center;
  } 
  .nav-pills  a {
    background-color: #ececec;
    color: #424242;
    border-radius: 0px !important;
    font-weight: bold;
    
  }
  .nav-pills > li.active > a, .nav-pills > li.active > a:focus,.nav-pills > li.active > a:hover
  {
    background-color: #195A9A;
  }
  .nav-pills > li {
      width: 50%;
      text-align: center;
  }
  .nav-pills > li + li {
  margin-left: 0;
}

.act_ros {
  font-size: 20px;
  font-weight: bold;
  padding: 20px 27px;
}
 .tab-content {
  margin-bottom: 30px;
  max-height: 79px;
  min-height: 79px;
  border:1px solid #eee;
}
.form{
  background-color: inherit;
  border:none;
}
.form-group{
  clear: both;
}
</style>
@endsection
