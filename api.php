<?php
// Set this header according to where your frontend is running
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE");

include_once "connect.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud_req'] == 'initialize') {
    initialize($conn);
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud_req'] == 'login') {
    login($conn);
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud_req'] == 'register') {
    register($conn);
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud_req'] == 'submitItem') {
    submitItem($conn);
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud_req'] == 'review') {
    review($conn);
}
else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'search') {
    search($conn);
}
elseif ($_SERVER['REQUEST_METHOD'] == 'GET'){
    logout();
}

function initialize($conn)
{
    echo "Initializing Database..."; 

    $sql = "DROP TABLE IF EXISTS reviews;
            DROP TABLE IF EXISTS items;
            DROP TABLE IF EXISTS user;

            CREATE TABLE user (
              username VARCHAR(50) PRIMARY KEY,
              password VARBINARY(255),
              firstName VARCHAR(50),
              lastName VARCHAR(50),
              email VARCHAR(50) UNIQUE
            );

            INSERT INTO user (username, password, firstName, lastName, email)
            VALUES ('user1', 'password1', 'John', 'Doe', 'john.doe@example.com'),
                   ('user2', 'password2', 'Jane', 'Smith', 'jane.smith@example.com'),
                   ('user3', 'password3', 'Bob', 'Johnson', 'bob.johnson@example.com'),
                   ('user4', 'password4', 'Emily', 'Lee', 'emily.lee@example.com'),
                   ('user5', 'password5', 'David', 'Brown', 'david.brown@example.com');

            CREATE TABLE items (
                item_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                username VARCHAR(255) NOT NULL,
                item_name VARCHAR(255) NOT NULL,
                categories VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                created_at DATE NOT NULL,
                FOREIGN KEY (username) REFERENCES user(username)
            );
            
            CREATE TABLE reviews (
                review_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                username VARCHAR(255) NOT NULL,
                item_id INT NOT NULL,
                rating VARCHAR(255) NOT NULL,
                review TEXT NOT NULL,
                created_at DATE NOT NULL,
                FOREIGN KEY (username) REFERENCES user(username),
                FOREIGN KEY (item_id) REFERENCES items(item_id)
            );
            ";
    $conn->multi_query($sql);
}

function login($conn)
{ 
    $username = $_POST["user_names"];
    $password = $_POST["passwords"];

    //check if user is already logged in
    if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
        http_response_code(401);
        echo "Another user is already logged in on this device";
        return;
    }

    $stmt = $conn->prepare("SELECT username, password FROM user WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (empty($row)) {
        http_response_code(401);
        echo "Invalid username or password"; 
    } else {
        $hash = $row["password"];
        if (password_verify($password, $hash)) {
            $_SESSION["logged_in"] = true;

            $key = password_hash($username, PASSWORD_DEFAULT);
            $_SESSION['user'] = $username;
            setcookie('user', $key);
            http_response_code(200);
            echo 'Welcome ' . $username;
            
        } else {
            http_response_code(401);
            echo "Invalid username or password"; 
        }
    }
}

function submitItem($conn){
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
        
        $username = $_SESSION['user'];
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM items WHERE username=? AND created_at=?");
        $stmt->bind_param("ss", $username, $created_at);
        
        $created_at = date("Y-m-d");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        
        if($count < 3){
            $stmt = $conn->prepare("INSERT INTO items (username, item_name, categories, description, price, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssds", $username, $item_name, $categories, $description, $price, $created_at);
            
            $item_name = $_POST["item_name"];
            $categories = $_POST["categories"];
            $description = $_POST["description"];
            $price = $_POST["price"];
            
            if ($stmt->execute()) {
                http_response_code(200);
                echo "Item added successfully";
            } else {
                http_response_code(500);
                echo "Error adding item: " . $stmt->error;
            }
        }
        else{
            http_response_code(400);
            echo "You have already added 3 items today";
        }
    } 
    else {
        // If user is not logged in, return an error message
        http_response_code(401);
        echo "You must be logged in to add an item";
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

function search($conn) {
    $ctg =$_GET['category'];

    // Query items by category
    $stmt = $conn->prepare("SELECT * FROM items WHERE FIND_IN_SET(?, categories) > 0");
    $stmt->execute([$ctg]);
    $result = $stmt->get_result();

    // Check if any results found
    if ($result->num_rows == 0) {
        echo "No items found with category: $ctg";
        exit();
    }

    $rows = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
}

function review($conn) {
    $rtg = $_POST['rating'];
    $txt = $_POST['textreview'];

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
        
        if($_POST["username"] != $_SESSION["user"]){
            $username = $_SESSION['user'];
            $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM reviews WHERE username=? AND created_at=?");
            $stmt->bind_param("ss", $username, $created_at);
            
            $created_at = date("Y-m-d");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $count = $row['count'];
            
            if($count < 3){
                $stmt = $conn->prepare("INSERT INTO reviews (username, item_id, rating, review, created_at) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $username, $item_id, $rating, $review, $created_at);
                
                $item_id = $_POST["item_id"];
                $rating = $_POST["rating"];
                $review = $_POST["textreview"];
                
                if ($stmt->execute()) {
                    http_response_code(200);
                    echo "Review added successfully";
                } else {
                    http_response_code(500);
                    echo "Error leaving review: " . $stmt->error;
                }
            }
            else{
                http_response_code(400);
                echo "You have already reviewed 3 items today";
            }
        }
        else {
            http_response_code(401);
            echo "You can't leave a review for your own item!";
        }
    } 
    else {
        // If user is not logged in, return an error message
        http_response_code(401);
        echo "You must be logged in to leave a review.";
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
    echo "Logging out..."; 
    exit();
}
