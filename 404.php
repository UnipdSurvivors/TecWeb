<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

		$content = file_get_contents(__("struttura_404.html"));
        
        addScreenStylesheet("CSS" . DIRECTORY_SEPARATOR ."style_login.css", $content);
        setLoadScript($content, "preparaLightbox()");
        echo($content);

?>
