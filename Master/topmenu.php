<?php
/**
 * Created by PhpStorm.
 * User: hreeves
 * Date: 11/30/2018
 * Time: 12:35 PM
 */?>
<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="../../Forms/Landing/">
        <!--<img src="../../Images/Logos/bando-logo-small.png">-->Bandocat
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="../../Forms/Main/">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Our Projects
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="../../Forms/Main/">Ed Rachel</a>
                    <a class="dropdown-item" href="#">USACE</a>
                    <a class="dropdown-item" href="../../../TonyAmos/Forms/TonyAmosCollection.php">Tony Amos</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../../../Bandocat/Forms/Logout/">Logout as <?php echo $session->getUsername(); ?></a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>
