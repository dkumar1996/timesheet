@extends('layouts.default')

@section('content')
{{ HTML::script('js/jquery.dataTables.min.js') }}
	{{ HTML::style('css/jquery.dataTables.min.css') }}
	{{ HTML::script('js/dataTables.responsive.js') }}
	{{ HTML::style('css/dataTables.responsive.css') }}
<script type="text/javascript">
$(document).ready(function(){
   
  //  $('#mytable').dataTable({"ordering": false,"responsive": false});
   $('#mytable').dataTable();
});
</script>
<style type="text/css">
h3
{
	clear: both;
}
table
{
	width: 100%;
	margin: 0px 15px;
}
</style>

<div class="container">

<div class="row">
<div class="col-md-12">


{{ link_to_route('admin.index', 'Dashboard', null, ['class' => 'btn-link']) }} &nbsp;>&nbsp;

{{ link_to_route('staff.index', 'Staff Maintenance', null, ['class' => 'btn-link']) }}



{{html_entity_decode(HTML::linkRoute('staff.create',Form::button('Add New',['class'=>'btn btn-primary pull-right'])))}}
</div>
</div>
<bR>
<div class="row">
<div class="col-md-12">
<table id="mytable" class="display" cellspacing="0" width="100%">
<thead>
<tr>
<th>First Name</th>
<th>Last Name</th>
<th>Site</th>
<th>Company</th>
<th>Xero ID</th>
<th>Unpaid Break</th>
<!-- <th>Signature</th>
 -->
 <th>Edit</th>
<th>Delete</th>
</tr>
</thead>
	@if(!empty($data))
	@foreach($data as $val)
	<tr>
	<td>{{$val->first_name}}</td>
	<td>{{$val->last_name}}</td>
	<?php $data=DB::table('site_staff_relation')->where('staff_id',$val->id)->lists('site_id');?>
	<td>
	@foreach($data as $siteval)
	{{DB::table('site')->where('id',$siteval)->pluck('site_name')}}<Br>
	@endforeach
	</td>
	<td>{{DB::table('company')->where('id',$val->company)->pluck('company_name');}}</td>
	<td>{{$val->xeroId}}</td>
	<td>{{$val->unpaid_break}} Minutes</td>
	<!-- <td>{{$val->signature}}</td> -->
	<?php $delete = array('class' =>'btn-link' ,'onclick'=>'return(confirm("Are you want to Delete"))' );
	?>
	<td> {{ link_to_route('staff.edit', 'Edit',array($val->id),['class'=>'btn-link']) }}</td>
	<td>{{ Form::open(array('method' => 'DELETE', 'route' => array('staff.destroy', $val->id))) }}                       
                            {{ Form::submit('Delete',$delete) }}
                        {{ Form::close() }}</td>
	</tr>
	@endforeach
	@else
	<tr><td colspan="7" class="text-center">No Data Found.</td></tr>
	@endif
</table>	
</div>
</div>



</div>
<bR>


@endsection