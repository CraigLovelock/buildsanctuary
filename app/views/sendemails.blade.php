<?php

// get each user and send an email
$query = DB::table('users')->where('email', 'craiglovelock54@hotmail.co.uk')->get();

foreach ($query as $user) {
	$data = array();
	Mail::send('emails.wereback', $data, function($message)
	{
	    $message->from('hello@buildsanctuary.com', 'BuildSanctuary');
	    $message->to($user->email);
	    $message->subject("We are back online!");
	});
}

?>