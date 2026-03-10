<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'admin';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//public $timestamps = false;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');


	protected $fillable = array(
	'email','password','first_name','last_name','usertype_id','timecard_no','signature');

	public static $rules = array('email','password');


}
