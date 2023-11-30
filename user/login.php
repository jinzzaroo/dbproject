<?php
include '../config.php'; // Adjust the path to config.php based on your project structure

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = $_POST['userid'];
    $password = $_POST['password'];

    $buyer_query = "SELECT * FROM Buyer WHERE userid = '$userid'";
    $buyer_result = $conn->query($buyer_query);

    $seller_query = "SELECT * FROM Seller WHERE userid = '$userid'";
    $seller_result = $conn->query($seller_query);

    if ($buyer_result->num_rows > 0) {
        $row = $buyer_result->fetch_assoc();
        $role = 'buyer';
    } elseif ($seller_result->num_rows > 0) {
        $row = $seller_result->fetch_assoc();
        $role = 'seller';
    } else {
        echo "사용자가 존재하지 않습니다.";
        exit();
    }

    $hashedPassword = $row['password'];
    $id = $row['id'];



    if (password_verify($password, $hashedPassword)) {
        session_start();
        $_SESSION['userid'] = $userid;
        $_SESSION['role'] = $role;
        $_SESSION['id'] = $id;


        if ($role === 'buyer') {
            header('Location: buyer_dashboard.php');
            exit();
        } elseif ($role === 'seller') {
            header('Location: ../seller/seller_dashboard.php');
            exit();
        }
    } else {
        echo "비밀번호가 일치하지 않습니다.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
</head>
<body>

<ul>
        <li><a href="../index.php">Home</a></li>
</ul>


<h2>로그인</h2>
<form method="post" action="login.php">
    <label for="userid">아이디:</label>
    <input type="text" name="userid" required>
    <br>
    <label for="password">비밀번호:</label>
    <input type="password" name="password" required>
    <br>
    <input type="submit" value="로그인">
</form>

</body>
</html>
