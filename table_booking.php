<?php

define('DB_NAME', 'matthe11_bombayMahalBooking');
define('DB_USER', 'matthe11_bmadmin');
define('DB_PASSWORD', 'Ciz=3594');
define('DB_HOST', 'localhost');

$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

if (!$link) {
	die('Could not connect: ' . mysql_error());
}

$db_selected = mysql_select_db(DB_NAME, $link);

if (!db_selected) {
	die('Can\'t us ' . DB_NAME . ': ' . mysql_error());
}

// echo "Connected successfully\n";


$customer_first_nameErr = $customer_last_nameErr = $email_addressErr = $phone_numberErr = $number_in_partyErr = $date_bookedErr = $time_bookedErr = "";
$customer_first_name = $customer_last_name = $email_address = $phone_number = $number_in_party = $date_booked = $time_booked = "";

// store form inputs in variables
// check to see if inputs are empty
// if not empty, validate input and store in variable
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST['customer_first_name'])) {
		$customer_first_nameErr = "First name is required";
		echo $customer_first_nameErr;
	} else {
		$customer_first_name = test_input($_POST['customer_first_name']);
	}

	if (empty($_POST['customer_last_name'])) {
		$customer_last_nameErr = "Last name is required";
		echo $customer_last_nameErr;
	} else {
		$customer_last_name = test_input($_POST['customer_last_name']);
	}

	if (empty($_POST['email_address'])) {
		$email_addressErr = "Email address is required";
		echo $email_addressErr;
	} else {
		$email_address = test_input($_POST['email_address']);
		if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
			$email_addressErr = "Invalid email format";
		}
	}
	
	if (empty($_POST['phone_number'])) {
		$phone_numberErr = "Phone number is required";
		echo $phone_numberErr;
	} else {
		$phone_number = test_input($_POST['phone_number']);
	}
	
	if (empty($_POST['number_in_party'])) {
		$number_in_partyErr = "Number in party is required";
		echo $number_in_partyErr;
	} else {
		$number_in_party = test_input($_POST['number_in_party']);
	}

	if (empty($_POST['date_booked'])) {
		$date_bookedErr = "Date is required";
		echo $date_bookedErr;
	} else {
		$date_booked = test_input($_POST['date_booked']);
	}

	if (empty($_POST['time_booked'])) {
		$time_bookedErr = "Time is required";
		echo $time_bookedErr;
	} else {
		$time_booked = test_input($_POST['time_booked']);
	}

	$sql_customer = "INSERT INTO customer (customer_first_name, customer_last_name, email_address, phone_number) VALUES ('$customer_first_name', '$customer_last_name', '$email_address', '$phone_number')";


	// run insert queries and test to make sure inserts were successful
	if (!mysql_query($sql_customer)) {
		die('Error: ' . mysql_error());
	}
	$last_index = mysql_insert_id();

	$sql_booking = "INSERT INTO booking (customer_id, number_in_party, date_booked, time_booked) VALUES ('$last_index','$number_in_party', '$date_booked', '$time_booked')";

	if (!mysql_query($sql_booking)) {
		die('Error: ' . mysql_error());
	}

	// compose and send email with reservation info

	require 'phpmailer/PHPMailerAutoload.php';

	$mail = new PHPMailer;

	// $mail->SMTPDebug = 3;                               // Enable verbose debug output

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'srv502.webhostingforstudents.com';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'user@matthewlyons.me';                 // SMTP username
	$mail->Password = 'Ciz=3594';                           // SMTP password
	$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 465;                                    // TCP port to connect to

	$mail->setFrom('from@example.com', 'Mailer');
	$mail->addAddress($email_address);     // Add a recipient
	// $mail->addAddress($email_address);               // Name is optional
	// $mail->addReplyTo('info@example.com', 'Information');
	// $mail->addCC($email_address);
	$mail->addBCC('matthewjosephlyons@gmail.com');

	// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = 'Bombay Mahal reservation for ' . date("m/d/Y", strtotime($date_booked));
	$mail->Body    = '<h3>Bombay Mahal reservation confirmation</h3>
					 <p>Name: '. $customer_first_name . ' ' . $customer_last_name . '</p>
					 <p>Email address: ' . $email_address . '</p>
					 <p>Phone number: ' . $phone_number . '</p>
					 <p>Number in party: ' . $number_in_party . '</p>
					 <p>Date booked: ' . date("m/d/Y", strtotime($date_booked)) . '</p>
					 <p>Time booked: ' . date("g:i a", strtotime($time_booked)) . '</p>';
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	if(!$mail->send()) {
	    echo 'Message could not be sent.';
	    echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
	    header('Location: contact-thank-you.html');
	    exit();
	}


	mysql_close();

}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>