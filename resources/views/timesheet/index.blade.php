@extends('layouts.default')

@section('content')
	
<script type="text/javascript">
$(document).ready(function(){
   
    $('#mytable').dataTable();

});
</script>

<style type="text/css">
	.container{width: 1200px;}
	.btn{margin-right: 10px;}
</style>



<div class="container">



<div class="row">
<div class="col-md-12">
{{ link_to_route('admin.index', 'Dashboard', null, ['class' => 'btn-link']) }} &nbsp;>&nbsp;

{{ link_to_route('timesheet.index', 'Timesheet Maintenance', null, ['class' => 'btn-link']) }}

{{html_entity_decode(HTML::linkRoute('timesheet.show',Form::button('Add New',['class'=>'btn btn-primary pull-right']),[1]))}}
{{html_entity_decode(HTML::linkRoute('timesheet.create',Form::button('Download Excel',['class'=>'btn btn-primary pull-right'])))}}
</div>
</div>
<bR>
<div class="row">
<div class="col-md-12">
<?php $totaltime="" ;?>
<table id="mytable" class="compact display" cellspacing="0" width="100%">
<thead>
<tr>
<th>Time Card No</th>
<th>First Name</th>
<th>Last Name</th>
<th>Date</th>
<th>Site</th>
<th>Start Time</th>
<th>End Time</th>
<th>Unpaid Break</th>
<th>Total Time</th>
<th>Login Signature</th>
<th>Logout Signature</th>

<th>Edit</th>
<!-- <th>Delete</th>
 --></tr></thead>
	@if(!empty($data))
	@foreach($data as $val)
	<tr>
	<td>{{$val->timecard_no}}</td>
	<td>{{$val->first_name}}</td>
	<td>{{$val->last_name}}</td>
	
	<?php
		$tottimecal=0;
		if(is_null($val->login_time)!=TRUE)
		{


			$datetime1=$val->login_time;
			$dateformat1 = DateTime::createFromFormat('Y-m-d H:i:s',$datetime1);
			$date1=$dateformat1->format('H:i'); 

			/*$time = strtotime($date1);
			$round = 15*60;
			$rounded = round($time / $round) * $round;
			$date1=date("H:i", $rounded);

			$tarr=explode(":", $date1);
			$tottimecal=intval($tarr[0])*60+intval($tarr[1]);*/


			/*$tarr=explode(':', $date1);
			echo intval($tarr[1])."<br>";
			$part2="";
			if(intval($tarr[1])<=5){$part2=2;}
			echo $part2."<br>";*/

		
		}
		else
		{
			$date1="";
		}
		if(is_null($val->logout_time)!=TRUE)
		{
			$datetime2=$val->logout_time;
			$dateformat2 = DateTime::createFromFormat('Y-m-d H:i:s',$datetime2);
			$date2=$dateformat2->format('H:i');

			/*$time = strtotime($date2);
			$round = 15*60;
			$rounded = round($time / $round) * $round;
			$date2=date("H:i", $rounded);

			$tarr=explode(":", $date2);
			$tottimecal=intval($tarr[0])*60+intval($tarr[1])-intval($tottimecal);*/
		}
		else
		{
			$date2="";
		} 

		if(is_null($val->logout_time)!=TRUE AND is_null($val->login_time)!=TRUE )
		{
			$date_a = new DateTime($val->logout_time);
			$date_b = new DateTime($val->login_time);
			$interval = date_diff($date_a,$date_b);
			$totaltime=$interval->format('%H:%i');

			/*$time = strtotime($totaltime);
			$round = 15*60;
			$rounded = round($time / $round) * $round;
			$totaltime=date("H:i", $rounded);
		
			$tmins=abs($tottimecal);
			$hurs=sprintf("%02d",floor($tmins/60));
			$mnutes=sprintf("%02d",floor($tmins%60));
			$totaltime=$hurs.":". $mnutes;*/
			

			$tarr=explode(":", $totaltime);
			$tmins=intval($tarr[0])*60+intval($tarr[1])-$val->break;
			$sign="-";
			if($tmins>=0){$sign="";}
			
			
			$tmins=abs($tmins);
			$hurs=sprintf("%02d",floor($tmins/60));
			$mnutes=sprintf("%02d",floor($tmins%60));
			$totaltime=$sign."".$hurs.":". $mnutes;
			
		}
		else
		{
			$totaltime="";
		}


	?>

	<td>{{$val->created_date}}</td>
	<td>{{DB::table('site')->where('id',$val->site_id)->pluck('site_name')}}</td>

	<?php 
	
	?>
	<td>{{$date1}}</td>
	<td>{{$date2}}</td>
	<td>
		<?php 
				$min=$val->break;
				$hou=sprintf("%02d",floor($min/60));
				$min=sprintf("%02d",floor($min%60));
				$dtime=$hou.":". $min;
				?>
				{{ $dtime }}
	</td>
	<td>{{$totaltime}}</td>
	

	<td>
	@if( is_null($val->login_time)!=TRUE )
	
	@if(File::exists('signatures/login/'.$val->id.'.png'))	
	<img width="100" height="50" src="{{URL::asset('signatures/login/'.$val->id.'.png')}}"/>
	@endif
	@endif
	</td>
	
	

	<td>
	@if(is_null($val->logout_time)!=TRUE)
    @if(File::exists('signatures/logout/'.$val->id.'.png'))	
	<img width="100" height="50" src="{{URL::asset('signatures/logout/'.$val->id.'.png')}}"/>
	@endif
	@endif

	</td>
	
	 <?php $delete = array('class' =>'btn-link' ,'onclick'=>'return(confirm("Are you want to Delete"))' );
  ?>
	<td> {{ link_to_route('timesheet.edit', 'Edit',array($val->id),['class'=>'btn-link']) }}
	@if($val->added_by)

	| 
{{ Form::open(array('method' => 'DELETE', 'route' => array('timesheet.destroy', $val->id))) }}                       
                            {{ Form::submit('Delete',$delete) }}
                        {{ Form::close() }}


	@endif

	</td>
	                
                          
	</tr>
	@endforeach
	@else
	<tr><td colspan="12" class="text-center">No Data Found.</td></tr>
	@endif
</table>	
</div>
</div>



</div>
<br>

@endsection