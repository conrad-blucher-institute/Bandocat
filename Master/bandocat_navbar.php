<?php
/**
 * Created by PhpStorm.
 * User: hreeves
 * Date: 12/6/2018
 * Time: 3:40 PM
 */
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <a class="navbar-brand" href="../../Forms/Landing/">Bandocat</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
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
            <!-- Collections Tab -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <!--<i class="far fa-map"> Collections</i>-->
                    Collections
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="../../Templates/Map/index.php?col=bluchermaps">Blucher Maps</a>
                    <a class="dropdown-item" href="../../Templates/FieldBook/index.php?col=blucherfieldbook">Field Book</a>
                    <a class="dropdown-item" href="../../Templates/Map/index.php?col=greenmaps">Green Maps</a>
                    <a class="dropdown-item" href="../../Templates/Indices/index.php?col=mapindices">Indices</a>
                    <a class="dropdown-item" href="../../Templates/FieldBookIndices/index.php?col=fieldbookindices">Field Book Indices</a>
                    <a class="dropdown-item" href="../../Templates/Folder/index.php?col=jobfolder">Job Folder</a>
                    <a class="dropdown-item" href="../../Templates/Map/index.php?col=pennyfenner">Pennyfenner Maps</a>
                </div>
            </li>
            <!-- Account Settings -->
            <li>
                <a class="nav-link" href="../../Forms/AccountSettings/">Account Settings</a>
            </li>
            <!-- Logout -->
            <li class="nav-item">
                <a class="nav-link" href="../../../Bandocat/Forms/Logout/">Logout as <?php echo $session->getUsername(); ?></a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>