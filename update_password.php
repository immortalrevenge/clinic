<?php
include 'conn.php';
// Update user's login credentials

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql1 = 'UPDATE user_tbl SET 
                  prc_id = ?, 
                  fname = ?, 
                  lname = ?, 
                  mname = ?, 
                  contact_no = ?, 
                  position = ?
                  WHERE user_id = ?';
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param('isssssi', $_POST['prc_id'], $_POST['fname'], $_POST['lname'], $_POST['mname'], $_POST['contact_no'], $_POST['position'], $_POST['user_id']);
    $stmt1->execute();
    $sql2 = 'UPDATE account_tbl SET 
                   email = ?, 
                   username = ?, 
                   password = ?
                   WHERE user_id = ?';
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('sssi', $_POST['email'], $_POST['username'], $password, $_POST['user_id']);
    $stmt2->execute();
}

?>