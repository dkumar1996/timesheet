<?php
require_once 'signature-to-image.php';
ini_set('memory_limit', '256M');


class UserController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {

    $site=DB::table('site')->lists('site_name','id');
    //$data=User::where('enabled','Yes')->get();
    return View::make('user.index')->with('title','User Management')
    ->with('site',$site);

  }
  public function changelocation()
  {
    $site=DB::table('site')->lists('site_name','id');
    //$data=User::where('enabled','Yes')->get();
    return View::make('user.index')->with('title','User Management')
    ->with('site',$site);
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    //
  }


  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {

    $validator=Validator::make(Input::all(),['pin'=>'required']);
    if($validator->passes())
    {
      $credentials=['password'=>Input::get('pin'),'email'=>Input::get('email')];

      if(Auth::user()->validate($credentials))
      {

        //$currentdate=date('Y-m-d');

        $userid=User::where('email',Input::get('email'))->pluck('id');
        $getlists=DB::table('user_attendance')->where('userid',$userid)
        ->where('logout_time','=',NULL)
        ->where('flag_login',1)
        //->where('created_date','<>',$currentdate)
        ->orderby('created_date','DESC')->get();
		
		
		//dd($minutes);
        //dd($getlists, date('Y-m-d H:i:s'),date_default_timezone_get(),ini_get('date.timezone'),new Carbon('2018-10-04 15:00:03'));



        if(empty($getlists))
        {
          //$test=DB::table('user_attendance')->where('userid',$userid)->where('created_date',$currentdate)->where('site_id',Input::get('site_id'))->where('flag_login',1)->pluck('login_time');

          //dd($test);
          //if(is_null($test))
          //{
            return Redirect::route('show_login',[Input::get('site_id'),$userid]);
        }
          else
          {
			foreach($getlists as $list){
				
				$start_date = new DateTime($list->login_time);
				$since_start = $start_date->diff(new DateTime());
				$minutes = $since_start->days * 24 * 60;
				$minutes += $since_start->h * 60;
				$minutes += $since_start->i;
				if($minutes > 720){
					return Redirect::route('force_logout',[$userid, Input::get('site_id')]);
				}
				else{
					return Redirect::route('show_logout',[Input::get('site_id'),$userid]);
				}
			}
			return Redirect::route('show_login',[Input::get('site_id'),$userid]);
          }

        //}

        //else////////
        //{
          //return Redirect::route('force_logout',[$userid, Input::get('site_id')]);


        //}

      }
      else
      {
        return Redirect::back()->withErrors($validator)->withErrors(['error'=>'Invalid Login Details']);
      }
    }
    else
    {
      return Redirect::back()->withErrors($validator)->WithInput();
    }


  }

  public function show_login($site_id,$id)
  {

     $data=User::find($id);
     $site=[''=>'Please Select']+DB::table('site')->lists('site_name','id');


     $roster_start_time=DB::table('roster')->where('user_id',$id)->pluck('roster_start_time');
     $roster_end_time=DB::table('roster')->where('user_id',$id)->pluck('roster_end_time');

      return View::make('user.show_login')->with('title','User Management')
         ->with('r_start_time',$roster_start_time)
         ->with('r_end_time',$roster_end_time)
         ->with('site',$site)
         ->with('site_id',$site_id)
         ->with('data',$data);
  }

  public function show_logout($site_id,$id)
  {
    $data=User::find($id);

    $roster_start_time=DB::table('roster')->where('user_id',$id)->pluck('roster_start_time');
    $roster_end_time=DB::table('roster')->where('user_id',$id)->pluck('roster_end_time');
	
	$logindetails=DB::table('user_attendance')->where('userid',$id)
        ->where('flag_login',1)
		->where('site_id',$site_id)
        ->where('logout_time','=',NULL)
		->first();

    $site=[''=>'Please Select']+DB::table('site')->lists('site_name','id');
    
     return View::make('user.show_logout')->with('title','User Management')
       ->with('r_start_time',$roster_start_time)
       ->with('r_end_time',$roster_end_time)
       ->with('site',$site)
       ->with('site_id',$site_id)
	   ->with('logindetails',$logindetails)
	   //->with('loggedISOTime',$loggedISOTime)
       ->with('data',$data);
  }

  public function force_logout($id,$site_id)
  {
     $data=User::find($id);
     $currentdate=date('Y-m-d');
     $roster_end_time=DB::table('roster')->where('user_id',$id)->pluck('roster_end_time');
     $logindetails=DB::table('user_attendance')->where('userid',$id)
        ->where('flag_login',1)
        ->where('logout_time','=',NULL)
        //->where('created_date','<>',$currentdate)
        //->orderby('created_date','DESC')->get();
		->first();
	$loggedTime = new DateTime($logindetails->login_time);
	$loggedTime->format('m-Y');
	$loggedTime->modify('-1 month');
	$loggedISOTime= $loggedTime->format('Y-m-d-H-i-s-0');

        // dd($data);
    // $site=[''=>'Select Site']+DB::table('site')->lists('site_name','id');
     return View::make('user.force_logout')->with('title','User Management')
    //->with('exists',$check)
    //->with('site',$site)
    ->with('site_id',$site_id)
    ->with('message',$logindetails)
	->with('loggedISOTime',$loggedISOTime)
    ->with('r_end_time',$roster_end_time)
    ->with('data',$data);
  }
 public function post_forcelogout($id)
  {
    $input=Input::all();
    //dd($input);
    array_forget($input,['_token','site_id']);

    $rules=[
          //'date'          =>'required|date_format:Y-m-d',
          //'time'          =>'required|date_format:H:i',
          //'site_name'       =>'required',
          'customer_signature'  =>'required'
         ];


    $Validator=Validator::make($input,$rules);

    if($Validator->passes())
    {
	  $user_attendance = TimeSheet::find(Input::get('user_attendance_id'));
	  $logout_time = Input::get('loggedOutTime') ;
	  $login_type=Input::get('login_type');
	  if ($login_type=="1") $login_type_text="roster";
	  else $login_type_text="actual";
	  $json = Input::get('customer_signature');
      $site_id=Input::get('site_id');
	  
	  if(strtotime($user_attendance->login_time)>=strtotime($logout_time))
      {
		$loggedTime = new DateTime($user_attendance->login_time);
		$loggedTime->format('m-Y');
		$loggedTime->modify('-1 month');
		$loggedISOTime= $loggedTime->format('Y-m-d-H-i-s-0');
		$id=Input::get('userid');
		$data=User::find($id);
		$roster_end_time=DB::table('roster')->where('user_id',$id)->pluck('roster_end_time');
		
        return Redirect::back()->WithInput()
            ->with('signature',$json)
			->with('site_id',$site_id)
			->with('message',$user_attendance)
			->with('loggedISOTime',$loggedISOTime)
			->with('r_end_time',$roster_end_time)
			->with('data',$data)
            ->withErrors(['errors'=>'Logout Time is smaller than Login Time(Login Time: '. $user_attendance->login_time.')']);
      }
	  else{
	   $dataall=array(

                //'logout_sig_hash'=>Hash::make($json),
                //'logout_signature'=>$json,
                'flag_login'=>0,
                'logout_time'=>$logout_time , 
                'actual_logout_time'=>Input::get('date'),
                'logout_type'=>$login_type_text
                );
		//dd($dataall,Input::all());
          TimeSheet::where('id',$user_attendance->id)->update($dataall);

            $target_dir = "signatures/logout/";
            $img = sigJsonToImage($json,array('imageSize'=>array(700, 230)));
            imagepng($img, $target_dir.$user_attendance->id.'.png');
            imagedestroy($img);

          return Redirect::route('user.show',['id'=>$site_id])
            ->with('smessage','You are successfully Logged out!');
	  }
      
    }
    else
    {
		$loggedTime = new DateTime($user_attendance->login_time);
		$loggedTime->format('m-Y');
		$loggedTime->modify('-1 month');
		$loggedISOTime= $loggedTime->format('Y-m-d-H-i-s-0');
		$id=Input::get('userid');
		$data=User::find($id);
		$roster_end_time=DB::table('roster')->where('user_id',$id)->pluck('roster_end_time');
		return Redirect::back()->WithInput()
			->with('site_id',$site_id)
			->with('message',$user_attendance)
			->with('loggedISOTime',$loggedISOTime)
			->with('r_end_time',$roster_end_time)
			->with('data',$data)
			->withErrors($Validator);
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


    $data=DB::table('user')->Select( '*',DB::raw('CONCAT( SUBSTRING(first_name, 1, 1), ".", Last_name) AS full_name'))
    ->join('site_staff_relation','site_staff_relation.staff_id','=','user.id')
    ->where('user.enabled','Yes')
    ->where('site_staff_relation.site_id',$id)
    ->orderby('full_name')->distinct()->get();

    //dd($data);

    $site=DB::table('site')->lists('site_name','id');
    return View::make('user.show_users')->with('title','User Management')
    ->with('site',$site)
    ->with('site_id',$id)
    ->with('data',$data);

  }

  public function showlogoff($userid,$site_id)
  {
    $data=DB::table('user')
    ->join('site_staff_relation','site_staff_relation.staff_id','=','user.id')
    ->where('user.enabled','Yes')
    ->where('site_staff_relation.site_id',$site_id)->get();

    return View::make('user.showlogoff')->with('title','User Management')
    //->with('site',$site)
    ->with('site_id',$id)
    ->with('data',$data);

  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $data=User::find($id);
    //$site=[''=>'Please Select']+DB::table('site')->lists('site_name','id');
    return View::make('user.login')->with('title','User Management')
    ->with('site',$site)
    ->with('default',$id)
    ->with('data',$data);
  }
  public function post_edit($site_id,$userid)
  {
    $currentdate=date('Y-m-d');

    $login_status="Sign In";

    $test1=DB::table('user_attendance')->where('userid',$userid)->where('created_date',$currentdate)->where('site_id',$site_id)->where('flag_login',1)->pluck('login_time');

    if(is_null($test1))
    {
      $login_status="Sign In";
    }
    else
    {
      $login_status="Sign Out";
    }

    $data=User::find($userid);

    $site=DB::table('site')->lists('site_name','id');
    return View::make('user.login')->with('title','User Management')
    ->with('site',$site)
    ->with('site_id',$site_id)
    ->with('login_status',$login_status)
    ->with('data',$data);
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
  public function loginupdate()
  {

      //var_dump(Input::all());

        $json = Input::get('customer_signature');

        $currentdate=date('Y-m-d',strtotime(Input::get('date')));

        $currentdateandtime=date('Y-m-d H:i:s',strtotime(Input::get('date')));

        $login_type=Input::get('login_type');

        $time=Input::get('date');

        $user_id=Input::get('userid');
        
        // dd($login_type);
        if ($login_type=="1") {
          $login_type_text="roster";
        }
        else {
          $login_type_text='actual';
        }

        $login_time=$currentdateandtime;
        if($login_type_text=='roster'){
          $roster_time=DB::table('roster')->where('user_id',$user_id)->pluck('roster_start_time');
          $login_time=$currentdate.' '.$roster_time;
        }

        //dd($login_time);

        $dataall=array(

              //'login_sig_hash'=>Hash::make($json),
              //'login_signature'=>$json,
              'userid'=>Input::get('userid'),
              'site_id'=>Input::get('site_id'),
              'login_time'=>(empty($login_time)) ? $currentdateandtime : $login_time,
              'created_date'=>$currentdate,
              'login_type'=>$login_type_text,
              'actual_login_time'=>$currentdateandtime

            );


        $imgid=DB::table('user_attendance')->insertGetId($dataall);

        /*if ($login_type_text=='actual') {

          $date_format_24=date('G:i');
          $time = date("H:i:s",strtotime($time));
          // DB::table('user_attendance')->where('id',$imgid)->update(['actual_login_time'=>$currentdateandtime]);
        }
        else {

          
          $roster_time=DB::table('roster')->where('user_id',$user_id)->lists('roster_start_time');
          DB::table('user_attendance')->where('id',$imgid)->update(['actual_login_time'=>$currentdateandtime,'login_time'=>$currentdate.' '.$roster_time[0]]);
        }*/

          $target_dir = "signatures/login/";
          $img = sigJsonToImage($json,array('imageSize'=>array(700, 230)));
          imagepng($img, $target_dir.$imgid.'.png');
          imagedestroy($img);
        return Redirect::route('user.show',['id'=>Input::get('site_id')])
        ->with('smessage','You are successfully logged in.');


  }

  public function logoutupdate()
  {
        $json = Input::get('customer_signature');

        $currentdate=date('Y-m-d',strtotime(Input::get('date')));
        $currentdateandtime=date('Y-m-d H:i:s',strtotime(Input::get('date')));
        $current_time=date('H:i:s',strtotime(Input::get('date')));

        $login_type=Input::get('login_type');
        // exit();
        $break=Input::get('break');
        $time=Input::get('date');
        $user_id=Input::get('userid');

         // $roster_end_time=DB::table('roster')->where('user_id',$user_id)->pluck('roster_end_time');
         // $roster_start_time=DB::table('roster')->where('user_id',$user_id)->pluck('roster_start_time');

        if ($login_type=="1") {
          $login_type_text="roster";
          // $roster_time=$roster_end_time;
        }
        else {
          $login_type_text='actual';
          
          // $roster_time=$current_time;
        }

        $logout_time=$currentdateandtime;
        if($login_type_text=='roster'){
          $roster_time=DB::table('roster')->where('user_id',$user_id)->pluck('roster_end_time');
          $logout_time=$currentdate.' '.$roster_time;
        }

      

        $dataall=array(

              //'logout_sig_hash'=>Hash::make($json),
              //'logout_signature'=>$json,
              'flag_login'=>0,
              'logout_time'=>$logout_time,
              'break'=>$break,
              'logout_type'=>$login_type_text,
              'actual_logout_time'=>$currentdateandtime

              );
		//dd($dataall,Input::all());
        /*$imgid=TimeSheet::where('userid',Input::get('userid'))->where('created_date',$currentdate)
        ->where('site_id',Input::get('site_id'))->where('flag_login',1)
        ->pluck('id');*/
		$imgid=Input::get('user_attendance_id');	
        TimeSheet::where('id',$imgid)->update($dataall);


        /*if ($login_type_text=='actual') {
          $time = date("H:i:s",strtotime($time));
          DB::table('user_attendance')->where('id',$imgid)->update(['actual_logout_time'=>$currentdateandtime]);
        }
        else {
          $user_id=Input::get('userid');
          $roster_time=DB::table('roster')->where('user_id',$user_id)->lists('roster_end_time');
          DB::table('user_attendance')->where('id',$imgid)->update(['actual_logout_time'=>$currentdateandtime,'logout_time'=>$currentdate.' '.$roster_time[0]]);
        }*/
          $target_dir = "signatures/logout/";
          $img = sigJsonToImage($json,array('imageSize'=>array(700, 230)));
          imagepng($img, $target_dir.$imgid.'.png');
          imagedestroy($img);

        return Redirect::route('user.show',['id'=>Input::get('site_id')])
        ->with('smessage','You are successfully logged out.');



  }
  public function post_site()
  {
    $validator=Validator::make(Input::all(),['site_id'=>'required']);
    if($validator->passes())
    {
      return Redirect::route('user.show',['id'=>Input::get('site_id')]);
    }
    else
    {
      return Redirect::back()->WithInput()->WithErrors($validator);
    }
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
