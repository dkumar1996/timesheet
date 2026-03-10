@extends('layouts.default')

@section('content')
<style type="text/css">
.btn-link,.btn-link:hover
{
	color: #333;
}
h4
{
	clear: both;
}
</style>

<div class="container">

<div class="row">
<div class="col-md-4"></div>
<div class="col-md-4 col-md-offset-2">
<div >
	{{ html_entity_decode(HTML::linkRoute('staff.index',HTML::image('images/staff-icon.png','Logo', array('class'=>"img-responsive  center-block")))) }}
	
		<h4>{{ link_to_route('staff.index', 'Staff', null, ['class' => 'btn-link text-default']) }}</h4>
		</div>


</div>
<div class="col-md-4"></div></div>



<div class="row">
<div class="col-md-4"></div>
<div class="col-md-4 col-md-offset-2">

<div >
	{{ html_entity_decode(HTML::linkRoute('site.index',HTML::image('images/site-icon.png','Logo', array('class'=>"img-responsive")))) }}
<h4>{{ link_to_route('site.index', 'Site', null, ['class' => 'btn-link text-default']) }}</h4>
</div>


</div>
<div class="col-md-4"></div></div>


<div class="row">
<div class="col-md-4"></div>
<div class="col-md-4 col-md-offset-2">

<div>
	{{ html_entity_decode(HTML::linkRoute('timesheet.index',HTML::image('images/timesheet-icon.png','Logo', array('class'=>"img-responsive")))) }}
<h4>{{ link_to_route('timesheet.index', 'Timesheet', null, ['class' => 'btn-link text-default']) }}</h4>
</div>
</div>
<div class="col-md-4"></div></div>


<div class="row">
<div class="col-md-4"></div>
<div class="col-md-4 col-md-offset-2">

<div>
	{{ html_entity_decode(HTML::linkRoute('report.index',HTML::image('images/report.png','Logo', array('class'=>"img-responsive")))) }}
<h4>{{ link_to_route('report.index', 'Report', null, ['class' => 'btn-link text-default']) }}</h4>
</div>
</div>
<div class="col-md-4"></div></div>

<div class="row">
<div class="col-md-4"></div>
<div class="col-md-4 col-md-offset-2">

<div>
	{{ html_entity_decode(HTML::linkRoute('company.index',HTML::image('images/settings.png','Logo', array('class'=>"img-responsive")))) }}
<h4>{{ link_to_route('company.index', 'Settings', null, ['class' => 'btn-link text-default']) }}</h4>
</div>
</div>
<div class="col-md-4"></div></div>

	
</div>
	
</div>
<br>


@endsection