<?php

class LoginController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$site=[''=>'Choose Site']+Site::where('enabled','Yes')->lists('site_name','id');
		return View::make('login.index')->with('site',$site)->with('title','Login');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
	
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		$input = Input::all();
		$validation = Validator::make($input, Admin::$rules);

		        if ($validation->passes())
		        {        $credentials1 = array('email'=>Input::get('email')."@coastalmidwest.com",'password'=>Input::get('password'));
		        	
		        	//$credentials1 = array('email'=>Input::get('email'),'password'=>Input::get('password'));
		        	//$credentials2 = array('email'=>Input::get('email'),'password'=>Input::get('password'),'usertype_id'=>2 );
			
					if(Auth::admin()->attempt($credentials1)) 
					{
						if(Auth::admin()->get()->first_name=="Super Admin")
						{
							 return Redirect::route('user.index');

						}
						else
						{
							 return Redirect::route('admin.index');
						}
						
					 
						
					}



					
					else
					{
						return Redirect::route('login.index')->with('message','Invalid Login Details');
					}
				}
		        else
		       	{
				  return Redirect::back()->withInput()->withErrors($validation);
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


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
			
	}

	public function logout()
	{
		Auth::user()->logout();
		Auth::admin()->logout();
		Auth::logout();

        // Redirect to homepage
        //return Redirect::to('login')
        return Redirect::route('login.index');
	}

	
	
}
