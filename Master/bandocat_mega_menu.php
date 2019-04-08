<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top" id="megaMenu">
    <a class="navbar-brand" href="../../Forms/Landing/">Bandocat</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <!-- Main Menu -->
            <li class="nav-item active">
                <a class="nav-link" href="../../Forms/Main/main.php">Main Menu <span class="sr-only">(current)</span></a>
            </li>
            <!-- Landing Page -->
            <li class="nav-item">
                <a class="nav-link" href="../../Forms/Landing/">Other Projects</a>
            </li>
            <!-- Statistics -->
            <li class="nav-item">
                <a class="nav-link" href="../../Forms/Statistics/">Statistics</a>
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
                <a href="../../Forms/ManageDatabase/" class="dropdown-item">Database Manager</a>';
                    if($session->isSuperAdmin())
                    {
                        echo '<a href="../../Creator/"  class="dropdown-item">Create New Collection</a>';
                    }
                    echo '
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
            <!-- Mega Menu -->
            <li class="nav-item dropdown position-static">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Menu
                </a>
                <div class="dropdown-menu w-100" aria-labelledby="navbarDropdown">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Training -->
                            <div class="col">
                                <h5>Training</h5>
                                <div class="d-flex flex-column">
                                    <a href="../../Training/jobfolder/Forms/list.php?col=jobfolder&action=training&type=none" class="dropdown-item text-dark p-1">Job Folder Training</a>
                                    <a href="../../Training/maps/Forms/list.php?col=maps&action=training&type=none" class="dropdown-item text-dark p-1">Maps Training</a>
                                    <a href="../../Training/fieldbook/Forms/list.php?col=fieldbook&action=training&type=none" class="dropdown-item text-dark p-1">Field Book Training</a>
                                </div>
                            </div>
                            <!-- Indices Transition -->
                            <div class="col">
                                <h5>Indices Transition</h5>
                                <div class="d-flex flex-column">
                                    <a href="../../Transcription/Indices/list.php?col=mapindices" class="dropdown-item text-dark p-1">Map Indices</a>
                                    <a href="../../Transcription/FieldBookIndices/list.php?col=fieldbookindices" class="dropdown-item text-dark p-1">FieldBook Indices</a>
                                </div>
                            </div>
                            <!-- Geo Rectification -->
                            <div class="col">
                                <h5>GeoRectification</h5>
                                <div class="d-flex flex-column">
                                    <a href="../../GeoRec/Map/index.php?col=bluchermaps" class="dropdown-item text-dark p-1">Blucher Maps</a>
                                    <a href="../../GeoRec/Map/index.php?col=greenmaps" class="dropdown-item text-dark p-1">Green Maps</a>
                                    <a href="../../GeoRec/Map/index.php?col=pennyfenner" class="dropdown-item text-dark p-1">Pennyfenner Maps</a>
                                </div>
                            </div>
                            <!-- Queries -->
                            <div class="col">
                                <h5>Queries</h5>
                                <div class="d-flex flex-column">
                                    <a href="../../Forms/Queries/hascoast.php" class="dropdown-item text-dark p-1">Coastal Maps</a>
                                    <a href="../../Forms/Queries/exportcollection.php" class="dropdown-item text-dark p-1">Export Document Index</a>
                                    <a href="../../Forms/Queries/mapswithouttitle.php" class="dropdown-item text-dark p-1">Maps Without Titles</a>
                                    <a href="../../Forms/Queries/manage_authorname.php" class="dropdown-item text-dark p-1">Manage TDL Author</a>
                                    <?php if($session->isAdmin()){echo '<a href="../../Forms/Queries/convert_and_compress.php" class="dropdown-item text-dark p-1">PDF System</a> '; } ?>
                                    <a href="#" class="dropdown-item text-dark p-1">Supplied Title Procedure</a>
                                </div>
                            </div>

                            <!-- Tickets and Create New Collection -->
                            <?php
                            echo '<div class="col">
                                <h5>Tickets</h5>
                                <div class="d-flex flex-column">
                                    <a href="../../Forms/UserTicket/" class="dropdown-item text-dark p-1">
                                        View Tickets <span class="badge badge-danger">'.$userticketCount.'</span></a>
                                    <a href="../../Forms/TicketsSubmission/" target="_blank" class="dropdown-item text-dark p-1">Submit Ticket</a>
                                </div>
                            </div>'; ?>
                            <!-- Help -->
                            <div class="col">
                                <h5>Help</h5>
                                <div class="d-flex flex-column">
                                    <a href="../../Procedures/Documents" class="dropdown-item text-dark p-1">Procedures</a>
                                    <a href="../../Procedures/Utilities" class="dropdown-item text-dark p-1">Support Software</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <!-- Collections Tab -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Operations
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <!--<a class="dropdown-item" href="../../Templates/Map/index.php?col=bluchermaps">Blucher Maps</a>
                    <a class="dropdown-item" href="../../Templates/FieldBook/index.php?col=blucherfieldbook">Field Book</a>
                    <a class="dropdown-item" href="../../Templates/Map/index.php?col=greenmaps">Green Maps</a>
                    <a class="dropdown-item" href="../../Templates/Indices/index.php?col=mapindices">Indices</a>
                    <a class="dropdown-item" href="../../Templates/FieldBookIndices/index.php?col=fieldbookindices">Field Book Indices</a>
                    <a class="dropdown-item" href="../../Templates/Folder/index.php?col=jobfolder">Job Folder</a>
                    <a class="dropdown-item" href="../../Templates/Map/index.php?col=pennyfenner">Pennyfenner Maps</a>-->
                    <a class="dropdown-item" href="../../Templates/Menu/index.php?option=Catalog">Catalog</a>
                    <a class="dropdown-item" href="../../Templates/Menu/index.php?option=Edit/View">Edit/View</a>
                    <a class="dropdown-item" href="../../Templates/Menu/index.php?option=Rectify">Rectify</a>
                    <a class="dropdown-item" href="../../Templates/Menu/index.php?option=Upload">Upload</a>
                    <a class="dropdown-item" href="../../Templates/Menu/index.php?option=Transcribe">Transcribe</a>
                </div>
            </li>
            <!-- Mega Menu for Collections -->
            <!--<li class="nav-item dropdown position-static">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Collections
                </a>
                <div class="dropdown-menu w-100" aria-labelledby="navbarDropdown">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <h5>Blucher Maps</h5>
                                <div class="d-flex flex-column">
                                    <a class="nav-link text-dark p-1" href="../../Templates/Map/catalog.php?col=bluchermaps">Catalog</a>
                                    <a class="nav-link text-dark p-1" href="../../Templates/Map/list.php?col=bluchermaps">Edit/View</a>
                                    <a class="nav-link text-dark p-1" href="../../GeoRec/Map/index.php?col=bluchermaps">Rectify</a>
                                </div>
                            </div>
                            <div class="col">
                                <h5>Field Book</h5>
                                <div class="d-flex flex-column">
                                    <a class="nav-link text-dark p-1" href="../../Templates/FieldBook/upload.php?col=blucherfieldbook">Upload</a>
                                    <a class="nav-link text-dark p-1" href="../../Templates/FieldBook/list.php?col=blucherfieldbook&action=catalog">Catalog</a>
                                    <a class="nav-link text-dark p-1" href="../../Templates/FieldBook/list.php?col=blucherfieldbook&action=review">Edit/View</a>
                                </div>
                                <div class="dropdown-divider"></div>
                                <h5>Field Book Indices</h5>
                                <div class="d-flex flex-column">
                                    <a class="nav-link text-dark p-1" href="../../Templates/FieldBookIndices/catalog.php?col=fieldbookindices">Catalog</a>
                                    <a class="nav-link text-dark p-1" href="../../Templates/FieldBookIndices/list.php?col=fieldbookindices">Edit/View</a>
                                    <a class="nav-link text-dark p-1" href="../../Transcription/Indices/list.php?col=fieldbookindices">Transcribe</a>
                                </div>
                            </div>
                            <div class="col">
                                <h5>Green Maps</h5>
                                <div class="d-flex flex-column">
                                    <a class="nav-link text-dark p-1" href="../../Templates/Map/catalog.php?col=greenmaps">Catalog</a>
                                    <a class="nav-link text-dark p-1" href="../../Templates/Map/list.php?col=greenmaps">Edit/View</a>
                                    <a class="nav-link text-dark p-1" href="../../GeoRec/Map/index.php?col=greenmaps">Rectify</a>
                                </div>
                            </div>
                            <div class="col">
                                <h5>Map Indices</h5>
                                <div class="d-flex flex-column">
                                    <a class="nav-link text-dark p-1" href="../../Templates/Indices/catalog.php?col=mapindicies">Catalog</a>
                                    <a class="nav-link text-dark p-1" href="../../Templates/Indices/list.php?col=mapindices">Edit/View</a>
                                    <a class="nav-link text-dark p-1" href="../../Transcription/Indices/list.php?col=mapindicies">Transcribe</a>
                                </div>
                            </div>
                            <div class="col">
                                <h5>Job Folder</h5>
                                <div class="d-flex flex-column">
                                    <a class="nav-link text-dark p-1" href="../../Templates/Map/catalog.php?col=bluchermaps">Catalog</a>
                                    <a class="nav-link text-dark p-1" href="../../Templates/Map/list.php?col=bluchermaps">Edit/View</a>
                                    <a class="nav-link text-dark p-1" href="../../GeoRec/Map/index.php?col=bluchermaps">Rectify</a>
                                </div>
                            </div>
                            <div class="col">
                                <h5>Penny Fenner</h5>
                                <div class="d-flex flex-column">
                                    <a class="nav-link text-dark p-1" href="../../Templates/Map/catalog.php?col=pennyfenner">Catalog</a>
                                    <a class="nav-link text-dark p-1" href="../../Templates/Map/list.php?col=pennyfenner">Edit/View</a>
                                    <a class="nav-link text-dark p-1" href="../../GeoRec/Map/index.php?col=pennyfenner">Rectify</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>-->
            <!-- Account Settings -->
            <li>
                <a class="nav-link" href="../../Forms/AccountSettings/">Account Settings</a>
            </li>
            <!-- Logout -->
            <li class="nav-item">
                <a class="nav-link" href="../../../Bandocat/Forms/Logout/">Logout as <?php echo $session->getUsername(); ?></a>
            </li>
        </ul>
    </div>
</nav>