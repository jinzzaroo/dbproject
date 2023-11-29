<?php
include 'config.php'; // Database connection settings

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = $_POST['userid'];
    $password = $_POST['password'];

    // Check if the user is a Buyer
    $buyer_query = "SELECT * FROM Buyer WHERE userid = '$userid'";
    $buyer_result = $conn->query($buyer_query);

    // Check if the user is a Seller
    $seller_query = "SELECT * FROM Seller WHERE userid = '$userid'";
    $seller_result = $conn->query($seller_query);

    if ($buyer_result->num_rows > 0) {
        $row = $buyer_result->fetch_assoc();
    } elseif ($seller_result->num_rows > 0) {
        $row = $seller_result->fetch_assoc();
    } else {
        echo "사용자가 존재하지 않습니다.";
        exit();
    }

    $hashedPassword = $row['password'];

    if (password_verify($password, $hashedPassword)) {
        session_start();
        $_SESSION['userid'] = $userid;

        if ($buyer_result->num_rows > 0) {
            $_SESSION['role'] = 'buyer';
            header('Location: buyer_dashboard.php'); // Redirect to buyer dashboard
        } elseif ($seller_result->num_rows > 0) {
            $_SESSION['role'] = 'seller';
            header('Location: seller_dashboard.php'); // Redirect to seller dashboard
        }

        exit();
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
