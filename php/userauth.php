<?php
session_start();

require_once "../config.php";

//register users
function registerUser($fullnames, $email, $password, $gender, $country){
    //create a connection variable using the db function in config.php
    $conn = db();

   //check if user with this email already exist in the database
    $stmt = $conn->prepare("INSERT INTO students (full_names, country, email, gender, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullnames, $country, $email, $gender, $password);

    if ($stmt->execute()) {
        header("refresh:0.5, url=../dashboard.php");
        echo "<script>alert(('User Successfully registered'))</script>";
        $_SESSION["username"] = $fullnames;
    }

    $stmt->close();
    $conn->close();
}


//login users
function loginUser($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();

    // open connection to the database and check if username exist in the database
    $sql = "SELECT * FROM students WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // This just gives the information about the result that was found, it doesnt return the users data
    $result = $stmt->get_result();

    // Use this function to return the user data that was found in the $result variable
    $user = $result->fetch_assoc();

    if ($conn) {
        if ($result->num_rows > 0) {
            // Check if the password is the same with what is given
            if ($password === $user["password"]) {
                // if true then set user session for the user and redirect to the dasbboard
                $_SESSION["username"] = $user["full_names"];
                header("location: ../dashboard.php");
            } else {
                header("location: ../forms/login.html");
            }
        }  else {
            header("location: ../forms/login.html");
        }
    }
}


function resetPassword($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();

    //open connection to the database and check if username exist in the database
    $sql = "SELECT * FROM students WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($conn) {
        if ($result->num_rows > 0) { // if it does, replace the password with $password given
            $sql = "UPDATE students SET password = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $password, $email);
            $stmt->execute();
        } else {
            header("location: ../forms/resetpassword.html");
        }
    }
}

function getusers(){
    $conn = db();
    $sql = "SELECT * FROM Students";
    $result = mysqli_query($conn, $sql);
    echo"<html>
    <head></head>
    <body>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: magenta; border-style: none'; >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if(mysqli_num_rows($result) > 0){
        while($data = mysqli_fetch_assoc($result)){
            //show data
            echo "<tr style='height: 30px'>".
                "<td style='width: 50px; background: blue'>" . $data['id'] . "</td>
                <td style='width: 150px'>" . $data['full_names'] .
                "</td> <td style='width: 150px'>" . $data['email'] .
                "</td> <td style='width: 150px'>" . $data['gender'] . 
                "</td> <td style='width: 150px'>" . $data['country'] . 
                "</td>
                <form action='action.php' method='post'>
                <input type='hidden' name='id'" .
                 "value=" . $data['id'] . ">".
                "<td style='width: 150px'> <button type='submit', name='delete'> DELETE </button>".
                "</tr>";
        }
        echo "</table></table></center></body></html>";
    }
    //return users from the database
    //loop through the users and display them on a table
}

 function deleteaccount($id){
     $conn = db();
     //delete user with the given id from the database
 }
