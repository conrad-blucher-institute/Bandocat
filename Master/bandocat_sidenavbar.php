<?php
/**
 * Created by PhpStorm.
 * User: hreeves
 * Date: 12/12/2018
 * Time: 2:04 PM
 */?>
<nav class="navbar navbar-light bg-light sticky-top" style="top:84px;">
    <ul class="navbar-nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="../../Forms/Main/main.php">Main Menu</a>
        </li>
        <!-- Admin Tabs -->
        <?php
        //create a new unique instance of DBheler so we can use it for tickets
        require_once '../../Library/DBHelper.php';
        $DB1 = new DBHelper();
        //if user is admin, then add Admin section to the menu
        $userid = $session-> getUserID();
        $userticketCount = $DB1->GET_USER_CLOSEDTICKET_COUNT($userid);
        $username = $_SESSION['username'];
        $ticketCount = 0;
        $admin = $session->isAdmin();
        if($session->isAdmin())
        {
            //queries the database for the number of tickets currently active
            $ticketCount = $DB1->GET_ADMIN_OPENTICKET_COUNT();
            echo '<li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Admin <span class="badge badge-danger">'.$ticketCount.'</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a href="../../Forms/ActivityLog/" class="dropdown-item">Activity Log</a>
                <a href="../../Forms/Ticket/" class="dropdown-item">
                    View Tickets <span class="badge badge-danger">'.$ticketCount.'</span>
                </a>
                <a href="../../Forms/ManageUser/" class="dropdown-item">Manage User</a>
                <a href="../../Forms/NewUser/" class="dropdown-item">Create New User</a>
                <a href="../../Training/admin/admin.php" class="dropdown-item">Training</a>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                TDL Publishing
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a href="../../TDLPublish/Forms/index.php" class="dropdown-item">Listing</a>
                <a href="../../TDLPublish/Forms/queue.php" class="dropdown-item">Publishing Queue</a>
                <a href="../../TDLPublish/Forms/updatequeue.php" class="dropdown-item">Update Queue</a>
            </div>
        </li>';

        }
        ?>
        <!-- Training Tab -->
        <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Training
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a href="../../Training/jobfolder/Forms/list.php?col=jobfolder&action=training&type=none" class="dropdown-item">Job Folder Training</a>
                <a href="../../Training/maps/Forms/list.php?col=maps&action=training&type=none" class="dropdown-item">Maps Training</a>
                <a href="../../Training/fieldbook/Forms/list.php?col=fieldbook&action=training&type=none" class="dropdown-item">Field Book Training</a>
            </div>
        </div>
        <!-- Indices Transcription Tab -->
        <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Indices Transcription
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a href="../../Transcription/Indices/list.php?col=mapindices" class="dropdown-item">Map Indices</a>
                <a href="../../Transcription/FieldBookIndices/list.php?col=fieldbookindices" class="dropdown-item">FieldBook Indices</a>
            </div>
        </div>
        <!-- Geo Rectification Tab -->
        <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                GeoRectification
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a href="../../GeoRec/Map/index.php?col=bluchermaps" class="dropdown-item">Blucher Maps</a>
                <a href="../../GeoRec/Map/index.php?col=greenmaps" class="dropdown-item">Green Maps</a>
                <a href="../../GeoRec/Map/index.php?col=pennyfenner" class="dropdown-item">Pennyfenner Maps</a>
            </div>
        </div>
        <!-- Queries Tab -->
        <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Queries
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a href="../../Forms/Queries/hascoast.php" class="dropdown-item">Coastal Maps</a>
                <a href="../../Forms/Queries/exportcollection.php" class="dropdown-item">Export Document Index</a>
                <a href="../../Forms/Queries/mapswithouttitle.php" class="dropdown-item">Maps Without Titles</a>
                <a href="../../Forms/Queries/manage_authorname.php" class="dropdown-item">Manage TDL Author</a>
                <?php if($session->isAdmin()){echo '<a href="../../Forms/Queries/convert_and_compress.php" class="dropdown-item">PDF System</a> '; } ?>
                <a href="#" class="dropdown-item">Supplied Title Procedure</a>
            </div>
        </div>
        <!-- Tickets and Create New Collection -->
        <?php
        echo '<div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Ticket
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a href="../../Forms/UserTicket/" class="dropdown-item">
                    View Tickets <span class="badge badge-danger">'.$userticketCount.'</span>
                </a> 
            <a href="../../Forms/TicketsSubmission/" target="_blank" class="dropdown-item">Submit Ticket</a>
</div>
    </div>';


        if($session->isSuperAdmin())
        {
            echo '<div class="nav-item">
        <a href="../../Creator/"  class="nav-link">Create New Collection</a>
    </div>';
        }
        ?>
        <!-- Help Tab -->
        <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Help
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a href="../../Procedures/Documents" class="dropdown-item">Procedures</a>
                <a href="../../Procedures/Utilities" class="dropdown-item">Support Software</a>
            </div>
        </div>
    </ul>
</nav>
