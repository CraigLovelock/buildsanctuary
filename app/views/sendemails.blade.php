<?php

// get each user and send an email

/*$query = DB::table('users')->get();

foreach ($query as $user) {
	echo $user->username;
}*/

$data = array();

Mail::send('emails.wereback', $data, function($message)
{
    $message->from('hello@buildsanctuary.com', 'BuildSanctuary');
    $message->to('craiglovelock54@hotmail.co.uk');
});

?>