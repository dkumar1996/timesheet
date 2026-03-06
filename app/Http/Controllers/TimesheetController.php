<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Site;
use App\Models\StaffR;
use App\Models\TimeSheet;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

require_once 'signature-to-image.php';
class TimesheetController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$previous_week=date('Y-m-d',strtotime("-3 week"));
		$today=date('Y-m-d');
		$data=DB::table('user_attendance')->leftJoin('user','user_attendance.userid','=','user.id')->where('user.enabled','Yes')
		->whereBetween('created_date',array($previous_week,$today))
		->select('user.timecard_no','user.first_name','user.last_name','user_attendance.login_time','user_attendance.logout_time','user_attendance.created_date','user_attendance.login_signature','user_attendance.logout_signature','user_attendance.id','user_attendance.break','user_attendance.site_id','user_attendance.added_by')
		->orderby('created_date','DESC')->get();
		//dd($data);
		return View::make('timesheet.index')
		->with('data',$data)
		->with('title','Timesheet Maintenance');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('timesheet.reportfilter')->with('title','Timesheet Maintenance');
		/*$userdata=DB::table('user_attendance')->leftJoin('user','user_attendance.userid','=','user.id')->where('user.enabled','Yes')
		->select('user.timecard_no','user.first_name','user.last_name','user_attendance.login_time','user_attendance.logout_time','user_attendance.created_date','user_attendance.login_signature','user_attendance.logout_signature','user_attendance.id')->get();
		//dd($data);
	
		//return View::make('timesheet.download',compact('userdata',$userdata),compact('title','Download'));

		Excel::create('Staff Attendance', function($excel) use($userdata) {

		 $excel->sheet('Staff Attendance', function($sheet) use($userdata){

		        $sheet->loadView('timesheet.downloadfromview')->with("userdata",$userdata);

		    });

		})->export('xls');*/


		

	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$inputs = request()->all();
		$date_valid="";
		$frdate=request()->input('from_date');
		
		if(empty($frdate)!=TRUE)
		{


		$date = DateTime::createFromFormat('Y-m-d',request()->input('from_date'));
		$date->modify('-1 day');
		$date_valid=$date->format('Y-m-d');

		}

		$rules = array(
        'from_date'		=> 'required|date_format:Y-m-d',
        'to_date'		=> 'required|date_format:Y-m-d|after:' . $date_valid
   		 );
		//var_dump($rules);

		$validator = Validator::make($inputs,$rules);


		if($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);

		}
		else
		{

			//dd(request()->all());
			$from_date=request()->input('from_date');
			$to_date=request()->input('to_date');
			$userdata=DB::table('user_attendance')->leftJoin('user','user_attendance.userid','=','user.id')->where('user.enabled','Yes')->whereBetween('user_attendance.created_date',[$from_date,$to_date])->select('user.timecard_no','user.first_name','user.last_name','user_attendance.login_time','user_attendance.logout_time','user_attendance.created_date','user_attendance.login_signature','user_attendance.logout_signature','user_attendance.id')->get();
			//dd($userdata);
		
			//return View::make('timesheet.download',compact('userdata',$userdata),compact('title','Download'));

			Excel::create('Staff Attendance', function($excel) use($userdata) {

			 $excel->sheet('Staff Attendance', function($sheet) use($userdata){

			        $sheet->loadView('timesheet.downloadfromview')->with("userdata",$userdata)->with('sinclude',request()->input('sig_include'));

			    });

			})->export('xls');

			return Redirect::route('timesheet.index');
			
			
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
		return View::make('timesheet.create')->with('title','Timesheet Maintenance');
	}


	public function storeentry()
	{
		$login_time=request()->input('login_time');
		$logout_time=request()->input('logout_time');
		$rules=[
		'created_date'=>'required',
		'site_id'=>'required',
		'userid'=>'required',
		'login_time'=>'required',
		//'logout_time'=>'required',

		];
		$input=request()->all();
		$Validator=Validator::make($input,$rules);
		if($Validator->passes())
		{
			if(!empty($logout_time))
			{
				$input['flag_login']=0;
				if(strtotime($login_time)>strtotime($logout_time))
				{
					return Redirect::back()->withErrors('Logout Time Must be greater than Login Time')->WithInput();
				}
			}
			else
			{
					//Arr::forget($input,'logout_time');
					$input['logout_time']=null;
			}

			
			Arr::forget($input,['_token','_method']);
			$input['added_by']=1;
		
			DB::table('user_attendance')->insert($input);
			
			return Redirect::route('timesheet.index');
		}
		
		else
		{
			return Redirect::back()->withErrors($Validator)->WithInput();
		}

	}

	public function ajax_storeentry()
	{
		$site_id=request()->input('site_id');

		$filtered_users=DB::table('site_staff_relation')->where('site_id',$site_id)->pluck('staff_id');

		return $filtered_users;
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$data=TimeSheet::find($id);
		return View::make('timesheet.edit')->with('title','Timesheet Maintenance')->with('data',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$login_time=request()->input('login_time');
		$logout_time=request()->input('logout_time');
		$rules=[
		'created_date'=>'required',
		'login_time'=>'required',
		//'logout_time'=>'required'
		];
		$input=request()->all();
		$Validator=Validator::make($input,$rules);
		if($Validator->passes())
		{
			if(!empty($logout_time))
			{
				$input['flag_login']=0;
			
				if(strtotime($login_time)>strtotime($logout_time))
				{
					return Redirect::back()->withErrors('Logout Time Must be greater than Login Time')->WithInput();
				}
			}
			else
			{
					//Arr::forget($input,'logout_time');
				$input['logout_time']=null;
			}

			
			Arr::forget($input,['_token','_method']);
		
			TimeSheet::where('id',$id)->update($input);
			
			return Redirect::route('timesheet.index');
		}
		
		else
		{
			return Redirect::back()->withErrors($Validator)->WithInput();
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
		TimeSheet::where('id',$id)->delete();
			
			return Redirect::route('timesheet.index');
	}


}
