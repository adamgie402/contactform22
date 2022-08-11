<!DOCTYPE html>
<html>
<head>
	<title>Contact Form</title>
	<!-- <link rel="stylesheet" href="https://bootswatch.com/cosmo/bootstrap.min.css"> -->
    <style>
        .msg--confirmation { color: green; }
        .msg--warning { color: red; }
    </style>
</head>
<body>

<?php
$msg = "";
$msg_class = "";

    //sprawdzam czy jest submit (jeśli nie, wyświetla pusty formularz - html poniżej if)
    if(filter_has_var(INPUT_POST, 'submit')) {

        // getting data from form and cleaning with htmlspecialchars()
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $phone = htmlspecialchars($_POST['phone']);
        $message = htmlspecialchars($_POST['message']);

        //phone sanitization
        if($phone) {
            $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT); // zostawia tylko cyfry oraz znaki +-

            //jeśli pierwszy znak to plus, zamień na 00
            if(substr($phone,0,1) == "+") {
                $phone = "00" . substr($phone,1); 
            } 
            $phone = str_replace('-','',$phone);
            $phone = str_replace('+','',$phone);
        };

        // check if has all data (no phone verification, because it is optional)
        if(!empty($name) && !empty($email) && !empty($message)) {
            //true
            $msg_class = "msg--confirmation";
            $msg = "Dane poprawne...";
            
            // EMAIL and PHONE VALIDATION
            if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                //fail
                $msg_class = "msg--warning";
                $msg = "Proszę poprawić email";

            } elseif ($phone && strlen($phone) < 9 ) { //jeśli istnieje $phone i jest krótrszy niż 9 znaków
                //fail
                $msg_class = "msg--warning";
                $msg = "Proszę sprawdzić nr telefonu";

            }  else {
                //passed - prepare email and send it!
                $msg_class = "msg--confirmation";
                $msg = "Dane poprawne...";

                if(!$phone) { $phone = "---";};
                
                // email variables
                $to_email = "@gmail.com";
                $server_email = "@dpoczta.pl";
                $website = "https://www.websiteaddress.com";
                $subject = "Wiadomość ze strony internetowej";
                $body = "<h3>Contact form message</h3>
                <h4>" . $website . "</h4>
                <h4>Nadawca:</h4>
                <p>" . $name . "</p>
                <h4>Email:</h4>
                <p>" . $email . "</p>
                <h4>Telefon: </h4>
                <p>" . $phone . "</p>
                <h4>Wiadomość:</h4>
                <p>" . $message . "</p>";
                
                // email headers
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-Type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: " . $name . "<" . $server_email . ">" . "\r\n";
                $headers .= "Reply-To: " . $name . "<" . $email . ">" . "\r\n";

                //jeśli funkcja mail zostanie pomyślnie wykonana
                if(mail($to_email, $subject, $body, $headers)) {
                    //email sent
                    $msg = "Email wysłany!";
                    $msg_class = "msg--confirmation";
                } else {
                    //some error
                    $msg_class = "msg--warning";
                    $msg = "Jest jakiś problem. Wiadomość nie została wysłana.";
                }

            }
        } else {
            //false
            $msg_class = "msg--warning";
            $msg = "Proszę wypełnić wszystkie pola obowiązkowe *";
        }
    };
?>

    


<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    
    <label>Name*</label><br>
    <input type="text" name="name" placeholder="write name" value="<?php echo isset($_POST['name']) ? $name : ''; ?>"> <!-- value: skrócona wersja if-else; utrzymanie wpisanej wartości (jeśli była podana) po przeładowaniu strony -->
    <br><br>
    <label>Email*</label><br>
    <input type="text" name="email" placeholder="write email" value="<?php echo isset($_POST['email']) ? $email : ''; ?>">
    <br><br>
    <label>Phone</label><br>
    <input type="text" name="phone" placeholder="write phone (optional)" value="<?php echo isset($_POST['phone']) ? $phone : ''; ?>">
    <br><br>
    <label>Message*</label><br>
    <textarea type="text" name="message" placeholder="write message here"><?php echo isset($_POST['message']) ? $message : ''; ?></textarea>
    <br><br>
    <button type="submit" name="submit">Send</button>

    <!-- ***** MESSAGE ***** -->
    <?php if($msg != ""): ?>
        <div class="message">
            <p class="<?php echo $msg_class ?>">
                <strong><?php echo $msg ?></strong>
            </p>
        </div>
    <?php endif; ?>

</form>


</body>
</html>