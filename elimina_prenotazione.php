<?php
	require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
	require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        //use DBAccess;

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedUser();
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                if ($_GET['id'] != $_SESSION['username']){
                        header("Location: accesso_negato.html");
                        exit();
                }else{
                        $dbAccess->deleteBooking($_GET['id'], $_GET['sala'], $_GET['servizio'], $_GET['data'], $_GET['ora']);
                        header("Location: userpanel.php");
                }
        }
?>
