<?php

	$to = "send.to@example.com";
	$from = "feedback@example.com";

	$result = json_decode($_POST['data'],true);

	$message = $result[0]['Issue'];
	$img = $result[1];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$fileData = base64_decode($img);
	//saving
	//$fileName = dirname(__FILE__).'/photo.png';
	$fileName = dirname(__FILE__).'/image_' . date('Y-m-d-H-i-s') . '_' . uniqid() . '.png';
	file_put_contents($fileName, $fileData);

	require 'class.phpmailer.php';

	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	//Set who the message is to be sent from
	$mail->setFrom($from, 'Feedback');
	//Set who the message is to be sent to
	$mail->addAddress($to, 'Website Admin');
	//Set the subject line
	$mail->Subject = 'You have new feedback mail';
	$mail->Body    = '<b>Issue:</b><br>
						'.$message.'<br>';
	$mail->AltBody = 'Issue - '.$message;  
	//Attach an image file
	$mail->addAttachment($fileName);
	//send the message, check for errors

	if (!$mail->send()) {
	    echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	    echo "ok";
	    //deletes the file
	    unlink($fileName);
	}

?>