<?php
require('config.php');
header('Content-Type: application/json');

use Phpfastcache\CacheManager;

// Get instance of files cache
    $objFilesCache = CacheManager::getInstance('files');

    $key = "welcome_message";
    
    // Try to fetch cached item with "welcome_message" key
    $CachedString = $objFilesCache->getItem($key);

    if (is_null($CachedString->get()) || $caching_status == 'OFF')
    {
            /// Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
        
            $sql = "SELECT * FROM heroku_4ec96a11d22cba3.covid2";
            $result =  $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                        $json = $row["jsons"];

                        if(is_null($CachedString->get()) && $caching_status == 'ON') // The cached entry doesn't exist
                        {
                            $numberOfSeconds = 3600;
                            $CachedString->set($json)->expiresAfter($numberOfSeconds);
                            $objFilesCache->save($CachedString);
                        }
                }
            } else {
                $json = "{}";
            }
    }
    else{   //display caching
        $json = json_decode($CachedString->get(),true);
        $json = (isset($_GET['country']) ? "{\"".$_GET['country']."\":".json_encode($json[$_GET['country']])."}" : json_encode($json) );
    }
    //echo JsonHelper::prettyFormat($json);
    print_r(json_decode($json,true));
?>