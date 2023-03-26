<?php
// this header needs to set according to where your frontend is running
header('Access-Control-Allow-Origin: *');

header("Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE");


include_once "connect.php";

session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud_req'] == 'register') {
    register($conn);
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud_req'] == 'login') {
    login($conn);
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud_req'] == 'initialize') {
    initialize($conn);
}
elseif ($_SERVER['REQUEST_METHOD'] == 'GET'){
    logout($conn);
}


    //---------------------------------------------------------------
    function initialize($conn)
    {
        echo '<script>alert("Initializing Database...")</script>';

        $sql = "DROP TABLE IF EXISTS user;
                CREATE TABLE user (
                  username VARCHAR(50) PRIMARY KEY,
                  password VARCHAR(50),
                  firstName VARCHAR(50),
                  lastName VARCHAR(50),
                  email VARCHAR(50) UNIQUE
                );
    
                INSERT INTO user (username, password, firstName, lastName, email)
                VALUES ('user1', 'password1', 'John', 'Doe', 'john.doe@example.com'),
                       ('user2', 'password2', 'Jane', 'Smith', 'jane.smith@example.com'),
                       ('user3', 'password3', 'Bob', 'Johnson', 'bob.johnson@example.com'),
                       ('user4', 'password4', 'Emily', 'Lee', 'emily.lee@example.com'),
                       ('user5', 'password5', 'David', 'Brown', 'david.brown@example.com')";
    
        $conn->multi_query($sql);
    }

    function login($conn)
    { 
        $isSuccess=0;

        $stmt = $conn->prepare("SELECT userName, password FROM user WHERE userName = ?");
        $stmt->bind_param('s', $usr);
        
        $usr=$_POST["user_names"];
       
        $sql = "SELECT * FROM user WHERE userName= ?";
        $statement = $conn->prepare($sql);
        $statement->bind_param('s', $usr);
        $statement->execute();
        $result = $statement->get_result();
 
        while ($row = $result->fetch_assoc()) {
         if (!empty($row)) {
             $pass = $row["password"];
             if (password_verify($_POST["passwords"], $pass)) {
                 $isSuccess = 1;
             }
         }
     }
     if ($isSuccess == 0) {
        http_response_code(401);
        echo "Invalid User name or password"; 
     } else {
       
        $key = password_hash($usr, PASSWORD_DEFAULT);
        $_SESSION[$key] = $usr;
        setcookie('user', $key);
        http_response_code(200);
        echo 'welcome ' . $usr;  
     }
     }
     function register($conn)
     {  
        $usr=$_POST["user_names"];
        $hash = password_hash($_POST['passwords'], PASSWORD_DEFAULT);
        $first =$_POST["FirstName"];
        $last =$_POST["LastName"];
        $email =$_POST["email"];

        //check for dupe user
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->execute([$usr]);
        $user = $stmt->fetch();
        if ($user) {
            // username already exists, return an error message
            echo 'Username already exists.';
            exit();
        }
        else{
            //check for dupe email
            $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if ($user) {
                // email already exists, return an error message
                echo 'Email already in use.';
                exit();
            }
            else{//not a dup user or email
                $sql = "INSERT INTO user (username, password, firstName, lastName, email) VALUES ('$usr','$hash', '$first', '$last', '$email')";
                if ($conn->query($sql) === TRUE) {
                   print("Success! Please Log In To Continue.");
                   $conn->close();
                }
                else {
                   echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        } 
     }

     function logout()
    {
        if (!isset($_COOKIE['user'])) {
            echo "You are currently not logged in.";
            exit();
        }
        unset($_SESSION['user']);
        session_destroy();
        setcookie('user', false);
        echo "Logging Out..." ;
        exit();
    }
       
