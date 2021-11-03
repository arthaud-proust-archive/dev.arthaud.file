<?php
$path = './files/'.$_GET["path"].'/';
$extList = file("extList.txt", FILE_IGNORE_NEW_LINES);
$files = array();
$desc = 'Cliquez pour voir les fichiers disponibles';

if(is_dir($path) && $_GET["path"]!='') {
    if ($handle = opendir($path)) {

        while (false !== ($entry = readdir($handle))) {
    
            if ($entry != "." && $entry != ".." && $entry != "index.php") {
                array_push($files, $entry);
            }
        }
        closedir($handle);

        if(count($files) == 0) {
            $desc = 'Aucun fichier';
        } else if(count($files) == 1) {
            $desc = '1 fichier, '.$files[0];
        } else {
            $desc = count($files).' fichiers';
            foreach($files as $file) {
                $desc.= ", ".$file;
            }
        } 
    } else {
        $desc = 'Dossier inaccessible';
    }
} else {
    $desc = 'Lien invalide';
}



function size($file, $decimals = 0) {
    $bytes = filesize($GLOBALS['path'].$file);
    $factor = floor((strlen($bytes) - 1) / 3);
    if ($factor > 0) $sz = 'KMGT';
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'o';
}

function getLink($file) {
    $info = new SplFileInfo($file);
    if (in_array($info->getExtension(), $GLOBALS['extList'])) {
        return('<li class="file"><label><input type="checkbox" checked data-file="'.$GLOBALS['path'].$file.'">'.$file.'  ('.size($file).')</label><a class="downFile" data-method="js">Télécharger</a></li>');
    } else {
        return('<li class="file"><label><input type="checkbox" checked data-file="'.$GLOBALS['path'].$file.'">'.$file.'  ('.size($file).')</label><a href="'.$GLOBALS['path'].$file.'" download="'.$file.'" class="downFile" data-method="html">Télécharger</a></li>');
    }

}





?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Télécharger les fichiers</title>
        <script src="/res/jquery-min.js" defer></script>
        <script src="/res/meta.js" defer></script>
        <link rel="stylesheet" href="/res/styles.css">

        <meta name=target content=noindex>
        <meta name=robots content=noindex>
        <meta name=theme-color content=#212121>

        <meta name=description content="<?=$desc ?>">
        <meta name=author content="Arthaud PROUST">
        <meta name=reply-to content=contact@arthaud.dev>
        <meta name=subject content=developpement>
        <meta name=language content=FR>
        <meta name=owner content="Arthaud PROUST">
        <meta name=url content=https://file.arthaud.dev>
        <meta name=identifier-URL content=https://file.arthaud.dev>
        
        <meta property=og:title content="Télécharger les fichiers">
        <meta property=og:type content=website>
        <meta property=og:description content="<?=$desc ?>">
        <meta property=og:site_name content=file.arthaud.dev>
        <meta property=og:url content=https://file.arthaud.dev>
        <meta property=og:locale content=fr_FR>
        <meta property=og:image content=https://arthaud.dev/img/flask_2048_min.webp>

        <meta name=twitter:card content=summary>
        <meta name=twitter:title content="Télécharger les fichiers">
        <meta name=twitter:site content=file.arthaud.dev>
        <meta name=twitter:description content="<?=$desc ?>">
        <meta name=twitter:image content=https://arthaud.dev/img/flask_2048_min.png>
        <meta name=twitter:image:alt content="Télécharger les fichiers">

        <meta name=apple-mobile-web-app-capable content=yes>
        <meta name=apple-mobile-web-app-status-bar-style content=#212121>
        <meta name=apple-mobile-web-app-title content="Télécharger les fichiers">

        <link rel=canonical href=https://file.arthaud.dev/ >
    </head>
    <body>
    <?php
    if(!is_dir($path) || $_GET["path"]=='') {
        echo('<h3>Lien invalide</h3>');
    } else if(count($files) == 0) {
        echo('<h3>Aucun fichier</h3>');
    } else if(count($files) == 1) {
        echo('<h3>1 fichier</h3>
        <ul id="files">'.
            getLink($files[0])
        .'</ul>
        ');
        //<li class="file"><label><input type="checkbox" checked data-file="'.$GLOBALS['path'].$files[0].'">'.$files[0].'  ('.size($files[0]).')</label><button class="downFile ">Télécharger</button></li>
    } else {
        echo('
            <h1>'.count($files).' fichiers</h1>
            <ul id="files">
            <li class="headbar"><label><input type="checkbox" checked class="selectAll">Tout sélectionner</label><button class="downAll">Tout télécharger</button></li>
            ');
        foreach($files as $file) {
            echo(getLink($file));
        }
        if(count($files) > 7) {
            echo('<li class="headbar"><label><input type="checkbox" checked class="selectAll">Tout sélectionner</label><button class="downAll">Tout télécharger</button></li>');
        }
        echo('
        </ul>
        <sub id="log"></sub>
        '); 
    }  
    ?>
    </body>
</html>
