<?php
$data = $_POST;
$training_location = $data['loc'];

if ($training_location == 'parent') {
    $training_col = $data['col'];
    $training_user = $data['user'];

    $training_parent = "../Training_Collections";
    //Collection directory
    $training_collection_dir = $training_parent.'/'.$training_col;
    //User directory
    $training_user_dir = $training_collection_dir.'/'.$training_user;

    if (file_exists($training_collection_dir))
        $training_collection_dir;

    else
        mkdir($training_collection_dir, 07000);

    if (file_exists($training_user_dir))
        $training_user_dir;

    else
        mkdir($training_user_dir, 07000);

}

if ($training_location == 'children') {
    $training_user = $data['user'];
    $training_type = $data['type'];

    if ($training_type == 'newbie') {

        if (file_exists($training_user_dir))
            $training_user_dir = $training_cuser_dir;


        else
            mkdir($training_user_dir, 07000);

        $training_XML = $training_collection_dir.'/'.$training_user . "_newbie.xml";
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

    if ($training_type == 'inter') {
        $training_XML = $training_user.'/'.$training_user . "_inter.xml";
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
}






?>