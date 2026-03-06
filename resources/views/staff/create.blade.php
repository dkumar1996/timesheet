@extends('layouts.default')

@section ('content')
{{ HTML::script('js/jquery.datetimepicker.js') }}
{{ HTML::style('css/jquery.datetimepicker.css') }}
<script type="text/javascript">
	function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
	$(document).ready(function(){

$('#login_time,#logout_time').datetimepicker({
format:"H:i"});
});
</script>
<style type="text/css">
	.form-control {width: 100%;}

	</style>

<div class="container">
<div class="row">
<div class="row">
<div class="col-md-12">
{{ link_to_route('admin.index', 'Dashboard', null, ['class' => 'btn-link']) }} &nbsp;>&nbsp;

{{ link_to_route('staff.index', 'Staff Maintenance', null, ['class' => 'btn-link']) }}
<h3 class="text-center text-info">Staff - Add New</h3>
{{html_entity_decode(HTML::linkRoute('staff.index',Form::button('Back',['class'=>'btn btn-primary pull-right'])))}}
</div>
</div>
<bR>
<div class="col-md-3"></div>

<div class="col-md-6 col-sm-12 col-xs-12">
@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
@endif
	{{ Form::open(['route' => 'staff.store','Method'=>'post','role'=>'form','class'=>'form-horizontal','id'=>'staff-form'])}}

	<div class="form-group">

	{{Form::label('first_name','First Name',['class'=>'col-sm-4 control-label'])}}
	 <div class="col-sm-8">
	{{Form::text('first_name','',['class'=>'form-control','required'])}}
	</div>

	</div>

	<div class="form-group">

	{{Form::label('last_name','Last Name',['class'=>'col-sm-4 control-label'])}}
	 <div class="col-sm-8">
	{{Form::text('last_name','',['class'=>'form-control','required'])}}
	</div>

	</div>

	<div class="form-group">

	{{Form::label('email','Email',['class'=>'col-sm-4 control-label'])}}
	 <div class="col-sm-8">
	{{Form::email('email','',['class'=>'form-control','required'])}}
	</div>

	</div>


	<div class="form-group">

	{{Form::label('cat','Site',['class'=>'col-sm-4 control-label'])}}
	 <div class="col-sm-8">
	{{Form::select('site[]',$site,null,['class'=>'form-control','required','multiple'=>true])}}
	</div>

	</div>
	<div class="form-group">
	<?php $company=array(''=>'Select Company')+DB::table('company')->where('enabled',1)->lists('company_name','id'); ?>
	{{Form::label('comp','Company',['class'=>'col-sm-4 control-label'])}}
	 <div class="col-sm-8">
	{{Form::select('company',$company,null,['class'=>'form-control','required'])}}
	</div>

	</div>

	<div class="form-group">

	{{Form::label('password','Enter PIN',['class'=>'col-sm-4 control-label'])}}
	 <div class="col-sm-8">
	{{Form::text('password',null,['class'=>'form-control','required','maxlength'=>4,'onkeypress'=>"return isNumber(event)"])}}
	</div>

	</div>

	<div class="form-group">

	{{Form::label('last_name','Time Card Number',['class'=>'col-sm-4 control-label'])}}
	 <div class="col-sm-8">
	{{Form::text('timecard_no','',['class'=>'form-control','required'])}}
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
	]; ?>

	<div class="form-group">
	{{Form::label('logout_time','Unpaid Break',['class'=>'col-sm-4 control-label'])}}
	 <div class="col-sm-8">
	{{Form::select('unpaid_break',$dvalues,null,['class'=>'form-control text-bold','id'=>'bkselect'])}}
	</div>

	</div>

	<div class="form-group">
		{{Form::label('logout_time','Roster Start Time',['class'=>'col-sm-4 control-label'])}}
		 <div class="col-sm-8">
		{{Form::text('roster_start','',['class'=>'form-control','required','id'=>'login_time'])}}
		</div>
	</div>
	<div class="form-group">
		{{Form::label('logout_time','Roster End Time',['class'=>'col-sm-4 control-label'])}}
		 <div class="col-sm-8">
		{{Form::text('roster_end','',['class'=>'form-control','required','id'=>'logout_time'])}}
	</div>
	</div>
	<div class="form-group">
	<div class='col-sm-4' ></div>
	 <div class="col-sm-8">
	{{Form::checkbox('meal_allowance','0',null,['id'=>'change'])}}
	 {{Form::label('change','Meal Allowance',['class'=>' control-label'])}}
	</div>

	</div>





	 <div class="form-group">
        <div class="col-sm-5 col-sm-offset-7">
              {{Form::submit('Save',['class'=>'btn btn-primary'])}}
        </div>
    </div>


    {{ Form::close() }}


</div>

<div class="col-md-3"></div>

</div>
</div>
<style media="screen">
	.xdsoft_datetimepicker .xdsoft_datepicker
	{
		width: 0;
	}
	.xdsoft_datepicker,.xdsoft_datepicker div{
		display: none;
	}
	.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_time_box > div > div
	{
		background-color: #fff;
	}
	.xdsoft_datetimepicker{
		background-color: #eee;
	}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$('#staff-form').submit(function(e){


		var startTime = $('#login_time').val();
		var endTime = $('#logout_time').val();


		if(endTime < startTime){
	 		var error = 'Your end time is before your start time.';
			alert(error);
			return false;
		};

	});


});
    $(document).ready(function(){
        $('#change').click(function(){
            if($(this).is(':checked')) $(this).val('1');
            else $(this).val('0');
        })

    });
</script>
@endsection
