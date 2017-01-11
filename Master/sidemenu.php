<nav>
    <div class="menu-item alpha">
        <h4><a href="../../">Main Menu</a></h4>
    </div>
    <?php
    //if user is admin, then add Admin section to the menu
    if($session->isAdmin())
        echo '<div class="menu-item menu-item_sub3"><h4><a href="">Admin</a></h4><ul>
            <li><a href="../../Forms/ActivityLog/index.php">Activity Log</a></li>
            <li><a href="../../Forms/Ticket/">View Tickets</a></li>
            <li><a href="../../Forms/NewUser/">Create New User</a></li></ul>
        </div>';
    ?>
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
    <div class="menu-item">
        <h4><a href="../../Transcription/Indices/list.php?col=mapindices">Indices Transcription</a></h4>
    </div>
    <div class="menu-item menu-item_sub3">
        <h4><a href="#">Queries</a></h4>
        <ul>
            <li><a href="../../Forms/Queries/hascoast.php">Coastal Maps</a></li>
            <li><a href="#">Export Document Index</a></li>
            <li><a href="../../Forms/Queries/mapswithouttitle.php">Maps Without Titles</a></li>
            <li><a href="#">Supplied Title Procedure</a></li>
        </ul>
    </div>
    <div class="menu-item">
        <h4><a href="../../Forms/Statistics/">Statistics</a></h4>
    </div>
    <div class="menu-item">
        <h4><a href="../../Forms/AccountSettings/">My Account</a></h4>
    </div>
    <div class="menu-item menu-item_sub3">
        <h4><a href="#">Help</a></h4>
        <ul>
            <li><a href="#">Procedures</a></li>
            <li><a href="../../Forms/TicketsSubmission/" target="_blank">Submit Ticket</a></li>
            <li><a href="#">Support Software</a></li>
        </ul>
    </div>
    <div class="menu-item">
        <h4><a href="../../../" id="sidemenu_logout">BandoCat 1.0</a></h4>
    </div>
    <div class="menu-item">
        <h4><a href="../../Forms/Logout/" id="sidemenu_logout">Logout as <?php echo $session->getUsername(); ?></a></h4>
    </div>
</nav>
</nav>