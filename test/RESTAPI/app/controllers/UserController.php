<?php
/*require_once 'signature-to-image.php';
ini_set('memory_limit', '256M');
*/

class UserController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {

    
  }
 

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    $site_id=Input::get('site_id');

    $response=array();
    $input = Input::all();
    $rules=array(
      'site_id'   =>  'required',
    );
    $validation = Validator::make($input,$rules);

    if ($validation->passes())
    {  

      $site_users=DB::table('user')->Select( 'staff_id',DB::raw('CONCAT( SUBSTRING(first_name, 1, 1), ".", Last_name) AS full_name'),'first_name')
      ->join('site_staff_relation','site_staff_relation.staff_id','=','user.id')
      ->where('user.enabled','Yes')
      ->where('site_staff_relation.site_id',$site_id)
      ->orderby('full_name')->get();

      $currentdate=date('Y-m-d');

      foreach ($site_users as $key => $site_user) {
      
       $userid=$site_user->staff_id;

       $check=DB::table('user_attendance')->where('userid',$userid)
        ->where('logout_time','=',NULL)
        ->where('flag_login',1)
        ->where('created_date','<>',$currentdate)
        ->orderby('created_date','DESC')->get();

      $login_status="Force Logout";

      if(empty($check)){

        $test_login_time=DB::table('user_attendance')->where('userid',$userid)->where('created_date',$currentdate)->where('site_id',$site_id)->where('flag_login',1)->pluck('login_time');
        
          if(is_null($test_login_time))
          {
            $login_status="Sign In";
          }
          else
          {
            $test_logout_time=DB::table('user_attendance')->where('userid',$userid)->where('created_date',$currentdate)->where('site_id',$site_id)->where('flag_login',1)->pluck('logout_time');
            if(is_null($test_logout_time))
            {
             $login_status="Sign Out";
            }
            else
            {
              $login_status="Sign In";
            }
          }
      }

     
      $site_user->status=$login_status;

      $date=date('Y-m-d');
      $tvalue=DB::table('user_attendance')->where('userid',$userid)->where('created_date',$date)->where('site_id',$site_id)->orderby('login_time','DESC')->pluck('id');

      $idval=DB::table('user_attendance')->find($tvalue);
      $user_login_color_status=0;

      if(is_null($tvalue)){$user_login_color_status=0;}
      else{
        if(!is_null($idval->login_time) && !is_null($idval->logout_time) ) $user_login_color_status=2;
        elseif(!is_null($idval->login_time)) $user_login_color_status=1;
      }

      //if(!is_null($idval->login_time) && !is_null($idval->logout_time) ) $user_login_color_status=2;

     // elseif(!is_null($idval->login_time)) $user_login_color_status=1;

      $site_user->user_login_color_status=$user_login_color_status;

    
      }

      $site_info=DB::table('site')->find($site_id);



      $response['allow'] = true;
      $response['site_users'] = $site_users;
      $response['site_name'] =  $site_info->site_name;
      $response['site_id'] =  $site_id;
      return Response::json($response);
    }
    else
    {
        $response['allow'] = false;
        $response['validation']=array(['site_id'=>strip_tags($validation->messages()->first('site_id'))]);
        return Response::json($response);
    }

    
  }


  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    $response=array();

    $rules=['pin'=>'required','user_id'=>'required','login_status'=>'required','site_id'=>'required'];

    $validation=Validator::make(Input::json()->all(),$rules);

    if($validation->passes())
    {

      $user_id=Input::json()->get('user_id');
      $password=Input::json()->get('pin');
      $data = DB::table('user')->find($user_id);
    
      if(Hash::check($password,$data->password))
      {

     
            $response['user_id']=Input::json()->get('user_id');
            $response['allow']=true;
          
            return Response::json($response);
      }
      else
      {

            $response['allow']=false;
            $response['validation']=array(['pin'=>'Invalid Pin Details']);
            return Response::json($response);
      }



    }
    else
    {
        $response['allow']=false;
        $response['validation']=array([
          'pin'=>strip_tags($validation->messages()->first('pin')),
          'user_id'=>strip_tags($validation->messages()->first('user_id')),
          'site_id'=>strip_tags($validation->messages()->first('site_id')),
          'login_status'=>strip_tags($validation->messages()->first('login_status'))

          ]);
        return Response::json($response);
    }


  }

  public function getscreeninfo()
  {
    $site_id=Input::get('site_id');
    $user_id=Input::get('user_id');
    $login_type=Input::get('login_type');
    $currentdate=date('Y-m-d');

    $response=array();
    $input = Input::all();
    $rules=array(
      'site_id'   =>  'required',
      'user_id'   =>  'required',
      'login_type' =>  'required'
    );
    $validation = Validator::make($input,$rules);

    if ($validation->passes())
    { 
         $roster_start_time=DB::table('roster')->where('user_id',$user_id)->pluck('roster_start_time');
         $roster_end_time=DB::table('roster')->where('user_id',$user_id)->pluck('roster_end_time');

         $response['planned_start_time']=$roster_start_time;
         $response['planned_end_time']=$roster_end_time;

         $default_break=DB::table('user')->where('id',$user_id)->pluck('unpaid_break');

          $response['break']=$default_break;

         $site_info=DB::table('site')->find($site_id);

      $response['allow'] = true;
      $response['site_name'] = $site_info->site_name;
      $response['site_id'] =  $site_id;

      if($login_type=="Force Logout"){
         $missed_date=DB::table('user_attendance')->where('userid',$user_id)
        ->where('flag_login',1)
        ->where('logout_time','=',NULL)
        ->where('created_date','<>',$currentdate)
        ->orderby('created_date','DESC')->pluck('created_date');
        $response['signature_missed_date'] =  $missed_date;
      }
      
      return Response::json($response);

         
    }
    else
    {
        $response['allow'] = false;
        $response['validation']=array([
          'site_id'=>strip_tags($validation->messages()->first('site_id')),
          'user_id'=>strip_tags($validation->messages()->first('user_id')),
          'login_type'=>strip_tags($validation->messages()->first('login_type')),

          ]);
        return Response::json($response);
    } 

     
  }

 
 
 public function postlogin()
  {

     $response=array();

    $rules=[
    'signature'=>'required',
    'user_id'=>'required',
    'login_status'=>'required',
    'site_id'=>'required',
    'currentdate'=>'required',
    'time'=>'required',
    'login_type'=>'required',
    'actual_time' => 'required'
    ];

    $validation=Validator::make(Input::json()->all(),$rules);

    if($validation->passes())
    {
       $login_type= Input::json()->get('login_type');

       $input=Input::json()->all();

       $msg="";$message="";

       if($login_type=="Sign In") {

        $msg=$this->StoreLoginInfo($input);
        $message="You are successfully Logged In.";
      }

       if($login_type=="Sign Out") {

        $msg=$this->StoreLogoutInfo($input);
         $message="You are successfully Logged Out.";
      }

       if($login_type=="Force Logout") {
        $msg=$this->StoreMissedLoginInfo($input);
         $message="You are successfully Logged Out.";
      }

       

      

       $response['message'] = $message;

       if (is_numeric($msg)) {

         $response['timesheet_id'] = $msg;
         $response['allow'] = true;
       }
       else
       {
        $response['message'] = $msg;
        $response['allow'] = false;
       }

      

       return Response::json($response);

    }
    else
    {
       $response['allow'] = false;
       $response['validation']=array([
          'signature'=>strip_tags($validation->messages()->first('signature')),
          'user_id'=>strip_tags($validation->messages()->first('user_id')),
          'login_type'=>strip_tags($validation->messages()->first('login_type')),
          'login_status'=>strip_tags($validation->messages()->first('login_status')),
          'site_id'=>strip_tags($validation->messages()->first('site_id')),
          'time'=>strip_tags($validation->messages()->first('time')),
          'currentdate'=>strip_tags($validation->messages()->first('currentdate')),
          'actual_time'=>strip_tags($validation->messages()->first('actual_time')),


          ]);
        return Response::json($response);
    }

   



  }


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {


    

  }

  public function StoreMissedLoginInfo($input)
  {


      $timesheet_id='';

      $input=(object)$input;
      
      $user_id=$input->user_id;

      $site_id=$input->site_id;
      
      $login_status=$input->login_status;

      $time=$input->time;

      $actual_time=$input->actual_time;

      $currentdate=$input->currentdate;

      $login_type=$input->login_type;


       

    $check=DB::table('user_attendance')->where('userid',$user_id)
        ->where('flag_login',1)
        ->where('logout_time','=',NULL)
        ->where('created_date',$currentdate)
        ->pluck('id');
      
      if(!empty($check))
      {

        $alcheck=DB::table('user_attendance')->find($check);

      
        $login_time=$alcheck->login_time;
        
        //$format_again=DateTime::createFromFormat('Y-m-d H:i:s', $login_time);

        //$logged_in_time=$format_again->format('Y-m-d H:i');


        $logout_time=$time;
        
      
        if(strtotime($login_time)>=strtotime($logout_time))
        {
          return "Logout Time is smaller than Login Time(". $login_time. ")";
        }
        else
        {

          $json = Input::get('customer_signature');
          
         
          $dataall=array(
              'flag_login'=>0,
              'logout_time'=>$time,
              'logout_type'=>$login_status,
              'actual_logout_time'=>$actual_time
              
            );
          
          
          DB::table('user_attendance')->where('id',$check)->update($dataall);
          $destinationPath = '../signatures/logout/';

          $photo_id= $input->signature;

          if (base64_decode($photo_id, true) == true)
          {
             
             
              $full_filename=$check.'.png';
              $decode_img=base64_decode($photo_id);
              $success = file_put_contents($destinationPath.$full_filename, $decode_img);
             
              return $check;
             
          }
    
          
        
      }
    }
     return "No Record matches with your Input Information";
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
   
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {

  }
  public function StoreLoginInfo($input)
  {

      $timesheet_id='';

      $input=(object)$input;
      
      $user_id=$input->user_id;

      $site_id=$input->site_id;
      
      $login_status=$input->login_status;

      $time=$input->time;

      $actual_time=$input->actual_time;

      $currentdate=$input->currentdate;

      $login_type=$input->login_type;


      $dataall=array(
              'userid'=>$user_id,
              'site_id'=>$site_id,
              'login_time'=>$time,
              'created_date'=>$currentdate,
              'login_type'=>$login_status,
              'actual_login_time'=>$actual_time
            );

      $timesheet_id=DB::table('user_attendance')->insertGetId($dataall);

      $destinationPath = '../signatures/login/';

      $photo_id= $input->signature;

      if (base64_decode($photo_id, true) == true)
      {
         
         
          $full_filename=$timesheet_id.'.png';
          $decode_img=base64_decode($photo_id);
          $success = file_put_contents($destinationPath.$full_filename, $decode_img);
         
         /* if($success)
          {
          }*/
         
      }

      return $timesheet_id;

        


  }

  public function StoreLogoutInfo($input)
  {
      $timesheet_id='';

      $input=(object)$input;
      
      $user_id=$input->user_id;

      $site_id=$input->site_id;
      
      $login_status=$input->login_status;

      $time=$input->time;

      $actual_time=$input->actual_time;

      $currentdate=$input->currentdate;

      $login_type=$input->login_type;

      $break = $input->break;

       $dataall=array(
              'flag_login'=>0,
              'break'=>$break,
              'logout_time'=>$time,
              'logout_type'=>$login_status,
              'actual_logout_time'=>$actual_time
            );

      $timesheet_id=DB::table('user_attendance')->where('userid',$user_id)->where('created_date',$currentdate)->where('site_id',$site_id)->where('flag_login',1)->pluck('id');
      DB::table('user_attendance')->where('id',$timesheet_id)->update($dataall);

      $destinationPath = '../signatures/logout/';

      $photo_id= $input->signature;

      if (base64_decode($photo_id, true) == true)
      {
         
         
          $full_filename=$timesheet_id.'.png';
          $decode_img=base64_decode($photo_id);
          $success = file_put_contents($destinationPath.$full_filename, $decode_img);
         
         /* if($success)
          {
          }*/
         
      }

      return $timesheet_id;

      

  }
 

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    //
  }


}
