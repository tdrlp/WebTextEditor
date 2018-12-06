<?php
    // Get the servers abosulte root directroy via the current working directroy.
    $root = dirname(getcwd());

    // Get the MyFiles absolute directroy.
    $myFilePath = $root . "\\MyFiles";
    
    // If the MyFiles directroy does not exist, output an error and terminate the script.
    // Otherwise, get an array of files in the MyFiles directroy.
    if(!file_exists($myFilePath)) {
    
        die("The MyFiles directroy is missing!"); 
    }

    // Get an array of files from the MyFiles directroy.
    $myFiles = scandir($myFilePath);

    // Remove the current and parent directory links.
    unset($myFiles[0], $myFiles[1]);

    // Rebase the array indicies after the unsets.
    $myFiles = array_values($myFiles);

    // Iterate through the file array and add each file to the json data array.
    for($i = 0; $i < sizeof($myFiles); $i++) {

        $data['file' . strval($i)] = $myFiles[$i];
    }

    // Check if the server has recieved any requests.
    if($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["file"] != null) {

        // Get the file name send sent to the server
        $data['requestedFileName'] = $_GET["file"];

        // Create the file path.
        $fileToOpen = $myFilePath . "\\" . $_GET["file"];

        // Try to open the file.
        $handle = fopen($fileToOpen, "r") or die("<br /><br />Unable to open " . $_GET["file"]);

        // Add the file contnet to the file array.
        $data['fileContent'] = fread($handle, filesize($fileToOpen));
    }
    else if($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["saveFile"] != null && $_GET["saveContent"] != null) {

        $contentToSave = $_GET['saveContent'];

        $fileToOpen = $myFilePath . "\\" . $_GET['saveFile'];

        if(!file_put_contents($fileToOpen, $contentToSave)) {

            $data["writeResult"] = "Failed to save the file.";
        }
        else {

            $data["writeResult"] = "File saved.";
        }
    }



    // Send the data encoded as json.
    echo json_encode($data);
?>