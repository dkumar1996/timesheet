@extends('layouts.default')



@section ('content')
@if(Session::has('file'))
<a href="{{ Session::get('file') }}" target="_blank" id="nodisplay">Report</a>
<style type="text/css">#nodisplay{display: none;}</style>
<script type="text/javascript">
document.getElementById("nodisplay").click();
</script>
 @endif
<style type="text/css">
	.form-control {width: 100%;}
	
	</style>
	
 	{{ HTML::script('js/calendar.js') }}
	{{ HTML::style('css/calendar.css') }}
<script type="text/javascript">
	$(document).ready(function(){

		 //$( "#from_date,#to_date" ).datepicker({dateFormat:"yy-mm-dd"});
		 $("#from_date").datepicker({

	       dateFormat:'yy-mm-dd',

	        onSelect: function(selected) {

	          $("#to_date").datepicker("option","minDate", selected)

	        }

	    });

	    $("#to_date").datepicker({

	     dateFormat:'yy-mm-dd',

	        onSelect: function(selected) {

	           $("#from_date").datepicker("option","maxDate", selected)

	        }

	    });  

	




	 
});
</script>
	
<div class="container">
<div class="row">
<div class="row">
<div class="col-md-12">
{{ link_to_route('admin.index', 'Dashboard', null, ['class' => 'btn-link']) }} &nbsp;>&nbsp;

{{ link_to_route('report.index', 'Report', null, ['class' => 'btn-link']) }}
<h3 class="text-center text-info">Filter Report</h3>
{{html_entity_decode(HTML::linkRoute('admin.index',Form::button('Back',['class'=>'btn btn-primary pull-right'])))}}
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
	
	{{ Form::open(['method' => 'POST', 'route' =>['report.store'],'role'=>'form','class'=>'form-horizontal'] ) }}

	<div class="form-group">

	{{Form::label('site','Site name',['class'=>'col-sm-4 control-label'])}}	
	 <div class="col-sm-8">

	{{Form::select('site_id',$site,null,['class'=>'form-control','id'=>'pin','required'])}}

	</div>
	
	</div>	
	
	<div class="form-group">

	{{Form::label('from_date','From Date',['class'=>'col-sm-4 control-label'])}}	
	 <div class="col-sm-8">
	{{Form::text('from_date',null,['class'=>'form-control','required','id'=>'from_date'])}}
	</div>
	
	</div>

	<div class="form-group">

	{{Form::label('to_date','To Date',['class'=>'col-sm-4 control-label'])}}	
	 <div class="col-sm-8">
	{{Form::text('to_date',null,['class'=>'form-control','required','id'=>'to_date'])}}
	</div>
	
	</div>

	

	 <div class="form-group">
        <div class="col-sm-5 col-sm-offset-7">
              {{Form::submit('Submit',['class'=>'btn btn-primary'])}}
        </div>
    </div>	

                                        
    {{ Form::close() }}
                            

</div>

<div class="col-md-3"></div>

</div>
</div>


@endsection