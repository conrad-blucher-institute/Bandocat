<?php
include '../../Library/SessionManager.php';
require('../../Library/DBHelper.php');
$session = new SessionManager();

$username = $_GET["user"];
$collection = $_GET['col'];
$type = $_GET['type'];

/*print_r($_GET['user']);
if (isset($_GET["user"])) {
    $userfile = str_replace(".xml", "", $_GET["user"]);
    if ($userfile == "")
        $userfile = $username;
} else $userfile = $username;*/

include 'config.php';
include 'main.php';
include 'class.php';
include 'saveTrainingData.php';

if (!isset($_GET["id"])) {
    header('Location: list.php');
}

$doc_id = $_GET["id"];
	// $progress_id = $_GET["id"];
	// $userid = $_SESSION["user_id"];		
	// $input_id = getUserIDInput($id);

	//$doc_id = 2;
$userType = $username . '_' . $type;
$XMLfile = XMLfilename($userType);
$file = simplexml_load_file('../Training_Collections/' . $collection . '/'.$username.'/'. $XMLfile) or die("Cannot open file!");

foreach ($file->document as $a) {
    print_r($a->id);
    if ($a->id == $doc_id) {
        if ($a["collection"] == $collection) {
            $doc1 = new JobFolder($username, $doc_id);
            break;
        }
    }
}
$_SESSION['currentId'] = $doc_id;

	  

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>[Training] Job Folder</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script type="text/javascript" src="js/main.js"></script>

</head>
<body>
	<header>
		<div id="logo"><img id = "image" src = "../BlucherScanning/Logos/4.png" /></div>
		<div id="top-nav"><a href = "javascript:history.back()" id = "link">Go Back </a><a href = "../JobFolder" id = "link">Job Folder </a><a href = "../BlucherScanning/logoutFunctions.php" id = "link">Log Out</a></div>
	</header>	

	<div id="drag_classification" class="ui-widget-content">
	<span id="title_class_desc">Classification Description</span>
	<form id="class_form" name="class_form" method="post">
		 <select id="ddl_class_desc" name="ddl_class_desc">
		 <?php
		 	for($i = 0; $i < count($classification_arr);$i++)
		 	{
		 		echo "<option value='" . $classification_arr[$i] . "'>" . $classification_arr[$i] . "</option>";
		 	}
		 ?>
		 </select>
		 <p id="txt_class_desc"></p>
		</div>
	</form>	
	<div class = "navbar center">
	</div>

	<div id = "container">

	<div style="text-align:center">
		<h2> INPUT TRAINING SESSION </h2>
		<p id="field"> (*) required field <br><br> (Hover mouse on 'Needs Review' to know instruction) </p>    
		<button onclick="window.open=window.open('examples.php','Map Examples','width=1000,height=800,scrollbars=yes');">View Examples</button>
	</div>

	<form enctype="multipart/form-data" onsubmit = "return validateForm()" action = ""  method = "POST">
		
		

    	
		<table id="thetable">

		<tbody>
			<tr>
				<td><br></td>
				<td><br><span style = "color:red;"> * </span> Library Index: </td>
				<td><br> <input type = "text" name = "library" id = "library" size="32" value="<?php echo $doc1->libraryindex; ?>" /><span class = "errorInput" id = "librarySub"></span></td>
				<td width="10px"><br></td>
				<td style="padding-left:20px"> <br>Document Start Date: </td>

				<td><br><select name = "monthStart" id = "monthStart" style = "width:75px;"> <option >Month</option><?php month($doc1->startmonth); ?></select> 
					<select name = "dayStart" id = "dayStart" style = "width:60px;"> <option >Day</option><?php day($doc1->startday); ?></select>
					<select name = "yearStart" id = "yearStart" style = "width:70px;"> <option >Year</option><?php year($doc1->startyear); ?></select><span class = "errorInput" id = "docStartDateSub"></span></td>
			</tr>


			<tr>
				<td> <br></td>
				<td><br><span style = "color:red;"> * </span>Document Title: </td>
				<td> <br><input type = "text" name = "docTitle" id = "docTitle" size = "32" value="<?php echo $doc1->title; ?>" /><span class = "errorInput" id = "docTitleSub"></span></td>
				<td><br></td>
				<td style="padding-left:20px"> <br>Document End Date: </td>
				<td> <br><select name = "monthEnd" id = "monthEnd" style = "width:75px;"> <option >Month</option><?php month($doc1->endmonth); ?></select> 
						<select name = "dayEnd" id = "dayEnd" style = "width:60px;"> <option >Day</option><?php day($doc1->endday); ?></select>
						<select name = "yearEnd" id = "yearEnd" style = "width:70px;"> <option >Year</option><?php year($doc1->endyear); ?></select></select><span class = "errorInput" id = "docEndDateSub"></span></td>

			</tr>
			
			<tr>
				<td><br></td>
				<td class = "tooltip"><br>Needs Review: <span>The document needs to be reviewed for a second opinion, or for other issues.</span></td>

				<td><br><input type = "radio" name = "needReview" id = "needReview" value = 1 <?php echo ($doc1->needsreview == 1)?'checked':'' ?>/>Yes 
						 <input type = "radio" name = "needReview" id = "needReview" value = 0 <?php echo ($doc1->needsreview == 0)?'checked':'' ?>/>No</td>
				<td><br></td>
				<td style="padding-left:20px"><br>Document Author 1:</td>
	
				<td><br><input type="text" name="author1" id="author1" list="authorlist1" size = "26" value = "<?php echo $doc1->author1; ?>"/>
				</input></td>

			</tr>

			<tr>
				<td><br></td>
				<td class = "tooltip"><br> In A Subfolder: <span>This document belongs to a subfolder.</span></td>
				<td><br><input type = "radio" name = "inSubfolder" id = "inSubfolder" value = 1 <?php echo ($doc1->inasubfolder == 1)?'checked':'' ?>/>Yes 
						 <input type = "radio" name = "inSubfolder" id = "inSubfolder" value = 0 <?php echo ($doc1->inasubfolder == 0)?'checked':'' ?>/>		No</td>
				<td><br></td>
				
				<td style="padding-left:20px"><br>Document Author 2:</td>

				<td><br><input type="text" name="author2" id="author2" size = "26" list="authorlist2" value = "<?php echo $doc1->author2;?>"/>
				
			</tr>

			<tr>
				<td><br></td>
				<td><br> Subfolder Comments: </td>
				<td><br><textarea type = "text" name = "subfolder_comments" id = "subfolder_comments" cols="34" rows="2" /><?php echo $doc1->subfoldercomments; ?></textarea><span class = "errorInput" id = "subfolderSub"></span></td>
				<td><br></td>
				<td style="padding-left:20px"><br>Document Author 3:</td>
	
				<td><br><input type="text" name="author3" list="authorlist3" id="author3" size = "26" value = "<?php echo $doc1->author3;?>"/>
	

				<td><br></td>
			</tr>

			<tr>
				<td><br></td>
				<td><br>Classification:</td>

				<td> <br><select name = "ddlClassification" id = "ddlClassification"><?php classification($classification_arr,$doc1->classification); ?></select>					
				<span class = "errorInput" id = "ClassSub"></span></td>
				<td><br></td>
				<td rowspan="2" style="padding-left:20px"><br>Comments: </td>
				<td rowspan="2"><br><br><textarea name = "comment" rows = "5" cols = "28"/><?php echo $doc1->comments ?></textarea></td>
			</tr>

			<tr>
				<td><br></td>
				<td><br> Classification Comments: </td>
				<td><br><textarea type = "text" name = "class_comments" id = "class_comments" rows="3" cols="34" /><?php echo $doc1->classificationcomments;?></textarea><span class = "errorInput" id = "classcmtSub"></span></td>
			</tr>

			<tr>
				<td><br><br></td>

			</tr>

			<tr>
					<td></td>
					<td> <br>Scan of Front: <br><br><?php echo "<a href=\"download.php?file=$doc1->frontimage\">(Click to download)</a>"; ?></td>

					
					<?php echo "<td align = 'center' name = 'file1' > <a href=\"download.php?file=$doc1->frontimage\"><br><img src= $doc1->frontthumbnail  alt = error /></a></td>"; ?>	
					<td></td>

					<td style="padding-left:20px"> 
						<br>Scan of Back:
						<br><br>
							<?php 
								if(strcmp($doc1->backimage, "") != 0)
									echo "<a href=\"download.php?file=$doc1->backimage\">(Click to download)</a>"; 		
								else
								{}
							?>			
					</td>
					<?php
						if(strcmp($doc1->backthumbnail, "") != 0)
							echo "<td align = 'center' name = 'file2' ><br> <a href=\"download.php?file=$doc1->backimage\"><img src = $doc1->backthumbnail alt = error /></a></td>"; 
						else
							echo "<td align = 'center' name = 'file2' ><br> No file uploaded </td>";
					?>
			</tr>		
			
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td colspan="2"><br><input type = "submit" name = "submit" id="btnSubmit" value = "Submit" class="button button-blue"/>
				<input type = "button" hide id = "btnBack" value="Back" class ="button button-blue" onclick="window.history.back()"></td>
	

			</tr>
		</tbody>
		</table>
		 	 		
    </form> 
</div>
</body>


<?php


	if (isset($_POST['submit'])) 
	{
		$_SESSION[$userfile] = $doc_id;
	  	writeXMLtag($doc_id, "title", $_POST['docTitle'], $userfile);
	  	writeXMLtag($doc_id, "needsreview", $_POST['needReview'], $userfile);
	  	writeXMLtag($doc_id, "inasubfolder", $_POST['inSubfolder'], $userfile);
	  	writeXMLtag($doc_id, "author1", $_POST['author1'], $userfile);
	  	writeXMLtag($doc_id, "author2", $_POST['author2'], $userfile);
	  	writeXMLtag($doc_id, "author3", $_POST['author3'], $userfile);
	  	writeXMLtag($doc_id, "subfoldercomments", $_POST['subfolder_comments'], $userfile);
	  	writeXMLtag($doc_id, "classification", $_POST['ddlClassification'], $userfile);
	  	writeXMLtag($doc_id, "classificationcomments", $_POST['class_comments'], $userfile);
	  	writeXMLtag($doc_id, "comments", $_POST['comment'], $userfile);
	  	writeXMLtag($doc_id, "startmonth", $_POST['monthStart'], $userfile);
	  	writeXMLtag($doc_id, "startday", $_POST['dayStart'], $userfile);
	  	writeXMLtag($doc_id, "startyear", $_POST['yearStart'], $userfile);
	  	writeXMLtag($doc_id, "endmonth", $_POST['monthEnd'], $userfile);
	  	writeXMLtag($doc_id, "endday", $_POST['dayEnd'], $userfile);
	  	writeXMLtag($doc_id, "endyear", $_POST['yearEnd'], $userfile);
	  	echo "<script>window.location = 'mapEditingProcess.php'</script>";

	}
 
?>
	  

<script>
	  $(function() {
	    $( "#drag_classification" ).draggable();
	    $( "#drag_classification" ).resizable();
  	});
	  var ar_class = <?php echo json_encode($classification_arr); ?>;
	 var ar_class_desc = <?php echo json_encode($classification_desc); ?>;
	  $("#ddl_class_desc").change(function()
	  {
	  	var ddl_value = document.getElementById("ddl_class_desc").value;
	  	
	  	if (ddl_value == "none")
	  		document.getElementById("txt_class_desc").innerHTML = "";
	  	else
	  	{ 
	  		for (var i = 0; i < ar_class_desc.length; i++)
	  		{
	  			if(ddl_value == ar_class[i])
	  				document.getElementById("txt_class_desc").innerHTML = ar_class_desc[i];
	  		}
	  	}
	  });

</script>
</body>
</html>