<?php
include_once "header.php";
include_once 'connect.php';
?>

<body style="padding-right: 0px;">

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">WebSiteName</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a <a href="profile.php?id=<?= $_SESSION['user_id'] ; ?>">Profili i perdoruesit</a></li>

            <li><a href="prona_info.php">Pronat</a></li>
            <li><a href="raport.php">Raport</a></li>

        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Log out</a></li>
        </ul>
    </div>
</nav>
</html>

</body>
