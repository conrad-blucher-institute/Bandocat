<?php
$data = $_POST;
$username = $data['user'];
$training = $data['type'];
$training_user = $username;

$training_path = "../../Training/Form".$username;
if (file_exists($training_path)) {
    $training_path = $training_path;
}

else
    mkdir($training_path, 07000);

if ($training == 'newbie') {
    $training_XML = $username.'/'.$username . "_newbie.xml";
    print_r($training_XML);
    if (file_exists($training_XML)) {
        $training_XML = $training_user;

    }
    else{
        $xml = new DOMDocument();
        $xml->save($training_XML);
        $myfile = fopen($training_XML, 'w') or die("Cannot create training log!");
        fclose($myfile);
        copy( "data.xml", $training_XML);
    }
}

if ($training == 'inter') {
    $training_XML = $username.'/'.$username . "_inter.xml";
    if (file_exists($training_XML)) {
        $training_XML = $training_user;
        print_r('file exists');
    }
    else{
        $xml = new DOMDocument();
        $xml->save($training_XML);
        $myfile = fopen($training_XML, 'w') or die("Cannot create training log!");
        fclose($myfile);
        copy( "data.xml", $training_XML);
    }
}

?>