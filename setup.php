<?php

error_reporting(1);

function sendMessage($message) {

  $message = $message . "\n";
  echo $message;

}

$version = "v0.3.1";

echo "\n";

sendMessage("Welcome to Kitsune Auto Installer " . $version);
sendMessage("Script Designed by AmusingThrone\n");

sendMessage("Would you like to install Kistune AS2 or AS3?");
$protocol = trim(fgets(STDIN));
$protocol = strtolower($protocol);

$types = array (
    'as2',
    'as3'
);

while (!in_array($protocol, $types)) {
  sendMessage("You can only respond in AS2 or AS3");
  sendMessage("Would you like to install Kistune AS2 or AS3?");
  $protocol = trim(fgets(STDIN));
  $protocol = strtolower($protocol);
}

sendMessage(strtoupper($protocol) . " protocol chosen.");

$url = "";

if ($protocol = $types[1]) {

$url         = "https://github.com/AmusingThrone/kitsune-auto-setup/raw/master/Kitsune.zip";

} elseif ($protocol = $types[2]) {

$url         = "https://github.com/Kits-une/Kitsune/archive/master.zip";

}

$zipFile     = "Kitsune.zip";
$zipResource = fopen($zipFile, "w");
$ch          = curl_init();
$zip         = new ZipArchive;
$extractPath = "kitsune";

sendMessage("Downloading " . $zipFile . " from Server...");

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

sendMessage("Done Downloading " . $zipFile);

curl_setopt($ch, CURLOPT_FILE, $zipResource);
$page = curl_exec($ch);

sendMessage("Preparing to Extract " . $zipFile);

if (!$page) {
    echo "Error :- " . curl_error($ch) . "\n";
}
curl_close($ch);

if ($zip->open($zipFile) != "true") {
    echo "Error :- Unable to extract Server\n";
}

$zip->extractTo($extractPath);
$zip->close();

sendMessage("Finished extracting\n");

//config starts.

$answers = array (
    'yes',
    'y'
);

$no      = array (
    'no',
    'n'
);

sendMessage("--------Configuration Setup--------");

echo "Enter Database Host: ";
$dbhost = trim(fgets(STDIN));
echo "\n";
echo "Enter Database Username: ";
$dbUser = trim(fgets(STDIN));
echo "\n";
echo "Enter Database Password: ";
$dbPass = trim(fgets(STDIN));
echo "\n";

while ($dbPass == "") {
    sendMessage("Please set a secure password for your Database");
    echo "Enter Database Password: ";
    $dbPass = trim(fgets(STDIN));
    echo "\n";
}

while (strlen($dbPass) < 5) {
    sendMessage("Please use a secure password for your Database");
    echo "Enter Database Password: ";
    $dbPass = trim(fgets(STDIN));
    echo "\n";
}

echo "Enter the Database Name: ";
$dbName = trim(fgets(STDIN));
echo "\n";

$con    = mysqli_connect($dbhost, $dbUser, $dbPass, $dbName);
$conan = "";


if (mysqli_connect_errno()) {
    sendMessage("We could not establish a connection to the Database");
    sendMessage("Error: " . mysqli_connect_error());
    echo "Continue Anyways? [y/n]";
    $conan = trim(fgets(STDIN));
    echo "\n";
} else {
    $xml                     = new DOMDocument('1.0', 'utf-8');
    $xml->formatOutput       = true;
    $xml->preserveWhiteSpace = false;
    $xml->load('kitsune/Database.xml');
    
    $xml->getElementsByTagName('name')->item(0)->nodeValue     = $dbName;
    $xml->getElementsByTagName('address')->item(0)->nodeValue  = $dbhost;
    $xml->getElementsByTagName('username')->item(0)->nodeValue = $dbUser;
    $xml->getElementsByTagName('password')->item(0)->nodeValue = $dbPass;
    
    $xml->save('kitsune/Database.xml');
    sendMessage("Successfully updated your Database.xml file!\n");
}

if (in_array($conan, $answers)) {
    $xml                     = new DOMDocument('1.0', 'utf-8');
    $xml->formatOutput       = true;
    $xml->preserveWhiteSpace = false;
    $xml->load('kitsune/Database.xml');
    
    $xml->getElementsByTagName('name')->item(0)->nodeValue     = $dbName;
    $xml->getElementsByTagName('address')->item(0)->nodeValue  = $dbhost;
    $xml->getElementsByTagName('username')->item(0)->nodeValue = $dbUser;
    $xml->getElementsByTagName('password')->item(0)->nodeValue = $dbPass;
    
    $xml->save("kitsune/Database.xml");
    sendMessage("Successfully updated your Database.xml file!\n");
} elseif (in_array($conan, $no)) {
    sendMessage("Shutting down Configuration Setup...");
    die("Kitsune " . strtoupper($protocol) . " Setup Finished! Enjoy!");
}

sendMessage("--------Database Setup--------");
sendMessage("Would you like to setup the database?");
echo "It's recommended you do, otherwise you will have to do this manually later [y,n]";
$answer = trim(fgets(STDIN));
echo "\n";

if (in_array($answer, $no)) {
    sendMessage("Shutting down Database Setup...");
    die("Kitsune " . strtoupper($protocol) . " Setup Finished! Enjoy!");
}

$database = file_get_contents('kitsune/Kitsune.sql');

mysqli_multi_query($con, $database);

mysqli_close($con);

sendMessage("The database is now setup!");
sendMessage("Kitsune " . strtoupper($protocol) . " Setup Finished! Enjoy!");

?>
