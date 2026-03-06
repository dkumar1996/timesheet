@extends('layouts.default')



@section ('content')
<style type="text/css">
	.form-control {width: 100%;}
	
	</style>
<div class="container">
<div class="row">
<div class="row">
<div class="col-md-12">
{{ link_to_route('admin.index', 'Dashboard', null, ['class' => 'btn-link']) }} &nbsp;>&nbsp;

{{ link_to_route('site.index', 'Site Maintenance', null, ['class' => 'btn-link']) }}
<h3 class="text-center text-info">Edit Site</h3>
{{html_entity_decode(HTML::linkRoute('site.index',Form::button('Back',['class'=>'btn btn-primary pull-right'])))}}
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
	
	{{ Form::model($site,['method' => 'PATCH', 'route' =>['site.update', $site->id],'role'=>'form','class'=>'form-horizontal'] ) }}	
	
	<div class="form-group">

	{{Form::label('name','Site Name',['class'=>'col-sm-4 control-label'])}}	
	 <div class="col-sm-8">
	{{Form::text('site_name',null,['class'=>'form-control','required'])}}
	</div>
	
	</div>

	<div class="form-group">

	{{Form::label('address','Site Address',['class'=>'col-sm-4 control-label'])}}	
	 <div class="col-sm-8">
	{{Form::textarea('address',null,['class'=>'form-control','required'])}}
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