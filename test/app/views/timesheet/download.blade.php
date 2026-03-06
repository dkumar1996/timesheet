<?php
$data="";
$data[]=array('Time Card No','First Name','Last Name','Date','Start Time','End Time','Total Time','Login Signature','Logout Signature');


if(!empty($userdata))
{
foreach($userdata as $val)
{


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

	$data[]=array(
  $val->timecard_no,
  $val->first_name,
  $val->last_name,
  $val->created_date,
  $date1,
  $date2,
  $totaltime,

  );
}
}

//dd($data);
Excel::create('Staff Attendance', function($excel) use($data) {
    

    $excel->sheet('Staff Attendance', function($sheet) use($data) {

        

    
       $sheet->fromArray($data, null, 'A1', false, false);
       $sheet-> setColumnFormat(array(
		    'A'     =>  '000000000',
		    'B'     =>  '@',
		    'C'     =>  '@',
		    'D'     =>  'yy-mm-dd',
		    'E'     =>  'h:mm',
		    'F'     =>  'h:mm',
		    
       	));
       
       //$sheet->autosize(true);
    /*  $sheet->setWidth(array(
    'A'     =>  15,
    'B'     =>  15,
    'C'     =>  15,
    'D'     =>  15,
    'E'     =>  15,
    'F'     =>  15,
    'G'     =>  15,
    'H'     =>  15,
    'I'     =>  15,
    'J'     =>  15,
    'K'     =>  15,
    'L'     =>  15,
    'M'     =>  15,
    
));*/

        $sheet->setOrientation('landscape');

        $sheet->setPageMargin(0.25);

       //$sheet->setColumnFormat(array('F2:F1000' => 'yyyy-mm-dd'));


        //$sheet->setColumnFormat(array('G2:G1000' => 'date'));
    });

})->export('xlsx');


				
?>
