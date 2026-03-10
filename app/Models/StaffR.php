<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffR extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'site_staff_relation';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public $timestamps = false;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');


	protected $fillable = array('site_id','staff_id');

	

}
