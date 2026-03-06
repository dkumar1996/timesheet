<?php

class LoginController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$response=array();
		$response['site']=DB::table('site')->where('enabled','Yes')->select('site_name','id')->get();
		return Response::json($response);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		echo phpinfo();
		return null;
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$response=array();
		$input = Input::json()->all();
		$rules=array(
			'username'		=>	'required',
			'password'	=>	'required');
		$validation = Validator::make($input,$rules);

		if ($validation->passes())
		{       
			$credentials = array('email'=>Input::json()->get('username')."@coastalmidwest.com",'password'=>Input::json()->get('password'));
		       
					if(Auth::attempt($credentials))
					{
						$response['user_id']=Auth::id();
						$response['allow']=true;
					
						return Response::json($response);
						
					}
					else
					{
						$response['allow']=false;
						$response['validation']=array(['username'=>'Invalid Login Details']);
						return Response::json($response);
					}


		}
		else
		{
				$response['allow']=false;
				$response['validation']=array(['username'=>strip_tags($validation->messages()->first('username')),'password'=>strip_tags($validation->messages()->first('password'))]);
					
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
		
	}

	
	
}
