<?php

return array(

	'multi' => array(
        'admin' => array(
            'driver' => 'eloquent',
            'model' => 'Admin',
            'table'=>'admin'
        ),
        'user' => array(
            'driver' => 'database',
            'model'=>'User',
            'table' => 'user'
        )
    ),

	'reminder' => array(

		'email' => 'emails.auth.reminder',

		'table' => 'password_reminders',

		'expire' => 60,

	),

);
