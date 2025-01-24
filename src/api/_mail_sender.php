<?php

//use mail send api to send recover password email.  https://www.mailersend.com/
function SendRecoverPwd($email,$password){
    require_once("incKeys.php");

    $curl = curl_init();
    
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.mailersend.com/v1/email",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode([
        'from' => [
            'email' => 'mlccc_ccr_recoverpassword_noreplay@trial-ynrw7gy7xdkg2k8e.mlsender.net'
        ],
        'to' => [
            [
                    'email' => $email
            ]
        ],
        'subject' => 'Your Password of MLCCC Chinese Characters Recognition App',
 
        'html' => "<p>Thank you to use <a href='https://mlccc.herokuapp.com/CCR'> MLCCC Chinese Characters Recognition App </a> </p> </br> <p>Your password is <b>$password</b>.</p> </BR><BR>  - MLCCC CCR Support team"
      ]),
      CURLOPT_HTTPHEADER => [
        "authorization: Bearer $mailersend_api_token",
        "content-type: application/json",
        "x-requested-with: XMLHttpRequest"
      ],
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        error_log("Mail_Sender Error: " . $err);
        throw new Exception("Send email to $email failed");
     
    } else {
        error_log("Mail_Sender response: " . $response);
       return true;
    }
}

/**
 * 
  // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'MLCCC Password Recovery';
        
        // HTML message
        $htmlMessage = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h2 style='color: #2c3e50;'>Password Recovery</h2>
                <p>Hello,</p>
                <p>You requested to recover your password for the MLCCC system.</p>
                <p>Your password is: <strong style='background: #f8f9fa; padding: 3px 6px; border-radius: 3px;'>{$user['Password']}</strong></p>
                <p style='color: #e74c3c;'><strong>For security reasons, we recommend changing your password after logging in.</strong></p>
                <br>
                <p>Best regards,<br>MLCCC Team</p>
            </div>
        </body>
        </html>";
        
        // Plain text message (for non-HTML mail clients)
        $textMessage = "Hello,\n\n"
            . "You requested to recover your password for the MLCCC system.\n"
            . "Your password is: {$user['Password']}\n\n"
            . "For security reasons, we recommend changing your password after logging in.\n\n"
            . "Best regards,\nMLCCC Team";

        $mail->Body    = $htmlMessage;
        $mail->AltBody = $textMessage;

        $mail->send();
        error_log("Password recovery email sent successfully to: " . $email);
        
        $response = [
            'success' => true,
            'message' => 'Your password has been sent to your email address. Please check your inbox.'
        ];

    } catch (PHPMailerException $e) {
        error_log("PHPMailer error for {$email}: " . $e->getMessage());
        throw new Exception('There was an error sending the email. Please contact MLCCC admin for assistance.');
    }

    // // Send email with password
    // $to = $email;
    // $subject = "MLCCC Password Recovery";
    // $message = "Hello,\n\n";
    // $message .= "You requested to recover your password for the MLCCC system.\n";
    // $message .= "Your password is: " . $user['Password'] . "\n\n";
    // $message .= "For security reasons, we recommend changing your password after logging in.\n\n";
    // $message .= "Best regards,\nMLCCC Team";

    // $headers = "From: noreply@mlccc.org\r\n";
    // $headers .= "Reply-To: noreply@mlccc.org\r\n";
    // $headers .= "X-Mailer: PHP/" . phpversion();

    // // Capture any mail errors
    // if (!mail($to, $subject, $message, $headers)) {
    //     $mailError = error_get_last();
    //     error_log("Mail error: " . ($mailError ? json_encode($mailError) : "Unknown mail error"));
    //     throw new Exception('There was an error sending the email. Please contact MLCCC admin for assistance.');
    // }

    // $response = [
    //     'success' => true,
    //     'message' => 'Your password has been sent to your email address. Please check your inbox.'
    // ];

 */

?>