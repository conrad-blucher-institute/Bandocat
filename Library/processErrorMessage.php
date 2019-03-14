<?php
/**
 * Created by PhpStorm.
 * User: hreeves
 * Date: 2/11/2019
 * Time: 2:37 PM
 */

require_once "ErrorReport.php";

$reporter = new ErrorReport();

if(isset($_POST["url"]) && isset($_POST["username"]) && isset($_POST["userid"]) && isset($_POST["error"]) && isset($_POST["errorMessage"]))
{
    $response = $reporter->reportError($_POST);

    // Getting mailer
    $mailer = $reporter->getMailer();
    $DB = $reporter->getDb();

    // Attempt to send email
    if(0)
    {
        $email = $DB->GET_USER_EMAIL($_POST["userid"]);

        $subject = "Error Report Received";

        $message = "<html>
<head>
<title>HTML email</title>
</head>
<body>
<p>This email contains HTML Tags!</p>
<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
</tr>
<tr>
<td>John</td>
<td>Doe</td>
</tr>
</table>
</body>
</html>";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <webmaster@bandocat.com>' . "\r\n";
        $headers .= 'Cc: myboss@example.com' . "\r\n";

        // Setting parameters
        $mailer->setHeaders($headers);
        $mailer->setHtml($message);
        $mailer->setTo($email);
        $mailer->setSubject($subject);

        $mailer->sendMail();
    }

    echo json_encode($response);
}

else
{
    echo "Incorrect parameters sent.";
    var_dump($_POST);
}