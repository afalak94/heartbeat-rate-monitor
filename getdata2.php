<?php
    include 'connect.php';
    $current_user = $_SESSION['user_name'];
    $myquery = "SELECT  `id`, `training_time` FROM  `trainings` WHERE user_name = '$current_user'";
    $result = mysqli_query($conn, $myquery);
    
    if ( ! $result ) {
        echo mysqli_error();
        die;
    }
    
    $data = array();
    

    for ($x = 0; $x < mysqli_num_rows($result); $x++) {
        $data[] = mysqli_fetch_assoc($result);
    }

    $array = json_decode(json_encode($data), true);
    $fp = fopen('data.csv', 'w');
    fwrite($fp, "id,training_time\n");

    foreach ($array as $array) {
        fputcsv($fp, $array);
    }

    fclose($fp);
?>