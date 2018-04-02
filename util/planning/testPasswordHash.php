<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 3/31/18
 * Time: 3:24 PM
 */
echo "What is your password?\n";
$pass = fgets(STDIN);
$hashed = password_hash($pass, PASSWORD_DEFAULT);
echo "Password hash is " . $hashed . "\n";
echo "Verify password: \n";
$i = 0;
$check = true;
do {
    $verify = fgets(STDIN);
    if (password_verify($verify, $hashed)) {
        echo "Password verified successfully!\n";
        $check = false;
    } else {
       $i++;
       if ($i == 1) {
           $attempt = "1st";
       } else if ($i == 2) {
           $attempt = "2nd";
       } else {
           echo "3rd attempt failed. Account lockout.\n";
           $check = false;
       }
       echo $attempt . " failed. Please try again.";
    }
} while ($check);
