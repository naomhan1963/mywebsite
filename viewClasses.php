<?php

    require_once __DIR__ . '/vendor/autoload.php';

    // API URL and authorization token
    $url = "https://api.wonde.com/v1.0";
    $token = getenv('API_TOKEN');

    if ($token !== false) {
    } else {
        // Inform the user that the API token cannot be accessed/is not set.
        echo "API Access Token is not set/cannot be accessed.";
    }
    $schoolid = "A1930499544"; //School ID

    if (!isset($_POST["teacherId"])) {
        $employeeID = $_GET['test1'];
    } else {
        $employeeID = $_POST["teacherId"];
    }
    // Using either GET OR POST to determine if the user is entering the page from the home.php or has used the back button on viewStudents.php.
    if (!isset($_POST["dayOfWeek"])) {
        $dayer = $_GET['test2'];
    } else {
        $dayer = $_POST["dayOfWeek"];
    }
    
    $dayer = strtolower($dayer); // Convert to lowercase for if condition so it matches output from URL.

    $client = new \Wonde\Client($token);

    $school = $client->school($schoolid);

    $url3 = "https://api.wonde.com/v1.0/schools/{$schoolid}/classes/?cursor=true&include=lessons.period&per_page=500";
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
    
        $classesData = $data['data'];
    
        // Initialize an array to store the matching classes
        $matchingClasses = [];
    
        foreach ($classesData as $class) {
            if (isset($class['lessons']['data'])) {
                $lessonsData = $class['lessons']['data'];
    
                foreach ($lessonsData as $lesson) {
                    if (isset($lesson['period'])) {
                        $periodData = $lesson['period']['data'];
                        if ($lesson['employee'] == $employeeID && $periodData['day'] == $dayer) {
                            // Store the matching class data in the array
                            $matchingClasses[] = [
                                'period' => $periodData['name'],
                                'periodstart' => $periodData['start_time'],
                                'periodend' => $periodData['end_time'],
                                'class' => $class['name']
                            ];
                        }
                    }
                }
            }
        }
        ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Class</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through the matching classes and populate the table rows
                foreach ($matchingClasses as $class) {
                    echo '<tr>';
                    echo '<td>' . $class['period'] . '</td>';
                    echo '<td>' . $class['periodstart'] . '</td>';
                    echo '<td>' . $class['periodend'] . '</td>';
                    echo '<td>' . $class['class'] . '</td>';
                    echo "<td><a href='viewStudents.php?class=" . $class['class'] . "&day=" . $dayer . "&id=". $employeeID . "'>View Students</a></td>";
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <?php
        echo "<a href='home.php'><input type='submit' name='submit' value='Back' style='width:150px; height: 40px;'/></a>";
        //Back Button
    } else {
        echo 'Empty response from the API.';
    }
?>
