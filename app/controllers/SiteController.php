<?php

class SiteController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{	$data=Site::where('enabled','Yes')->get();
		return View::make('site.index')
		->with('data',$data)
		->with('title','Site Maintenance');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		
		return View::make('site.create')->with('title','Site Maintenance');
		
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules=[
		'site_name'=>'required|unique:site',
		'address'=>'required',
		];
		$input=Input::all();
		$Validator=Validator::make($input,$rules);
		if($Validator->passes())
		{
			array_forget($input,['_token']);
		
			$user=Site::create($input);
			
			return Redirect::route('site.index');
			

		}
		else
		{
			return Redirect::back()->withErrors($Validator)->WithInput();
		}
		/*if(Request::ajax())
		{
		
		$check=DB::table('site')->where('site_name',Input::get('name'))->get();
			if ($check) {

				return View::make('common')
	           ->with('exception','Already Data Exists');
			
			}
			else
			{

				$insert=Site::create(['site_name'=>Input::get('name')]);
				$insertedId = $insert->id;
        		return View::make('common')
	            ->with('id',$insertedId )
	            ->with('name',Input::get('name'));
	           
	        }
	    }*/
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		/*$data=DB::table('user')
		->leftJoin('site_staff_relation','site_staff_relation.staff_id','=','user.id')
		->where('site_staff_relation.site_id','<>',$id)
		->Where('user.usertype_id',2)->where('user.enabled','Yes') ->groupBy('site_staff_relation.staff_id')
		->get();*/

		//dd($data);
		$data=User::where('enabled','Yes')->get();
		return View::make('site.assign')
		->with('id',$id)
		->with('data',$data)->with('title','Site Maintenance');
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$data=Site::find($id);
		
		return View::make('site.edit')->with('title','Site Maintenance')
		->with('site',$data);
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
		'site_name'=>'required|unique:site,site_name,'.$id,
		'address'=>'required',
		];
		$input=Input::all();
		$Validator=Validator::make($input,$rules);
		if($Validator->passes())
		{
			array_forget($input,['_token','_method']);
		
			$user=Site::where('id',$id)->update($input);
			
			return Redirect::route('site.index');
			

		}
		else
		{
			return Redirect::back()->withErrors($Validator)->WithInput();
		}
		/*if(Request::ajax())
        {
		

			$check=DB::table('site')->where('site_name',Input::get('name'))->Where('id','<>',Input::get('id'))->get();
			if ($check) 
			{

				return View::make('common')
	           ->with('exception','Already Data Exists');
			
			}
			else
			{


				$id=Input::get('id');

				$input=['site_name'=>Input::get('name')];
		
				Site::where('id',$id)->update($input);
		
				
	            return View::make('common')
	            ->with('update_id',Input::get('id'))
	            ->with('update_name',Input::get('name'));
	            
            }
		}*/
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Site::where('id',$id)->update(['enabled'=>'No']);
		return Redirect::route('site.index');
		/*if(Request::ajax())
        {
        
			$id=Input::get('id');
			Site::where('id',$id)->update(['enabled'=>'No']);
			return 1;
		}
		else
		{
			return 0;
		}*/
	}

	public function assign_user()
	{
		
		$Validator=Validator::make( Input::all(),[ 'checkbox' => 'required|min:1' ]);

		if($Validator->passes())
		{
			StaffR::where('site_id',Input::get('id'))->delete();
			foreach(Input::get('checkbox') as $val)
			{
				
				StaffR::Create(['site_id'=>Input::get('id'),'staff_id'=>$val]);
			}
			return Redirect::route('site.index');
		}
		else
		{
			return Redirect::back()->withErrors($Validator)->withInput();
		}
	}







}
