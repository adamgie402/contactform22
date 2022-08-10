<?php

$msg = "";
$msg_class = "";

    //check for submit
    if(filter_has_var(INPUT_POST, 'submit')) {

        // getting data from form and cleaning with htmlspecialchars()
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);

        // check if has all data
        if(!empty($name) && !empty($email) && !empty($message)) {
            //true
            $msg = "Pola wypełnione";
            $msg_class = "msg--confirmation";
            
            // EMAIL VALIDATION
            if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                //fail
                $msg = "Proszę poprawić email";
                $msg_class = "msg--warning";

            } else {
                //passed
                $msg = "Email OK";
                $msg_class = "msg--confirmation";
                
                // email variables
                $to_email = "@gmail.com";
                $server_email = "@dpoczta.pl";
                $subject = "Message from contact form";
                $body = "<h3>Message from contact form</h3>
                <h4>Nadawca:</h4>
                <p>" . $name . "</p>
                <h4>Email:</h4>
                <p>" . $email . "</p>
                <h4>Wiadomość:</h4>
                <p>" . $message . "</p>";
                // email headers
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-Type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: " . $name . "<" . $server_email . ">" . "\r\n";
                $headers .= "Reply-To: " . $email . "\r\n";

                //jeśli są wszystkie zmienne
                if(mail($to_email, $subject, $body, $headers)) {
                    //email sent
                    $msg = "Email wysłany!";
                    $msg_class = "msg--confirmation";

                } else {
                    //some error
                    $msg = "Błąd!!! nie wysłano wiadomości!!!";
                    $msg_class = "msg--warning";
                }

            }




        } else {
            //false
            $msg = "Proszę wypełnić wszystkie pola";
            $msg_class = "msg--warning";
        }

    };



?>

<!DOCTYPE html>
<html>
<head>
	<title>Contact Us</title>
	<!-- <link rel="stylesheet" href="https://bootswatch.com/cosmo/bootstrap.min.css"> -->
</head>
<body>
    
    <!-- ***** MESSAGE ***** -->
    <?php if($msg != ""): ?>
        <div class="message">
            <p class="<?php echo $msgClass ?>">
                <?php echo $msg ?>
            </p>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        
        <label>Name</label>
        <input type="text" name="name" placeholder="write name" value="<?php echo isset($_POST['name']) ? $name : ''; ?>"> <!-- value: utrzymanie wpisanej wartości (jeśli była podana) po przeładowaniu strony -->
        <br><br>
        <label>Email</label>
        <input type="text" name="email" placeholder="write email" value="<?php echo isset($_POST['email']) ? $email : ''; ?>">
        <br><br>
        <label>Message</label>
        <textarea type="text" name="message" placeholder="write message here"><?php echo isset($_POST['message']) ? $message : ''; ?></textarea>
        <br><br>
        <button type="submit" name="submit">Send</button>

    </form>


</body>
</html>