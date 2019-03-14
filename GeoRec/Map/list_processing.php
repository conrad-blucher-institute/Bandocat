<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
if(isset($_GET['col']))
    $collection = htmlspecialchars($_GET['col']);
else header('Location: ../../');
require('../../Library/DBHelper.php');
$DB = new DBHelper();

// SQL server connection information
$sql_details = array(
    'user' => $DB->getUser(),
    'pass' => $DB->getPwd(),
    'db'   => $DB->SP_GET_COLLECTION_CONFIG($collection)['DbName'],
    'host' => $DB->getHost()
);

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'document';

// Table's primary key
$primaryKey = 'documentID';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => '`document`.`documentID`', 'dt' => 0, 'field' => 'documentID' ),
    array( 'db' => '`document`.`libraryindex`', 'dt' => 1,'field' => 'libraryindex'),
    array( 'db' => '`document`.`title`', 'dt' => 2,'field' => 'title' ),
    array( 'db' => '`document`.`enddate`', 'dt' => 3,'field' => 'enddate'),
    array( 'db' => '`document`.`filename`', 'dt' => 4,'field' => 'filename'),
    array( 'db' => '`document`.`filenameback`', 'dt' => 5,'field' => 'filenameback'),
    array( 'db' => '`document`.`rectifiability`', 'dt' => 6,'field' => 'rectifiability'),
    array( 'db' => '`document`.`georecFrontStatus`', 'dt' => 7,'field' => 'georecFrontStatus'),
    array( 'db' => '`document`.`georecBackStatus`', 'dt' => 8,'field' => 'georecBackStatus')

);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require('../../Library/sspwithjoin.class.php');

$joinQuery = " FROM `document` LEFT JOIN `customer` ON (`document`.`customerID` = `customer`.`customerID`)";
$extraWhere = " `document`.`needsreview` = 0";

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);