<?php
require 'db.php';
include 'simple_html_dom.php';

$query = "SELECT * FROM reguli";

if ($result = $mysqli->query($query)) {

    /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
        printf ("%s \n", $row["id_regula"]);
    }

    /* free result set */
    $result->free();
}

/* close connection */
$mysqli->close();

$url = $_POST["url"];

// check if url address is well-formed
    if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
    echo("$url is a valid URL");
    $content = file_get_contents($url);
    $contentArray = split("\n",$content);
    foreach ($contentArray as $key => $value) {
    	echo $key.". ". $value."<br>";
    }

} else {
    echo("$url is not a valid URL");
}



//calculate percentage
/*foreach($data as $value){
    $percentage = $value['Overhead'] / $value['Revenue'] * 100;
    echo $value['month'] . " - " . $percentage . "%<br>";
}*/




//search URL from input
/*
$ch = curl_init();
$timeout = 5;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$html = curl_exec($ch);
curl_close($ch);

# Create a DOM parser object
$dom = new DOMDocument();

# Parse the HTML from Google.
# The @ before the method call suppresses any warnings that
# loadHTML might throw because of invalid HTML in the page.
@$dom->loadHTML($html);

# Iterate over all the <a> tags
foreach($dom->getElementsByTagName('a') as $link) {
        # Show the <a href>
        echo $link->getAttribute('href');
        echo "<br />";
}*/
?>
