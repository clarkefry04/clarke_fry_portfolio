<?php

if($_POST) {

   $name = trim(stripslashes($_POST['contactName']));
   $email = trim(stripslashes($_POST['contactEmail']));
   $subject = trim(stripslashes($_POST['contactSubject']));
   $contact_message = trim(stripslashes($_POST['contactMessage']));

   // Check Name
	if (strlen($name) < 2) {
		$error['name'] = "Please enter your name.";
	}
	// Check Email
	if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
		$error['email'] = "Please enter a valid email address.";
	}
	// Check Message
	if (strlen($contact_message) < 15) {
		$error['message'] = "Please enter your message. It should have at least 15 characters.";
	}
   // Subject
	if ($subject == '') { $subject = "Contact Form Submission"; }


   // Set Message
   $message .= "Email from: " . $name . "<br />";
   $message .= "Email address: " . $email . "<br />";
   $message .= "Message: <br />";
   $message .= $contact_message;
   $message .= "<br /> ----- <br /> This email was sent from your site's contact form. <br />";

   // Set From: header
   $from =  $name . " <" . $email . ">";

	// Email Headers
	$headers = "From: " . $from . "\r\n";
	$headers .= "Reply-To: ". $email . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	//if theres no error
   if (!$error) {
		
		//send the email
		 $url = 'https://api.sendgrid.com/';
		 $user = '<this is your sendgrid username>';
		 $pass = '<this is your sendgrid password>';

		 $params = array(
			  'api_user' => $user,
			  'api_key' => $pass,
			  'to' => 'clarkefry@gmail.com',
			  'subject' => $subject,
			  'html' => $message,
			  'from' => $email,
		   );

		 $request = $url.'api/mail.send.json';

		 // Generate curl request
		 $session = curl_init($request);

		 // Tell curl to use HTTP POST
		 curl_setopt ($session, CURLOPT_POST, true);

		 // Tell curl that this is the body of the POST
		 curl_setopt ($session, CURLOPT_POSTFIELDS, $params);

		 // Tell curl not to return headers, but do return the response
		 curl_setopt($session, CURLOPT_HEADER, false);
		 curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

		 // obtain response
		 $response = curl_exec($session);
		 curl_close($session);

		if (strpos($response, 'success') !== false)
		{ 
			echo "Success! Thank you for contacting me"; 
		}
		else 
		{ 
			echo "Something went wrong. Please try again."; 
		}
		
	} # end if - no validation error

	else {

		$response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
		$response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
		$response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;
		
		echo $response;

	} # end if - there was a validation error

}

?>