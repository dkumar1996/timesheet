<?php

class ReportController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$site=DB::table('site')->lists('site_name','id');
		return View::make('report.create')
		->with('site',$site)
		->with('title','Report Management');
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
		$inputs = Input::all();
		$date_valid="";
		$frdate=Input::get('from_date');
		
		if(empty($frdate)!=TRUE)
		{


		$date = DateTime::createFromFormat('Y-m-d',Input::get('from_date'));
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

			//dd(Input::all());
			$users=DB::table('user')->where('user.enabled','Yes')->lists('email');
			//var_dump($users);
			$site_id=Input::get('site_id');
			$from_date=Input::get('from_date');
			$to_date=Input::get('to_date');
			$userdata=DB::table('user_attendance')->leftJoin('user','user_attendance.userid','=','user.id')->where('user_attendance.site_id',$site_id)->where('user.enabled','Yes')->whereBetween('user_attendance.created_date',[$from_date,$to_date])->select('user.id','user.email','user.timecard_no','user.first_name','user.last_name','user_attendance.login_time','user_attendance.logout_time','user_attendance.created_date','user_attendance.login_signature','user_attendance.logout_signature','user_attendance.id','user_attendance.break')->get();
			//dd($userdata);
			$sitename=DB::table('site')->where('id',$site_id)->pluck('site_name');
			$usr_array=array();
			if(count($userdata)>0)
			{ 
				foreach($users as $username)
				{

					$inr_array=array();
					foreach($userdata as $val)
					{
						if($val->email==$username)
						{
								$inr_array[]=$val;
						}
					}
					//dd($inr_array);

					array_push($usr_array, $inr_array);
					$inr_array='';

				}
			}
			$filtered_Arr=array_filter($usr_array);
			$path=base_path().'/reportpdf';
			$pdf = App::make('dompdf');
			$html=View::make('report.show')->with('data',$filtered_Arr)->with('sitename',$sitename);

			//return $html;
			//$pdf->loadHTML($html);
			$pdf=PDF::loadHTML($html)->setOrientation('landscape');

			// $pdf = PDF::setOptions(['isHtml5ParserEnabled' =>true])->loadView('report.show')->with('data',$filtered_Arr)->with('sitename',$sitename);

			// $dompdf->set_option('isHtml5ParserEnabled', true);
			$random_val=str_random(3);
			$dfile=$path.'/report_'.$random_val.'.pdf';
			$pdf->save($dfile);

			$dfile=Request::root()."/reportpdf/report_".$random_val.".pdf";
			return Redirect::back()->with('file',$dfile);
			
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
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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
