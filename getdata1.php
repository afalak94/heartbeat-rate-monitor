<?php
    include 'connect.php';

    $user = $_SESSION['user_name'];
    $user = mysql_real_escape_string($user);
    //$myquery = "SELECT  `heart_rate`, `time` FROM  `podaci`";

    $myquery2 = "SELECT `heart_rate`, `time` FROM (
            SELECT `heart_rate`, `time` FROM `podaci` WHERE user_name='$user' ORDER BY time DESC LIMIT 100) sub
        ORDER BY time ASC"; 

    $result = mysqli_query($conn, $myquery2);
    
    if ( ! $result ) {
        echo mysqli_error();
        die;
    }
    
    $data = array();
    

    for ($x = mysqli_num_rows($result) - 100; $x < mysqli_num_rows($result); $x++) {
        $data[] = mysqli_fetch_assoc($result);
    }

    $array = json_decode(json_encode($data), true);
    $fp = fopen('data1.csv', 'w');
    fwrite($fp, "heart_rate,time\n");

    foreach ($array as $array) {
        @fputcsv($fp, $array);
    }

    fclose($fp);
?>