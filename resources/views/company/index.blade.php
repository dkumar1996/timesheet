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

{{ link_to_route('company.index', 'Company Settings', null, ['class' => 'btn-link']) }}
{{html_entity_decode(HTML::linkRoute('company.create',Form::button('Add New',['class'=>'btn btn-primary pull-right'])))}}
<!-- {{Form::button('Add New',['class'=>'btn btn-primary pull-right','id'=>'add'])}} -->
</div>
</div>
<bR>
<div class="row">
<div class="col-md-12">
<table id="mytable" class="display" cellspacing="0" width="100%">
<thead>
<tr>
<th>S.No</th>
<th>Company Name</th>
<th>Edit</th>
<th>Delete</th>
</tr>
</thead>
	@if(!empty($data))
	@foreach($data as $key=> $val)
	<tr id="{{$val->id}}">
	<td>
		{{$key+1}}
	</td>
	<td>{{$val->company_name}}</td>
  
	
  <?php $delete = array('class' =>'btn-link' ,'onclick'=>'return(confirm("Are you want to Delete"))' );
  ?>
  <td > {{ link_to_route('company.edit', 'Edit',array($val->id),['class'=>'btn-link']) }}</td>
  <td>{{ Form::open(array('method' => 'DELETE', 'route' => array('company.destroy', $val->id))) }}                       
                            {{ Form::submit('Delete',$delete) }}
                        {{ Form::close() }}</td>

	</tr>
	@endforeach
	@else
	<tr><td></td><td class="text-center">No Data Found.</td><td></td><td></td></tr>
	@endif
</table>	
<br>
</div>
</div>



</div>


@endsection


