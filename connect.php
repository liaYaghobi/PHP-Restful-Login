
<?php

$servername= "localhost";
       $username = "root";
       $pass = "";
       $dbname = "lab4";
 
       $conn = new mysqli($servername, $username, $pass, $dbname);
       if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
          print("Failure!");
       }
      
       $sql =  "CREATE TABLE IF NOT EXISTS items (
         item_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
         username VARCHAR(255) NOT NULL,
         item_name VARCHAR(255) NOT NULL,
         categories VARCHAR(255) NOT NULL,
         description TEXT NOT NULL,
         price DECIMAL(10,2) NOT NULL,
         created_at DATE NOT NULL,
         FOREIGN KEY (username) REFERENCES user(username)
       )";

if ($conn->query($sql) === FALSE) {
   die("Error creating table: " . $conn->error);
}
?>