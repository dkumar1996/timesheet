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

class CompanyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{	$data=DB::table('company')->where('enabled',1)->get();
		return View::make('company.index')
		->with('data',$data)
		->with('title','Company Settings');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		
		return View::make('company.create')->with('title','Company Settings');
		
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules=[
		'company_name'=>'required|unique:company',
		//'address'=>'required',
		];
		$input=request()->all();
		$Validator=Validator::make($input,$rules);
		if($Validator->passes())
		{
			$input['enabled']=1;
			Arr::forget($input,['_token']);
		
			$user=DB::table('company')->insert($input);
			
			return Redirect::route('company.index');
			

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
		
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$data=DB::table('company')->find($id);
		
		return View::make('company.edit')->with('title','Company Settings')
		->with('company',$data);
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
		'company_name'=>'required|unique:company,company_name,'.$id,
		];
		$input=request()->all();
		$Validator=Validator::make($input,$rules);
		if($Validator->passes())
		{
			Arr::forget($input,['_token','_method']);
		
			$user=DB::table('company')->where('id',$id)->update($input);
			
			return Redirect::route('company.index');
			

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
		DB::table('company')->where('id',$id)->update(['enabled'=>0]);
		return Redirect::route('company.index');
		
	}

	







}
