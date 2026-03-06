@extends('layouts.default_user')

@section('content')


   {{HTML::script('js/jquery.signaturepad.js')}}
   {{HTML::style('css/jquery.signaturepad.css')}}
   {{ HTML::script('js/clockpicker.js') }}
   {{ HTML::style('css/clockpicker.css') }}

  
    <!--[if lt IE 9]>

    {{ HTML::script('js/DateTimePicker-ltie9.js') }}
    {{ HTML::style('css/DateTimePicker-ltie9.css') }}
    
    <![endif]-->




<script type="text/javascript">
  $(document).ready(function(){
    $('.clockpicker').clockpicker({
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
});
    $('#date').focus();


    });
  

  </script>


<script type="text/javascript">
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
        $('[name=date]').val(getCurrentTime());
       //DateTime.Now.ToString("dd/MM/yyyy");
    }

    updateTime();
    setInterval(updateTime, 1000); // 5 * 1000 miliseconds
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


  

  {{Form::hidden('date',null,['class'=>'form-control text-bold','readonly'=>true])}}
   {{Form::hidden('login_type',null,['id'=>'login_type'])}}
  <div class="form-group">

  <div id="tabs">  
   <ul  class="nav nav-pills">
      <li class="active"><a data-value="1" href="#planned" data-toggle="tab"><strong>Planned Finish Time</strong></a></li>
      <li><a data-value="0" href="#actual-time" data-toggle="tab"><strong>Actual Finish Time</strong></a></li>
    </ul>      
    <div class="tab-content clearfix">
  
        <div class="tab-pane fade in active" id="planned">

          <div class="col-sm-12 col-md-12 col-xs-12 pull-left act_ros" align="center"><span class="timer-roster text-center timer"><i class="fa fa-clock-o"></i>{{$r_end_time}}</span></div>
          <!-- <div class="col-sm-6 col-md-6 col-xs-6 pull-left default-time" align="center"></div> -->
        </div>

        <div class="tab-pane fade" id="actual-time">
            <div class="col-sm-12 col-md-12 col-xs-12 act_ros"  id="timer" align="center">
          <div class="form-group clockpicker">
              <input type="text" name="time" class="form-control" id="time" Placeholder="Enter the Logout Time" readonly="true" data-field="datetime"> 
          </div>
            </div>
          <!-- <div class="col-sm-6 col-md-6 col-xs-6 default-time" align="center"></div> -->
        </div>
              
      </div>
  </div>
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
 <div class="alert alert-danger" style="clear:both; margin-bottom: 0">
    <strong>Whoops!</strong> You didn't complete <strong><span class="time-not-finish"></span></strong> Timesheet. <button type="button" class="close" data-dismiss="modal">&times;</button>
    @if (count($errors) > 0)
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
            
    @endif        
</div>
 @endif
      </div>
     
    </div>

  </div>
</div>




    {{Form::hidden('logout_siteid',$message[0]->site_id)}}
    {{Form::hidden('userid',$data->id)}}
    {{Form::hidden('site_id',$site_id)}}
    {{Form::hidden('date-end',$message[0]->created_date)}}
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

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  var target = $(e.target).attr("href") // activated tab
    if (target=="#actual-time") {
        $('[name=login_type]').val('0');
        $('[name=time]').val('');
    }
    else{
      $('[name=login_type]').val('1');
      $('[name=time]').val(r_end_time);
    }
});



});     
        
</script>


 

</div>

  
</div>
  
</div>

<style type="text/css">

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
