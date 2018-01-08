<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        use DBAccess;

        session_start();
        checkLoggedUserAndRedirect("prenotazione_sala.php");
        $content = file_get_contents("struttura.html");

        setTitle($content, "Prenotazione Sale");
        initBreadcrumbs($content, "Home", "index.php");
        addBreadcrumb($content, "Prenotazione Sale", "");
        setUserStatus($content);
        setAdminArea($content);
        setupMenu($content, 3);
        setContentFromFile($content, "contenuto_prenotazioni.html");
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                $res = $dbAccess->getRoomList();
                for ($i=0; $i<count($res["Nome"]); $i++){
                        if ($res["Nome"][$i] == $_POST["Sale"]){
                                $sale = $sale .  "<option value='". $res["Nome"][$i] . "' selected='selected'>" . $res["Nome"][$i] . "</option>";
                        }else{
                                $sale = $sale .  "<option value='". $res["Nome"][$i] . "'>" . $res["Nome"][$i] . "</option>";
                        }
                }
                for ($i=0; $i<count($res["Funzione"]); $i++){
                        if ($res["Funzione"][$i] == $_POST['Servizio']){
                                $funz = $funz .  "<option value='". $res["Funzione"][$i] . "' selected='selected'>" . $res["Funzione"][$i] . "</option>";
                        }else{
                                $funz = $funz .  "<option value='". $res["Funzione"][$i] . "'>" . $res["Funzione"][$i] . "</option>";
                        }
                }
                $content = str_replace("<!--LISTASALE-->", $sale, $content);
                $content = str_replace("<!--LISTASERVIZI-->", $funz, $content);
                $content = str_replace("<!--VALOREDATA-->", $_POST["Data"], $content);
                $content = str_replace("<!--VALOREORA-->", $_POST["Ora"], $content);
                $content = str_replace("<!--VALOREDURATA-->", $_POST["Durata"], $content);
                if (isset($_POST['submit'])|| isset($_POST['submit2'])){
                        if (empty($_POST['Data'])){
                                $err = "<div id='statusfailed'>Inserire qualcosa nel campo data</div>";
                                $content = str_replace("<!--STATO-->", $err, $content);
                        }else{
                                if (checkDateInput($_POST['Data'])){
                                        $data = DateTime::createFromFormat("d/m/Y", $_POST['Data']);
                                        $data = $data->format("Ymd");
                                        $res = $dbAccess->checkBookings("1", $data);
                                        for ($i = 0; $i < count($res["Time"]); $i++){
                                                $resp = $resp . "<span class='booktime'>Ore " . $res["Time"][$i] . ":00</span><span class='bookstatus";
                                                if ($res["Status"][$i] == "Occupato"){
                                                        $resp = $resp . "unavailable'>" . $res["Status"][$i] . "</span><br />";
                                                }else if ($res["Status"][$i] == "Disponibile"){
                                                        $resp = $resp . "available'>" . $res["Status"][$i] . "</span><br />";
                                                }else{
                                                        $resp = $resp . "yourbooking'>" . $res["Status"][$i] . "</span><br />";
                                                }
                                        }
                                        $content = str_replace("<!--RISULTATIVERIFICA-->", $resp, $content);
                                }else{
                                        $errors = $errors . $_SESSION['dateerrors'];
                                        unset($_SESSION['dateerrors']);
                                }
                        }
                }
                if (isset($_POST['submit2'])){
                        $timeOk = checkTimeInput($_POST['Ora']);
                        $durOk = checkDurationInput($_POST['Durata']);
                        if($timeOk && $durOk){
                                $data = DateTime::createFromFormat("d/m/Y", $_POST['Data']);
                                $data = $data->format("Ymd");
                                $result = $dbAccess->newBooking($_SESSION['username'], $_POST['Sale'], $_POST["Servizio"], $data, $_POST['Ora'], $_POST['Durata']);
                                if ($result != ""){
                                        $errors = $errors . $result . "<br />";

                                }
                        }else{
                                $errors = $errors . $_SESSION['timeerrors'];
                                $errors = $errors . $_SESSION['durationerrors'];
                                unset($_SESSION['timeerrors']);
                                unset($_SESSION['durationerrors']);
                        }
                }
                if (!empty($errors)){
                        $content = str_replace("<!--STATO-->", "<div id='statusfailed'>" . $errors . "</div>", $content);
                }
        }
        echo($content);
?>
