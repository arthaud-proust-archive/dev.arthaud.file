<?php
$files = array();

if ($handle = opendir('./files')) {

    while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != ".." && $entry != "index.php") {
            array_push($files, $entry);
        }
    }
    closedir($handle);
}


function size($file, $decimals = 0) {
    $bytes = filesize('./files/'.$file);
    $factor = floor((strlen($bytes) - 1) / 3);
    if ($factor > 0) $sz = 'KMGT';
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'o';
}
?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Télécharger</title>
        <script src="/res/jquery-min.js" defer></script>
        <script src="/res/meta.js" defer></script>
        <link rel="stylesheet" href="/res/styles.css">
    </head>
    <body>
    
    <?php
    if(count($files) == 0) {
        echo('<h3>Aucun fichier</h3>');
    } else if(count($files) == 1) {
        echo('<h3>1 fichier</h3>
        <ul id="files">
            <li class="file"><label><input type="checkbox" checked data-file="'.$files[0].'">'.$files[0].'  ('.size($files[0]).')</label><button class="downFile ">Télécharger</button></li>
        </ul>
        ');
    } else {
        echo('
            <h1>'.count($files).' fichiers</h1>
            <ul id="files">
            <li class="headbar"><label><input type="checkbox" checked class="selectAll">Tout sélectionner</label><button class="downAll">Tout télécharger</button></li>
            ');
        foreach($files as $file) {
            echo('<li class="file"><label><input type="checkbox" checked data-file="'.$file.'">'.$file.'  ('.size($file).')</label><button class="downFile ">Télécharger</button></li>');
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
