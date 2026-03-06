@extends('layouts.default')

@section('content')
<style type="text/css">
form
{
	float: left;
	width: 100%;
}

</style>

<div class="container">
<div class="row">
<div class="row">
<div class="col-md-12">
{{ link_to_route('admin.index', 'Dashboard', null, ['class' => 'btn-link']) }} &nbsp;>&nbsp;

{{ link_to_route('site.index', 'Site Maintenance', null, ['class' => 'btn-link']) }}
<h3 class="text-center text-info">Assign Site to Staff</h3>
<h4 class="text-danger">Site Name: {{DB::table('site')->where('id',$id)->pluck('site_name')}}</h4>
{{html_entity_decode(HTML::linkRoute('site.index',Form::button('Back',['class'=>'btn btn-primary pull-right'])))}}
</div>
</div>
<bR>
<div class="col-md-12">
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
{{Form::open(['route'=>'assign_users','method'=>'POST'])}}
@if(!empty($data))
<?php $count=1;?>
<table class="table table-stripped">
<tr><th>S.No</th><th>Staff Name</th><th>Assign</th></tr>
@foreach($data as $val)
	<tr>
	<td>{{$count}}</td>
	<td>
	{{Form::label($val->id,$val->first_name)}}
	</td>
	<?php $assign_value=StaffR::where('staff_id',$val->id)->where('site_id',$id)->exists();?>
	<td>{{Form::checkbox('checkbox[]',$val->id,$assign_value,['id'=>$val->id])}}</td>
	</tr>
</div>
<?php $count++;?>
@endforeach
<tr><td colspan="2"></td><td  class="">{{Form::submit('Assign',['class'=>'btn btn-primary pull-right'])}}</td></tr>
</table>
@endif

{{Form::hidden('id',$id)}}
	{{Form::close()}}

</div>
</div>
</div>
<bR>

@endsection