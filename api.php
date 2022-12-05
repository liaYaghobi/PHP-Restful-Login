<?php
// this header needs to set according to where your frontend is running
header('Access-Control-Allow-Origin: *');

header("Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE");


include_once "connect.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo 'Welcome to RESTful login System';
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud_req'] == 'register')
    register($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud_req'] == 'login')
    login($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET')
logout($conn);


    //---------------------------------------------------------------

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
        
        $sql = "INSERT INTO user (userName, password) VALUES ('$usr','$hash')";
  
        if ($conn->query($sql) === TRUE) {
           print("Success!");
         
           $conn->close();
         
           
        }
        else {
           echo "Error: " . $sql . "<br>" . $conn->error;
        }
       
     }
     function logout()
{
    if (!isset($_COOKIE['user'])) {
        echo "You are not logged in!!!";
        exit();
    }
    unset($_SESSION['user']);
    session_destroy();
    setcookie('user', false);
    echo "You are logged out!!! " . session_status();
    exit();
}
       
