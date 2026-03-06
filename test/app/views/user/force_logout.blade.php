@extends('layouts.default_user')

@section('content')
   
{{HTML::style('css/jquery.signaturepad.css')}}
{{HTML::script('js/jquery.signaturepad.js')}}

{{HTML::style('css/jquery-ui.css')}}
{{HTML::script('js/jquery-ui-sliderAccess.js')}}
{{HTML::style('css/jquery-ui-timepicker-addon.css')}}
{{HTML::script('js/jquery-ui.js')}}
{{HTML::script('js/jquery-ui-timepicker-addon.js')}}
{{HTML::script('js/i18n/jquery-ui-timepicker-addon-i18n.min.js')}}

  
    <!--[if lt IE 9]>

    {{ HTML::script('js/DateTimePicker-ltie9.js') }}
    {{ HTML::style('css/DateTimePicker-ltie9.css') }}
    
    <![endif]-->




<script type="text/javascript">
  $(document).ready(function(){
    /*$('.clockpicker').clockpicker({
    placement: 'bottom',
    align: 'left',
    autoclose: 'true',
     init: function() { 
                            console.log("colorpicker initiated");
                        },
                        beforeShow: function() {
                            console.log("before show");
                        },
                        afterShow: function() {
                            console.log("after show");
                        },
                        beforeHide: function() {
                            console.log("before hide");
                        },
                        afterHide: function() {
                            console.log("after hide");
                        },
                        beforeHourSelect: function() {
                            console.log("before hour selected");
                        },
                        afterHourSelect: function() {
                            console.log("after hour selected");
                        },
                        beforeDone: function() {
                            console.log("before done");
                        },
                        afterDone: function() {
                            console.log("after done");
                        }
});*/
    $('#date').focus();


    });
  

 </script>


<script type="text/javascript">
var login_dateandtime= "<?php echo $loggedISOTime; ?>";
var roster_endtime = "<?php echo $r_end_time; ?>";
function checkPattern(obj){
    var re = new RegExp("^"+obj.pattern+"$");
        if (!re.test(obj.value)) {
            obj.focus();
            alert("Wrong format. Please, "+obj.title);
        }
}


$(document).ready(function(){
    var weekends=new Date();
    var day = weekends.getDay();

    if((day == 6) || (day == 0)){
      $('.roster').hide();
    }
});

function updateShiftHours(activeTab='',expectedDate=''){
	
		var [ly, lmm, ld, lh,lm,ls,lms] = login_dateandtime.split('-');
	    if(expectedDate.length > 0){
			var [lgy, lgmm, lgd, lgh,lgm,lgs] = expectedDate.split('-');	
			var today = new Date(lgy, lgmm, lgd, lgh,lgm,lgs,0);
			console.log(lgy, lgmm, lgd, lgh,lgm,lgs);
		}
		else{
			var [lgh,lgm,lgs] = roster_endtime.split(':');	
			var today = new Date(ly, lmm, ld, lgh,lgm,lgs,0);
		}
	
	    var fromDate =new Date(ly, lmm, ld, lh,lm,ls,lms);
		console.log(ly, lmm, ld, lh,lm,ls,lms);
		var timeDiff = Math.abs(today.getTime() - fromDate.getTime()); // in miliseconds
	    var timeDiffInSecond = Math.ceil(timeDiff / 1000); // in second
		jQuery('#shift_hours').val(secondsToHms(timeDiffInSecond));
		if(activeTab == 'actual-time') jQuery('#shift_hours').attr('data-actual',secondsToHms(timeDiffInSecond));
		if(activeTab == 'planned') jQuery('#shift_hours').attr('data-planned',secondsToHms(timeDiffInSecond));
}
function secondsToHms(d) {
    d = Number(d);
    var h = Math.floor(d / 3600);
    var m = Math.floor(d % 3600 / 60);
    var s = Math.floor(d % 3600 % 60);

    var hDisplay = h > 0 ? h + (h == 1 ? " hour " : " hours ") : "";
    var mDisplay = m > 0 ? m + (m == 1 ? " minute " : " minutes ") : "";
    var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
    return hDisplay + mDisplay + sDisplay; 
}
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
	   var [ly, lmm, ld, lh,lm,ls,lms] = login_dateandtime.split('-');
	   //console.log(ly, lmm, ld, lh,lm,ls,lms);
	    var fromDate =new Date(ly, lmm, ld, lh,lm,ls,lms);
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
      
	  var timeDiff = Math.abs(today.getTime() - fromDate.getTime()); // in miliseconds
	  var timeDiffInSecond = Math.ceil(timeDiff / 1000); // in second
	//console.log(timeDiffInSecond);
	/*if($('#shift_hours').attr('data-type')=="actual" && $('#shift_hours').attr('data-subtype')=="current") {
		$('#shift_hours').attr('data-load',secondsToHms(timeDiffInSecond));
	}*/
	$('#shift_hours').attr('data-actual',secondsToHms(timeDiffInSecond));
	    var today =yyyy+'-'+mm+'-'+dd;
        date.val(today +" "+getCurrentTime());
        //$('[name=date]').val(getCurrentTime());
		$('#timerDate').html('<div class="timeDate"><i class="fa fa-calendar"></i>'+dd+'-'+mm+'-'+yyyy+"</div>");
		$('#timer').html('<div class="time"><i class="fa fa-clock-o"></i>'+getCurrentTime()+"</div>");
        $('#loggedOutTime').attr('data-actual',yyyy+'-'+mm+'-'+dd+" " +getCurrentTime());
       //DateTime.Now.ToString("dd/MM/yyyy");
    }

    updateTime();
	updateShiftHours('planned');
    //setInterval(updateTime, 1000); // 5 * 1000 miliseconds
  });
</script>
<style type="text/css">
.sigPad{
  width: 100%;
}
  .fleft {
    float: left;
    margin: 10px;
    text-align: center;
    width: auto;
}#showtime {
    width: 100%;
}
.form-control
{
  display: unset;
  width: 100%;
}
#form
{
  margin-bottom: 2em;
}
.dtpicker-components .dtpicker-compValue {  pointer-events: none;}
.dtpicker-components .dtpicker-compButton { font-size: 200%; }
.dtpicker-header .dtpicker-close { font-size: 2.5em; }

</style>

  
<div class="container">
<div class="row">
<div class="col-md-3">
<div class="fleft">
{{HTML::image('images/avatar_2x.png','Logo', array('class'=>"img-responsive"))}}
<br>
Welcome {{$data->first_name}}</div>
</div>
<div class="col-md-9">


 @if(Session::has('newerror'))
 <div class="alert alert-danger" style="clear:both"> <strong>Whoops!</strong> {{Session::get('newerror')}} </div>
 @endif 
 
{{Form::open(['route' =>['post_forcelogout',$data->id],'method'=>'post','role'=>'form','id'=>'form','class'=>'sigPad form'])}}

  

  {{Form::hidden('date',null,['class'=>'form-control text-bold','id'=>'date','readonly'=>true])}}
   {{Form::hidden('login_type',null,['id'=>'login_type'])}}
  <div class="form-group">

  <div id="tabs"> 
  <input type="hidden" name="plannedDate" id="plannedDate"> 
   <ul  class="nav nav-pills">
      <li class="active"><a data-value="1" href="#planned" data-toggle="tab"><strong>Planned Finish Time</strong></a></li>
      <li><a data-value="0" href="#actual-time" data-toggle="tab"><strong>Actual Finish Time</strong></a></li>
    </ul>      
    <div class="tab-content clearfix">
  
        <div class="tab-pane fade in active" id="planned">

          <div class="col-sm-12 col-md-12 col-xs-12 pull-left act_ros" align="center">
		  <span class="timer-roster text-center timer" id="plannedTimeWrapper">
		  <div class="timeDate"><i class="fa fa-calendar"></i>{{date('d-m-Y',strtotime($message->login_time))}}</div>
		  <div class="time"><i class="fa fa-clock-o"></i>{{$r_end_time}}</div>
		  <span class="text-primary"><i class="fa fa-edit trigger-time" data-type="planned"></i></span>
		  </span>
		  
		  <!--<span class="col-sm-12 col-md-12 col-xs-12 timer-actual timer act_ros" id="plannedTimeChangeWrapper"  align="center">
		  <span id="ptimerDate" ></span>
		  <span id="ptimer" ></span>		 
		 </span>-->
		 </div>
          <!-- <div class="col-sm-6 col-md-6 col-xs-6 pull-left default-time" align="center"></div> -->
        </div>

        <div class="tab-pane fade" id="actual-time">
		<span class="col-sm-12 col-md-12 col-xs-12 timer-actual timer act_ros" id="currentTime"  align="center">
		  <span id="timerDate" ></span>
		  <span id="timer" ></span>
		  <span class="text-primary"><i class="fa fa-edit trigger-time" data-type="custom"></i></span>
		</span>
		<input type="hidden" name="customDate" id="customDate"> 
		<!--<span class="col-sm-12 col-md-12 col-xs-12 timer-actual timer act_ros" id="setTime"  align="center">
		  <span id="ctimerDate" ></span>
		  <span id="ctimer" ></span>
		  <span class=" btn btn-warning btn-sm trigger-time" data-type="current">Set Current</span>
		</span>-->
		
        <div class="col-sm-12 col-md-12 col-xs-12"  id="timerz" align="center">
          <div class="form-group clockpicker" style="display:none">
              <input type="text" name="time" class="form-control" value="{{date('Y-m-d H:i:s')}}" id="time" Placeholder="Enter the Logout Time" readonly="true" data-field="datetime"> 
          </div>
            </div>
          <!-- <div class="col-sm-6 col-md-6 col-xs-6 default-time" align="center"></div> -->
        </div>
              
      </div>
  </div>
  </div>
  <div class="form-group" > 
	<div class=""><strong>Shift Hours</Strong> </div>
	<strong><input type="text" readonly="true" class="form-control" data-subtype="current" data-type="planned" name="shift_hours" id="shift_hours" value=""></strong>
  </div>
  
  <div  class="form-group">
    
   <div class=""><strong>Please Sign Here</Strong> </div>
    <div class="sig sigWrapper">
        <div class="typed"></div>
        <canvas class="pad" width="720" height="242" required="required"></canvas>

        {{Form::hidden('customer_signature',null,['class'=>'output', 'id'=>'customer_sign','required'])}}
     </div>


     <span class="error" id="sign-error"></span> <bR>
     {{html_entity_decode(HTML::LinkRoute('user.show',Form::button('Cancel',['class'=>'btn btn-primary btn-lg']),['id'=>$site_id]))}}

      <button class="btn btn-primary btn-lg clearButton">Clear</button>
    
  </div>

<!-- Modal -->
<div id="warning" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

  <div class="modal-body" style="padding:0px;">
  @if(!empty($message))
	@if (count($errors) > 0)
	<div class="alert alert-danger" style="clear:both; margin-bottom: 0">
	<strong>Please check the errors</strong><button type="button" class="close" data-dismiss="modal">&times;</button>
		<ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
	</div>
    @else
	<div class="alert alert-info" style="clear:both; margin-bottom: 0">
		You didn't sign out on <strong><span class="time-not-finish"></span>.  Please confirm which date you completed your last shift. <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>	
    @endif        
 @endif
  </div>
     
    </div>

  </div>
</div>




    {{Form::hidden('logout_siteid',$message->site_id)}}
    {{Form::hidden('userid',$data->id)}}
    {{Form::hidden('site_id',$site_id)}}
    {{Form::hidden('date-end',$message->created_date)}}
	<input type="hidden" value="{{$message->id}}" name="user_attendance_id" />
	<input type="hidden" data-planned="{{date('Y-m-d',strtotime($message->login_time))}} {{$r_end_time}}" name="loggedOutTime" id="loggedOutTime" value="{{date('Y-m-d',strtotime($message->login_time))}} {{$r_end_time}}" />
  {{Form::submit('Log Off',array('class'=>'btn btn-primary btn-lg  ','id'=>'showtime'))}}
  {{Form::close()}}

   
   {{HTML::script('js/json2.min.js')}}

<?php 
        if(Session::has('signature'))
        {
          $signature=Session::get('signature');
          
        }
        else
        {
          $signature=null;
        }
        ?>


<script type="text/javascript">
    $(window).on('load',function(){
        $('#warning').modal('show');
    });
</script>
<script>

$(document).ready(function () {

/*Sign Pad*/
$('.sigPad').signaturePad({drawOnly : true});
/*Sign Pad*/
var inputcustomDate = $("#customDate");
var changeDateButtons = function() {
    setTimeout(function() {
        var widgetHeader = inputcustomDate.datepicker("widget").find(".ui-datepicker-header");
        //you can opt to style up these simple buttons tho
        var prevYrBtn = $('<button type="button"  title="Today">Today</button>');
        prevYrBtn.bind("click", function() {
            inputcustomDate.datetimepicker('setDate',new Date());
			//changeDateButtons();
        });
        var nextYrBtn = $('<button type="button"  title="Yesterday">Yesterday</button>');
        nextYrBtn.bind("click", function() {
			var date = new Date();
			date.setDate(date.getDate() - 1);
			inputcustomDate.datetimepicker('setDate',date);
			//changeDateButtons();
        });
        prevYrBtn.appendTo(widgetHeader);
        nextYrBtn.appendTo(widgetHeader);

    }, 1);
};
 $('#customDate').datetimepicker({
	 showButtonPanel: true, 
	  dateFormat: "yy-mm-dd",
	  timeFormat: 'HH:mm:ss',
	  gotoCurrent: true,
	  showTime: true,
	  beforeShow: changeDateButtons,
	  onSelect: changeDateButtons,
	  onClose: function(dateText, inst) { 
	  console.log(dateText,inst);
            var datTime=dateText.split(" ");
			$('#loggedOutTime').val(dateText);
			$('#loggedOutTime').attr('data-actual',dateText);
			$('#actual-time').find('.timeDate').html('<i class="fa fa-calendar"></i>'+datTime[0]);
			$('#actual-time').find('.time').html('<i class="fa fa-clock-o"></i>'+datTime[1]);
			updateShiftHours('actual-time',inst.selectedYear+'-'+inst.selectedMonth+'-'+inst.selectedDay +'-'+ (datTime[1].split(':')).join('-'));
        }
 }).datetimepicker('setDate',new Date());
 
 
 var inputplannedDate = $("#plannedDate");
var changeDateButtons = function() {
    setTimeout(function() {
        var widgetHeader = inputplannedDate.datepicker("widget").find(".ui-datepicker-header");
        //you can opt to style up these simple buttons tho
        var prevYrBtn = $('<button type="button" title="Today">Today</button>');
        prevYrBtn.unbind("click").bind("click", function() {
			inputplannedDate.datetimepicker('setDate',new Date());
            //changeDateButtons();
        });
        var nextYrBtn = $('<button type="button" title="Yesterday">Yesterday</button>');
        nextYrBtn.unbind("click").bind("click", function() {
			var date = new Date();
			date.setDate(date.getDate() - 1);
			inputplannedDate.datetimepicker('setDate',date);
			//changeDateButtons();	
				
        });
        prevYrBtn.appendTo(widgetHeader);
        nextYrBtn.appendTo(widgetHeader);

    }, 1);
};
 $('#plannedDate').datetimepicker({
	 showButtonPanel: true, 
	  dateFormat: "yy-mm-dd",
	  timeFormat: 'HH:mm:ss',
	  gotoCurrent: true,
	  showTime: true,
	  beforeShow: changeDateButtons,
	  onSelect: changeDateButtons,
	  onClose: function(dateText, inst) { 
	  console.log(dateText,inst);
            var datTime=dateText.split(" ");
			$('#loggedOutTime').val(dateText);
			$('#loggedOutTime').attr('data-planned',dateText);
			$('#planned').find('.timeDate').html('<i class="fa fa-calendar"></i>'+datTime[0]);
			$('#planned').find('.time').html('<i class="fa fa-clock-o"></i>'+datTime[1]);
			updateShiftHours('planned',inst.selectedYear+'-'+inst.selectedMonth+'-'+inst.selectedDay +'-'+ (datTime[1].split(':')).join('-'));
        }
 }).datetimepicker('setDate',new Date());
 
$('.trigger-time').click(function(){
	//$('#customDate').datetimepicker('show');
	if($(this).attr('data-type')=="custom"){
		$('#customDate').datetimepicker('show');
		//$('#shift_hours').attr('data-subtype','custom');
		//$('#currentTime').hide();
		//$('#setTime').show();
	}
	else if($(this).attr('data-type')=="planned"){
		$('#plannedDate').datetimepicker('show');
		//$('#shift_hours').attr('data-subtype','current');
		//$('#currentTime').show();
		//$('#setTime').hide();
	}
	
});


/* To display current day */
 var dateend=$('[name="date-end"]').val();
 var date=new Date();
  var past_date=new Date(dateend);

  // alert(past_date);
 
 var weekdays =['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
 
  var monthNames = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
  ];
  var dd=date.getDate();
  if (dd < 10) {
      dd='0'+date.getDate();
    }

  var date_head= '<span class="weekdays"><i class="fa fa-calendar"></i>'+weekdays[past_date.getDay()] +'</span><span class="time">'+past_date.getDate()+'-'+monthNames[past_date.getMonth()]+'-'+date.getFullYear()+' </span>';
  $(".default-time").html(date_head);

  var default_date= weekdays[past_date.getDay()]+' '+past_date.getDate()+'-'+monthNames[past_date.getMonth()]+'-'+date.getFullYear();
  $(".time-not-finish").html(default_date);


/* To display current day */




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




var r_end_time="<?php echo $r_end_time; ?>";
 $('[name=time]').val(r_end_time);
 $('[name=login_type]').val('1');
 $('#shift_hours').attr('data-type','planned');

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  var target = $(e.target).attr("href") // activated tab
    if (target=="#actual-time") {
        $('[name=login_type]').val('0');
        //$('[name=time]').val('');
		$('#shift_hours').attr('data-type','actual');
		$('#shift_hours').val($('#shift_hours').attr('data-actual'));
		$('#loggedOutTime').val($('#loggedOutTime').attr('data-actual'));
		//updateTime();
		//updateShiftHours();
    }
    else{
      $('[name=login_type]').val('1');
      $('[name=time]').val(r_end_time);
	  $('#shift_hours').attr('data-type','planned');
	  $('#shift_hours').val($('#shift_hours').attr('data-planned'));
	  $('#loggedOutTime').val($('#loggedOutTime').attr('data-planned'));
	  //updateShiftHours();
    }
});



});     
        
</script>


 

</div>

  
</div>
  
</div>

<style type="text/css">
 .timeDate, .time {
    display: inline-block;
    margin-right: 15px;
}
span.text-primary{cursor:pointer}
#setTime,#plannedTimeChangeWrapper{display:none}
@media screen and (max-width: 990px) {

}
.clockpicker{
  text-align: left;
}
.error {
  font-family: arial;
  font-size: 14px;
  font-weight: normal;
}
  .nav-pills > li{
    font-size: 16px;
  }
  .nav-pills .active::after {
  -moz-border-bottom-colors: none;
  -moz-border-left-colors: none;
  -moz-border-right-colors: none;
  -moz-border-top-colors: none;
  border-color: #a94442 transparent transparent;
  border-image: none;
  border-style: solid;
  border-width: 10px;
  content: " ";
  left: 50%;
  margin-left: -10px;
  position: absolute;
}


.form-group .clockpicker{
  margin-bottom: 0;
}
.act_ros {
  font-size: 20px;
  font-weight: bold;
  padding: 20px 27px;
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
.nav-pills > li.active > a, .nav-pills > li.active > a:focus, .nav-pills > li.active > a:hover {
  background-color: #a94442 !important;
}
.nav-pills > li.active > a, .nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus {
  background-color: #428bca;
  color: #fff !important;
}

</style>
@endsection
