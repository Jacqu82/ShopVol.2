<?php

$admin = loggedAdmin($connection);

?>
<div class="container">
    <div class="navbar-header">
        <div class="container text-center">
            <div class="navbar-header">
                <a class="navbar-brand" href="../admin/adminPanel.php">Panel administracyjny</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li><a href="wholeShopHistory.php">Pełna historia zakupów</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="sendMessage.php">Wyślij wiadomość</a></li>
                    <li style="margin-top: 15px">Zalogowany jako:
                        <a class="user" href="adminPanel.php">
                            <?php
                            echo $admin->getLogin();
                            ?>
                        </a>
                    </li>
                    <li><a href="logout.php">
                            <span class="glyphicon glyphicon-log-out"></span> Wyloguj
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>