<?php
header('Content-Type: application/json'); // return JSON

$host = "localhost"; 
$user = "root";      
$pass = "Chirag@123";          
$db   = "craft_store";  

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["status"=>"error","message"=>"Connection failed: ".$conn->connect_error]);
    exit;
}

$action = $_POST['action'] ?? ''; // match the JS key




if ($action === "signup") {
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        echo json_encode(["status"=>"error","message"=>"Passwords do not match!"]);
        exit;
    }

    $check = $conn->prepare("SELECT * FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status"=>"error","message"=>"Email already registered!"]);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hash);

    if ($stmt->execute()) {
        echo json_encode(["status"=>"success","message"=>"You have successfully registered!"]);
    } else {
        echo json_encode(["status"=>"error","message"=>$stmt->error]);
    }
    exit;
}

if ($action === "signin") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            echo json_encode(["status"=>"success","message"=>"Login successful"]);
        } else {
            echo json_encode(["status"=>"error","message"=>"Invalid password!"]);
        }
    } else {
        echo json_encode(["status"=>"error","message"=>"User not found!"]);
    }
}

$conn->close();
?>
