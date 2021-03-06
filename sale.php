<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $content = file_get_contents(__("struttura.html"));

        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                setTitle($content, "What we offer");
                addBreadcrumb($content, "What we offer", "");
        }else{
                setTitle($content, "Cosa Offriamo");
                addBreadcrumb($content, "Cosa Offriamo", "");
        }
        addScreenStylesheet("CSS" . DIRECTORY_SEPARATOR . "style_offerta.css", $content);
        setUserStatus($content);
        setupMenu($content, 1);
        setAdminArea($content);
        setLangArea($content, "sale.php");
        setContentFromFile($content, __("contenuto_cosaoffriamo.html"));
        $xml = new DOMDocument();
        $xml->loadHTML($content);
        setHTMLNameSpaces($xml);
        $content = $xml->saveXML($xml->documentElement);
        addXHTMLdtd($content);
        echo($content);
?>
