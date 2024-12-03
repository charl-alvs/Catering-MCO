<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require __DIR__ . '/../vendor/autoload.php';

function sendCode($email, $code) {
    error_log("Starting email send process to: " . $email . " with code: " . $code);
    
    try {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->Debugoutput = function($str, $level) {
            error_log("PHPMailer Debug: $str");
        };
        
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'catering038@gmail.com';                     //SMTP username
        $mail->Password   = 'mxgyaigjiqknfypr';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to

        error_log("SMTP settings configured");

        //Recipients
        $mail->setFrom('catering038@gmail.com', 'Catering Service');
        $mail->addAddress($email);     //Add a recipient
        
        error_log("Recipients configured");

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = "Verify Your Email";
        $mailContent = '<html>
                            <head>
                                <style>
                                    /* CSS styles for the email body */
                                    body {
                                        background-color: #f7f7f7;
                                        font-family: Arial, sans-serif;
                                        background-color: darkgreen;    
                                    }
                                    .container {
                                        background-color: #fff;
                                        border: 1px solid #ddd;
                                        border-radius: 5px;
                                        box-shadow: 0 0 5px #ddd;
                                        margin: 20px auto;
                                        max-width: 600px;
                                        padding: 20px;
                                    }
                                    img {
                                        display: block;
                                        margin: 0 auto;
                                        width: 100px;
                                    }
                                    h1 {
                                        color: #064e3b;
                                        font-size: 24px;
                                        margin-top: 0;
                                        text-align: center;
                                    }
                                    p {
                                        font-size: 16px;
                                        line-height: 1.5;
                                        margin: 20px 0;
                                    }
                                    .code {
                                        background-color: #f5f5f5;
                                        color: #064e3b;
                                        border: 1px solid #ddd;
                                        border-radius: 5px;
                                        font-size: 24px;
                                        font-weight: bold;
                                        margin: 20px auto;
                                        max-width: 200px;
                                        padding: 10px;
                                        text-align: center;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="container">
                                    <img src="https://i.ibb.co/XyVg3Rc/chef-platter.png" alt="Catering Service">
                                    <h1>Catering Service</h1>
                                    <p>Hi Users,</p>
                                    <p>Welcome to Catering Service!</p>
                                    <p>Thank you for ordering food.</p>
                                    <p style="text-align: center; color: #666;">Please use the following code to verify your email address:</p>
                                    <div class="code">' . $code . '</div>
                                    <p style="text-align: center; color: #666;">If you did not request this verification code, please ignore this email.</p>
                                    <p>Regards,</p>
                                    <p>The Catering Service Team</p>
                                </div>
                            </body>
                        </html>';
        $mail->Body = $mailContent;
        
        error_log("Email content prepared");
        
        // Send the email
        if($mail->send()) {
            error_log("Email sent successfully to: " . $email);
            return true;
        } else {
            error_log("Failed to send email. PHPMailer Error: " . $mail->ErrorInfo);
            return false;
        }
    } catch (Exception $e) {
        error_log("Mailer Exception: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        return false;
    }
}
