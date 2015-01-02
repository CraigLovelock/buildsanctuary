<?php

// get each user and send an email
$query = DB::table('users')->where('email', 'craiglovelock54@hotmail.co.uk')->get();

foreach ($query as $user) {
	$email = $user->email;
	$data = array();
	Mail::send('emails.wereback', $data, function($message) use ($email)
	{
	    $message->from('hello@buildsanctuary.com', 'BuildSanctuary');
	    $message->to($email);
	    $message->subject("We are back online!");
	});
}

?>