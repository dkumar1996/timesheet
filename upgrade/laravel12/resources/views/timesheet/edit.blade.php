@extends('layouts.default')



@section ('content')
<style type="text/css">
	.form-control {width: 100%;}
	
	</style>
	
 	{{ HTML::script('js/jquery.datetimepicker.js') }}
	{{ HTML::style('css/jquery.datetimepicker.css') }}
<script type="text/javascript">
	$(document).ready(function(){

		$('#login_time,#logout_time').datetimepicker({
		format:"Y-m-d H:i:s"});

		$('#login_date').datetimepicker({
			
		format:"Y-m-d", timepicker:false,});

	/*$('#login_time').datetimepicker({
		format:"Y-m-d H:i:s",
		onShow:function( ct ){
	   this.setOptions({
	    maxDate:$('#logout_time').val()?$('#logout_time').val():false
	   })
 	 },
  
	});  


	$('#logout_time').datetimepicker({

		format:"Y-m-d H:i:s",

		onShow:function( ct ){
	   this.setOptions({
	    minDate:$('#login_time').val()?$('#login_time').val():false
	   })
	  },
	  

	});  */





	 
});
</script>
	
<div class="container">
<div class="row">
<div class="row">
<div class="col-md-12">
{{ link_to_route('admin.index', 'Dashboard', null, ['class' => 'btn-link']) }} &nbsp;>&nbsp;

{{ link_to_route('timesheet.index', 'Timesheet Maintenance', null, ['class' => 'btn-link']) }}
<h3 class="text-center text-info">Edit TimeSheet</h3>
{{html_entity_decode(HTML::linkRoute('timesheet.index',Form::button('Back',['class'=>'btn btn-primary pull-right'])))}}
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
	
	{{ Form::model($data,['method' => 'PATCH', 'route' =>['timesheet.update', $data->id],'role'=>'form','class'=>'form-horizontal'] ) }}	
	

	<div class="form-group">

	{{Form::label('lo_time','Date',['class'=>'col-sm-4 control-label'])}}	
	 <div class="col-sm-8">
	{{Form::text('created_date',null,['class'=>'form-control','required','id'=>'login_date'])}}
	</div>
	
	</div>


	<div class="form-group">

	{{Form::label('login_time','Login Time',['class'=>'col-sm-4 control-label'])}}	
	 <div class="col-sm-8">
	{{Form::text('login_time',null,['class'=>'form-control','required','id'=>'login_time'])}}
	</div>
	
	</div>

	<div class="form-group">

	{{Form::label('logout_time','Logout Time',['class'=>'col-sm-4 control-label'])}}	
	 <div class="col-sm-8">
	{{Form::text('logout_time',null,['class'=>'form-control','id'=>'logout_time'])}}
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
	{{Form::select('break',$dvalues,null,['class'=>'form-control text-bold','id'=>'bkselect'])}}
	</div>
	
	</div>

	

	

	 <div class="form-group">
        <div class="col-sm-5 col-sm-offset-7">
              {{Form::submit('Update',['class'=>'btn btn-primary'])}}
        </div>
    </div>	

                                        
    {{ Form::close() }}
                            

</div>

<div class="col-md-3"></div>

</div>
</div>


@endsection