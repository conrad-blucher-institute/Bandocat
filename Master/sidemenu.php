<nav>
    <div class="menu-item alpha">
        <h4><a href="../../">Main Menu</a></h4>
    </div>
    <!-- Add admin section to side menu -->
    <?php
    //create a new unique instance of DBheler so we can use it for tickets
    require_once '../../Library/DBHelper.php';
    $DB1 = new DBHelper();
    //if user is admin, then add Admin section to the menu
    if($session->isAdmin())
    {
        //queries the database for the number of tickets currently active
        $ticketCount = $DB1->GET_ADMIN_OPENTICKET_COUNT();
        echo '<div class="menu-item menu-item_sub4">
            <!--class for the visuals, data-badge to pass the number of tickets to the text in the badge -->
            <h4><a class="notificationBadge" data-badge='.$ticketCount.' id="adminNotificationBadge" href="">Admin</a></h4>    
             <div></div>
            <ul>           
            <li><a href="../../Forms/ActivityLog/index.php">Activity Log</a></li>
            <li><a href="../../Forms/Ticket/">View Tickets</a></li>
            <li><a href="../../Forms/ManageUser/">Manage User</a></li>
            <li><a href="../../Forms/NewUser/">Create New User</a></li>
            </ul>
        </div>
        <div class="menu-item menu-item_sub2">
        <h4><a href="#">TDL Publishing</a></h4>
        <ul>
            <li><a href="../../TDLPublish/Forms/index.php">Listing</a></li>
            <li><a href="../../TDLPublish/Forms/queue.php">Queue</a></li>
        </ul>
    </div>';
    }
    ?>
    <!-- Collections Tab -->
    <div class="menu-item menu-item_sub5">
        <h4><a href="#">Collections</a></h4>
        <ul>
            <li><a href="../../Templates/Map/index.php?col=bluchermaps">Blucher Maps</a></li>
            <li><a href="../../Templates/FieldBook/index.php?col=blucherfieldbook">Field Book</a></li>
            <li><a href="../../Templates/Map/index.php?col=greenmaps">Green Maps</a></li>
            <li><a href="../../Templates/Indices/index.php?col=mapindices">Indices</a></li>
            <li><a href="../../Templates/Folder/index.php?col=jobfolder">Job Folder</a></li>
        </ul>
    </div>
    <!-- Indices Transcription Tab -->
    <div class="menu-item">
        <h4><a href="../../Transcription/Indices/list.php?col=mapindices">Indices Transcription</a></h4>
    </div>
    <div class="menu-item menu-item_sub2">
        <h4><a href="#">GeoRectification</a></h4>
        <ul>
            <li><a href="../../GeoRec/Map/index.php?col=bluchermaps">Blucher Maps</a></li>
            <li><a href="../../GeoRec/Map/index.php?col=greenmaps">Green Maps</a></li>
        </ul>
    </div>
    <!-- Queries Tab -->
    <div class="menu-item menu-item_sub3">
        <h4><a href="#">Queries</a></h4>
        <ul>
            <li><a href="../../Forms/Queries/hascoast.php">Coastal Maps</a></li>
            <li><a href="../../Forms/Queries/exportcollection.php">Export Document Index</a></li>
            <li><a href="../../Forms/Queries/mapswithouttitle.php">Maps Without Titles</a></li>
            <li><a href="#">Supplied Title Procedure</a></li>
        </ul>
    </div>
    <!-- Statistics Tab -->
    <div class="menu-item">
        <h4><a href="../../Forms/Statistics/">Statistics</a></h4>
    </div>
    <!-- My Account Tab -->
    <div class="menu-item">
        <h4><a href="../../Forms/AccountSettings/">My Account</a></h4>
    </div>
    <!-- User Ticket Tab -->
    <div class="menu-item menu-item_sub2">
        <h4><a href="#">Ticket</a></h4>
        <ul>
            <li><a href="../../Forms/UserTicket/">View Tickets</a></li>
            <li><a href="../../Forms/TicketsSubmission/" target="_blank">Submit Ticket</a></li>
        </ul>
    </div>
    <!-- Help Tab -->
    <div class="menu-item menu-item_sub2">
        <h4><a href="#">Help</a></h4>
        <ul>
            <li><a href="../../Procedures/Documents">Procedures</a></li>
            <li><a href="../../Procedures/Utilities">Support Software</a></li>
        </ul>
    </div>
    <!-- Logout Tab -->
    <div class="menu-item">
        <h4><a href="../../Forms/Logout/" id="sidemenu_logout">Logout as <?php echo $session->getUsername(); ?></a></h4>
    </div>

    <script>

        $( document ).ready(function()
        {
            //grab ticketCount variable from above PHP function
            var count = '<?php echo $ticketCount; ?>';
            //if we have more than 0 tickets with the status of "open"
            if(count > 0)
            {
                document.getElementById("adminNotificationBadge").className = "notificationBadge";

            }else
            {
                document.getElementById("adminNotificationBadge").className = "";
            }

        });
    </script>
</nav>