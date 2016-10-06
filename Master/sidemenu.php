<nav>
    <div class="menu-item alpha">
        <h4><a href="../../">Main Menu</a></h4>
    </div>
    <?php
        //if user is admin, then add Admin section to the menu
    echo '<div class="menu-item"><h4><a href="../../Form/Admin/">Admin</a></h4></div>';
    ?>
    <div class="menu-item menu-item_sub5">
        <h4><a href="#">Collections</a></h4>
        <ul>
            <li><a href="../../Templates/Map/index.php?col=bluchermaps">Blucher Maps</a></li>
            <li><a href="../../Templates/FieldBook/index.php?col=fieldbook">Field Book</a></li>
            <li><a href="../../Templates/Map/index.php?col=greenmaps">Green Maps</a></li>
            <li><a href="../../Templates/Indices/index.php?col=indices">Indices</a></li>
            <li><a href="../../Templates/Folder/index.php?col=jobfolder">Job Folder</a></li>
        </ul>
    </div>
    <div class="menu-item menu-item_sub3">
        <h4><a href="#">Queries</a></h4>
        <ul>
            <li><a href="#">Export Document Index</a></li>
            <li><a href="#">Maps Without Titles</a></li>
            <li><a href="#">Supplied Title Procedure</a></li>
        </ul>
    </div>
    <div class="menu-item">
        <h4><a href="#">Statistics</a></h4>
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
        <h4><a href="../../Forms/Logout/" id="sidemenu_logout">Logout as username</a></h4>
    </div>
</nav>