<html> 
<body> 

 @if(!empty($userdata))
  <table id="mytable" class="table table-stripped">
<thead>
<tr>
<th>Time Card No</th>
<th>First Name</th>
<th>Last Name</th>
<th>Date</th>
<th>Start Time</th>
<th>End Time</th>
<th>Total Time</th>
@if($sinclude=="Yes")
<th>Login Signature</th>
<th>Logout Signature</th>@endif

</tr></thead>
	
	@foreach($userdata as $val)
	<tr>
	<td>{{$val->timecard_no}}</td>
	<td>{{$val->first_name}}</td>
	<td>{{$val->last_name}}</td>
	
	<?php

		if(is_null($val->login_time)!=TRUE)
		{


			$datetime1=$val->login_time;
			$dateformat1 = DateTime::createFromFormat('Y-m-d H:i:s',$datetime1);
			$date1=$dateformat1->format('H:i'); 

		
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
		}
		else
		{
			$totaltime="";
		}


	?>

	<td>{{$val->created_date}}</td>
	<td>{{$date1}}</td>
	<td>{{$date2}}</td>
	<td>{{$totaltime}}</td>
	

	<td>
	@if($sinclude=="Yes")
	@if( is_null($val->login_time)!=TRUE )
		
	<img width="100" height="" src="signatures/login/{{{$val->id}}}.png"/>

	@endif	@endif
	</td>
	
	

	<td>
	@if($sinclude=="Yes")
	@if(is_null($val->logout_time)!=TRUE  )
	<div style="border-bottom:1px solid #333;">
	<img width="100" height="" src="signatures/logout/{{{$val->id}}}.png"/>
	</div>
	@endif	@endif

	</td>
	
	
	
	                
                          
	</tr>
	@endforeach
	
</table>	
     @endif 
</body> 
</html>