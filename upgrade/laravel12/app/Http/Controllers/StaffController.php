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

class StaffController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		/*$data=DB::table('user')->leftJoin('site_staff_relation','site_staff_relation.staff_id','=','user.id')
		->where('user.enabled','Yes')->get();*/
		$data=DB::table('user')->where('user.enabled','Yes')->get();
	//	dd($data);
		return View::make('staff.index')
		->with('data',$data)
		->with('title','Staff Maintenance');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$site=DB::table('site')->pluck('site_name','id');
		return View::make('staff.create')->with('title','Staff Maintenance')
		->with('site',$site);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules=[
		'first_name'=>'required',
		'last_name'=>'required',
		'email'	=>'required|email|unique:user',
		'password'=>'required',
		'site'=>'required',
		'xeroId'=>'',
		'company'=>'required'

		];

		$input=request()->all();

		$roster_start=$input['roster_start'];
		$roster_end=$input['roster_end'];

		$Validator=Validator::make($input,$rules);
		if($Validator->passes())
		{
			Arr::forget($input,['_token','site','roster_end','roster_start']);

			$input['password']=Hash::make(request()->input('password'));

			// dd($input);

			$user=User::create($input);
			$site_arr=request()->input('site');

			$roster_datas=DB::table('roster')->insert(['user_id'=>$user->id,'roster_start_time'=>$roster_start,'roster_end_time'=>$roster_end]);


			if(count($site_arr)>0)
			{

				foreach($site_arr as $key=>$value)
				{

					$another=['site_id'=>$value,'staff_id'=>$user->id];

					StaffR::firstOrCreate($another);
				}

			}


			return Redirect::route('staff.index');

		}
		else
		{
			return Redirect::back()->withErrors($Validator)->WithInput();
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

		$data=User::find($id);
		$roster_time=DB::table('roster')->where('user_id',$id)->get();
		$site_id=DB::table('site_staff_relation')->where('staff_id',$id)->pluck('site_id');
		$site=DB::table('site')->pluck('site_name','id');
		return View::make('staff.edit')->with('title','Staff Maintenance')
		->with('user',$data)
		->with('site_id',$site_id)
		->with('roster_time',$roster_time)
		->with('site',$site);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$rules=[
		'first_name'=>'required',
		'last_name'=>'required',
		'email'	=>'required|email|unique:user,email,'.$id,
		'site' =>'required',
		'xeroId'=>'',
		'company'=>'required'

		];
		$input=request()->all();
		$roster_start=$input['roster_start'];
		$roster_end=$input['roster_end'];
		$Validator=Validator::make($input,$rules);

		if($Validator->passes())
		{


			Arr::forget($input,['_token','site','_method','password','change_password','roster_end','roster_start']);
			if(!request()->has('meal_allowance')) $input['meal_allowance']=0;
			if(request()->input('change_password',false))
			{
				$input['password']=Hash::make(request()->input('change_password'));
			}
			else
			{
				$input['password']=request()->input('password');
			}
			$data_Exists=DB::table('roster')->where('user_id',$id)->exists();

			if ($data_Exists) {
				$roster_datas=DB::table('roster')
											->where('user_id',$id)
											->update(['roster_start_time'=>$roster_start,'roster_end_time'=>$roster_end]);
			}
			else {
				$roster_datas=DB::table('roster')
											->insert(['user_id'=>$id,'roster_start_time'=>$roster_start,'roster_end_time'=>$roster_end]);
			}

			$user=User::where('id',$id)->update($input);

			$site_arr=request()->input('site');

			if(count($site_arr)>0)
			{
				DB::table('site_staff_relation')->where('staff_id',$id)->delete();

				foreach($site_arr as $key=>$value)
				{

					$another=['site_id'=>$value,'staff_id'=>$id];

					StaffR::firstOrCreate($another);
				}

			}


			return Redirect::route('staff.index');


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
		//$var="../Hello world!";
		//echo str_replace("../","",$var);
		User::where('id',$id)->update(['enabled'=>'No']);
		return Redirect::route('staff.index');
	}


}
