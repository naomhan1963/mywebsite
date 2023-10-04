<?php
    require_once __DIR__ . '/vendor/autoload.php';
    $schoolid = "A1930499544";
    $token = getenv('API_TOKEN');

    if ($token !== false) {
    } else {
        // Inform the user that the API token cannot be accessed/is not set.
        echo "API Access Token is not set/cannot be accessed.";
    }

    $classer = $_GET['class']; //Retrieving class name through URL
    $employeeId = $_GET['id']; //Retrieving employee ID through URL
    $dayer = $_GET['day']; //Retrieving day of the week  through URL


    $url3 = "https://api.wonde.com/v1.0/schools/{$schoolid}/classes/?cursor=true&include=students&per_page=500"; //Could not get it to work adding the class ID to the URL.
    // Initialize a cURL session
    $ch3 = curl_init($url3);

    // Set cURL options
    curl_setopt($ch3, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $token,
    ]);
    curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);

    $response1 = curl_exec($ch3);
    if (curl_errno($ch3)) {
        echo 'Curl error: ' . curl_error($ch3);
    }
    curl_close($ch3);

    if (!empty($response1)) {
        $data = json_decode($response1, true);
    
        $classes = $data['data'];
    
        // Start building the HTML table
        echo '<table border="1">';
    
        // Create table headers
        echo '<tr>';
        echo '<th>First Name</th>';
        echo '<th>Surname</th>';
        echo '</tr>';
    
        foreach ($classes as $class) {
            // Check if the 'students' key exists, is an array, and is not empty
            if (isset($class['students']) && is_array($class['students']['data']) && !empty($class['students']['data'])) {
                // Getting ID
                $classname = $class['name'];
                if ($classname == $classer) {
                    // Loop through each student in the class.
                    foreach ($class['students']['data'] as $student) {
                        echo '<tr>';
                        echo '<td>' . $student['forename'] . '</td>';
                        echo '<td>' . $student['surname'] . '</td>';
                        echo '</tr>';
                    }
                }
            }
        }  
        echo '</table>'; // Close the table

        echo "<a href='viewClasses.php?test1=" . $employeeId . "&test2=" . $dayer . "'><input type='submit' name='submit' value='Back' style='width:150px; height: 40px;'/></a>";
        //Back Button
    } else {
        echo 'No data found in the response.';
    }
?>