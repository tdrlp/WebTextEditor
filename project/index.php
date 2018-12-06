<!DOCTYPE html>
<html>
    <head>
        <title>Text Editor</title>
        <link rel="stylesheet" type="text/css" href="index.css">
    </head>
    <body>
    
        <form action="index.php" method="GET">

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
                if($_SERVER["REQUEST_METHOD"] == "GET" && $_GET != null) {

                    $data['fileName'] = $_GET["file"];

                    $fileToOpen = $myFilePath . "\\" . $_GET["file"];

                    $handle = fopen($fileToOpen, "r") or die("<br /><br />Unable to open " . $_GET["file"]);

                    $data['fileContent'] = fread($handle, filesize($fileToOpen));
                }

                echo json_encode($data);
            ?>

            <br/>
            <textarea id="textArea"></textarea>
        </form>
    </body>
</html>