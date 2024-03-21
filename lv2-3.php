<?php
$xml = simplexml_load_file('LV2.xml');
foreach ($xml->record as $person) {
    echo "<div><h2>$person->id.";
    echo " {$person->ime}";
    echo " {$person->prezime}";
    if ($person->spol == 'Female') {
        echo " (F)";
    } else {
        echo " (M)";
    }
    echo '</h2><div>';
    echo "<div>Contact: $person->email \n</div><div>";
    $imageURL = (string)$person->slika;
    echo '<div class="flex-container">';
    echo "<div><img src=\"$imageURL\" loading='lazy'></div>";
    echo "<div class='eldin'>$person->zivotopis</div>";
    echo '</div>';
    echo '<hr>';
}