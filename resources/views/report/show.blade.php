<?php 
 clearstatcache();
  function alltime($times,$sign) {
      $seconds = 0;
      $tot_times=count($times);
      for ($i=0;$i<$tot_times;$i++)
      {

        list($hour,$minute,$second) = explode(':', $times[$i]);
        //echo "hours".$hour." minutes".$minute." seconds".$second;
      /*  if($sign[$i]=="" || $sign[$i]==" ")
        {
        	//echo "sign+";
        	$seconds += $hour*3600;
	        $seconds += $minute*60;
	        $seconds += $second;
        }
        else
        {   //echo "sign-";
        	$seconds -= $hour*3600;
	        $seconds -= $minute*60;
	        $seconds -= $second;
        }*/
        $seconds += $hour*3600;
	        $seconds += $minute*60;
	        $seconds += $second;
      }
      //echo $seconds;
      $seconds=round($seconds);
      if($seconds>0)
      {
        $hours = floor($seconds/3600);
      }
      else
      {
      	 $hours = ceil($seconds/3600);
      }
     
      $seconds -= $hours*3600;
      $minutes  = floor($seconds/60);
      $seconds -= $minutes*60;
       if( $seconds < 9)
      {
         $seconds = "0".$seconds;
      }
      if( $minutes < 9)
      {
         $minutes = "0".$minutes;
      }
       if( $hours < 9)
      {
         $hours = "0".$hours;
      }
   /*   if( $seconds >0 AND $seconds < 9)
      {
      $seconds = "0".$seconds;
      }
      if($minutes >0 AND $minutes < 9)
      {
      $minutes = "0".$minutes;
      }
       if($hours >0 AND $hours < 9)
      {
      $hours = "0".$hours;
      }
*/
      //$minutes=abs($minutes);
      return "{$hours}:{$minutes}";
    }
   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="Cache-Control" content="no-store" />
	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<META HTTP-EQUIV="Expires" CONTENT="-1">
	<style>
	table {page-break-before:auto;}
		th,td{border:1px solid black;text-align:center;font-size:12px;font-weight:normal;}
		.box{width:100%;margin-top:30px;}		
		.box1, .box2, .box3, .box4, .box5 {border: 1px solid black;display: inline-block;height:65px;font-size: 12px}
		.box1,.box3,.box4,{width:23.8%;}
		.box2{width: 20%;}
		.box5{width:25%;}
		.color{background:#EAF9FF;padding:0;}
		.top-head {line-height: 5px;}
		/*.box1, .box2, .box3 h5, .box4 h5, .box5 h5 {margin-top: 10px; font-size: 11px}*/
		.noborder .noborder1{border-bottom:0;border-left:0;}
		.noborderall{border:0;}
		.container {margin: auto;width: 100%;font-family: sans-serif;}
		.clearfix{page-break-after: always;}
		p {color: #FFB239 ;font-weight: bold;}
	</style>
</head>
	<body style="padding:10px; margin:0;">
	<div class="container">
	<?php $ttc=count($data);$intcont=1;?>
		@if(count($data)>0)
		@foreach($data as $val)
		<div class="top-head">
			<center><h1><u><strong>PRE PTY LTD</strong></u></h1>
			<h3><u><strong>TIME SHEET</strong></u></h3>
			<h5>ABN 833379876323</h5>
			<h5>{{$sitename}}</h5>
			</center>
		</div>

		
		<table style="border-collapse: collapse;" >
		<thead>
			<tr>
			<th>DAY</th>
			<th>DATE</th>
			<th>START</th>
			<th>UNPAID BREAKS</th>
			<th>FINISH</th>
			<th>TOTAL HOURS LESS BREAKS</th>
			<th>DESCRIPTION</th>
			<th>Employee Signature</th>
			<th class="color">OFFICE USE ONLY</th>
			<th class="color">OFFICE USE ONLY</th>
			<th class="color">OFFICE USE ONLY</th>
			<th class="color">OFFICE USE ONLY</th>	
			</tr>
		</thead>	<tbody>	
			<?php $countin=count($val); $count=1; $allsign=""; $alltimes=array();?>

			@foreach($val as $innerval)

	
			<tr>
				<td>{{date('D',strtotime($innerval->created_date))}}</td>

		<?php
		$tottimecal=0;
		if(is_null($innerval->login_time)!=TRUE)
		{


			$datetime1=$innerval->login_time;
			$dateformat1 = DateTime::createFromFormat('Y-m-d H:i:s',$datetime1);
			$date1=$dateformat1->format('H:i'); 

			$time = strtotime($date1);
			$round = 15*60;
			$rounded = round($time / $round) * $round;
			$date1=date("H:i", $rounded);

			$tarr=explode(":", $date1);
			$tottimecal=intval($tarr[0])*60+intval($tarr[1]);
			
		}
		else
		{
			$date1="";
		}
		if(is_null($innerval->logout_time)!=TRUE)
		{
			$datetime2=$innerval->logout_time;
			$dateformat2 = DateTime::createFromFormat('Y-m-d H:i:s',$datetime2);
			$date2=$dateformat2->format('H:i');

			$time = strtotime($date2);
			$round = 15*60;
			$rounded = round($time / $round) * $round;
			$date2=date("H:i", $rounded);

			$tarr=explode(":", $date2);
			$tottimecal=intval($tarr[0])*60+intval($tarr[1])-intval($tottimecal);
		}
		else
		{
			$date2="";
		} 

		if(is_null($innerval->logout_time)!=TRUE AND is_null($innerval->login_time)!=TRUE )
		{
			$tmins=abs($tottimecal);
			$hurs=sprintf("%02d",floor($tmins/60));
			$mnutes=sprintf("%02d",floor($tmins%60));
			$totaltime=$hurs.":". $mnutes;
			

			$tarr=explode(":", $totaltime);
			$tmins=intval($tarr[0])*60+intval($tarr[1])-intval($innerval->break);
			$sign="-";
			if($tmins>=0){$sign="";}
			
			
			$tmins=abs($tmins);
			if($tmins<120)
			{
				$sign="";
				$totaltime="02:00";
				$alltimes[]="02:00:00";
				$allsign[]=$sign;
			
			}
			else
			{
				$hurs=sprintf("%02d",floor($tmins/60));
				$mnutes=sprintf("%02d",floor($tmins%60));
				$totaltime=$sign."".$hurs.":". $mnutes;
				$alltimes[]=$hurs.":". $mnutes.":00";
				$allsign[]=$sign;
			
			}
			
		}
		else
		{
			$totaltime="";
		}

		//var_dump($allsign);
	?>


				<td>{{date('d/m/Y',strtotime($innerval->created_date))}}</td>
				<td>{{$date1}}</td>
				<td>
				<?php 
				$min=$innerval->break;
				$hou=sprintf("%02d",floor($min/60));
				$min=sprintf("%02d",floor($min%60));
				$dtime=$hou.":". $min;
				?>
				{{ $dtime }}
				 </td>
				<td>{{$date2}} </td>

				<td>{{$totaltime}}</td>



				<td> </td>
				<td>	
				<div align="center" style="margin-top:10px;">{{HTML::image('signatures/login/'.$innerval->id.'.png','Signature',['width'=>100])}}</div>	 
				</td>
				<td class="color"></td>
				<td class="color"></td>
				<td class="color"></td>
				<td class="color"></td>
			</tr>
			@if($count==$countin)
			<tr class="noborder">
				<td colspan="5" class="noborder1"><center><u><b>ALL BREAKS MUST BE TAKEN AND <Br>SHOWN ABOVE</b></u></center></td>
				<td><p>{{alltime($alltimes,$allsign)}}
				
				</p></td>
				<td class="noborderall"></td>
				<td class="noborderall"></td>
				<td class="color"></td>
				<td class="color"></td>
				<td class="color"></td>
				<td class="color"></td>
			</tr>

			@endif
			<?php $count++;?>
			@endforeach	
			<?php unset($alltimes); unset($allsign);?>
			</tbody> 				
		</table>
		<br>
		<div class="box">
			<div class="box1">
				<center><u>EMPLOYEE NAME</u></center>
				<div align="center" style="margin-top:10px;">{{$innerval->first_name}} {{$innerval->last_name}}</div>					
			</div>
			<div class="box3">
				<center><u>VEHICLE / YARD</u></center>
			</div>
			<div class="box4">
				<center><u>WEEK ENDING</u></center>
			</div>
			<div class="box5">
				<center><u>DEPOT MANAGER SIGNATURE</u></center>
			</div>
		</div>
		@if($ttc==$intcont)
		<div class=""></div>
		@else
		<div class="clearfix" ></div><br><Br>
		@endif
		<?php $intcont++;?>
@endforeach

		@endif
	</div>

	</body>
</html>
