@extends('layouts.default')

@section('content')

<script type="text/javascript">
$(document).ready(function(){
   
    $('#mytable').dataTable();
});
</script>



<div class="container">

<div class="row">
<div class="col-md-12">
{{ link_to_route('admin.index', 'Dashboard', null, ['class' => 'btn-link']) }} &nbsp;>&nbsp;

{{ link_to_route('site.index', 'Site Maintenance', null, ['class' => 'btn-link']) }}
{{html_entity_decode(HTML::linkRoute('site.create',Form::button('Add New',['class'=>'btn btn-primary pull-right'])))}}
<!-- {{Form::button('Add New',['class'=>'btn btn-primary pull-right','id'=>'add'])}} -->
</div>
</div>
<bR>
<div class="row">
<div class="col-md-12">
<table id="mytable" class="display" cellspacing="0" width="100%">
<thead>
<tr>
<th>Site Name</th>
<th>Address</th>
<th>Assign Users</th>
<th>Edit</th>
<th>Delete</th>
</tr>
</thead>
	@if(!empty($data))
	@foreach($data as $val)
	<tr id="{{$val->id}}">

	<td>{{$val->site_name}}</td>
  <td>{{$val->address}}</td>
	<td>{{ html_entity_decode(HTML::linkRoute('site.show',HTML::image('images/assign_icon.png','Assign Users', array('class'=>"img-responsive")),[$val->id])) }}
	</td>
  <?php $delete = array('class' =>'btn-link' ,'onclick'=>'return(confirm("Are you want to Delete"))' );
  ?>
  <td> {{ link_to_route('site.edit', 'Edit',array($val->id),['class'=>'btn-link']) }}</td>
  <td>{{ Form::open(array('method' => 'DELETE', 'route' => array('site.destroy', $val->id))) }}                       
                            {{ Form::submit('Delete',$delete) }}
                        {{ Form::close() }}</td>
<!-- 	<td>{{Form::button('Edit',['class'=>'btn-link edit'])}}</td>
	<td>{{Form::button('Delete',['class'=>'btn-link delete'])}}</td> -->
	
	</tr>
	@endforeach
	@else
	<tr><td colspan="4" class="text-center">No Data Found.</td></tr>
	@endif
</table>	
<br>
</div>
</div>



</div>


@endsection


