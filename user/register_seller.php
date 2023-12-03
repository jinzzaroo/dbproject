<?php
include '../config.php'; // 데이터베이스 연결 설정 파일

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = $_POST['userid'];
    $password = $_POST['password'];
    $sellerName = $_POST['seller_name'];
    $contactNumber = $_POST['contact_number'];
    $address = $_POST['address'];

    // Check if userid already exists
    $checkQuery = "SELECT * FROM Seller WHERE userid = '$userid'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        echo "이미 존재하는 사용자 이름입니다. 다른 사용자 이름을 선택해주세요.";
    } else {
        // If userid is unique, proceed with registration
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO Seller (userid, password, seller_name, contact_number, address) VALUES ('$userid', '$hashedPassword', '$sellerName', '$contactNumber', '$address')";

        if ($conn->query($query) === TRUE) {
            echo "판매자 회원가입 성공!";
            header('Location: ../seller/seller_dashboard.php');
            exit();
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
            header('Location: register_seller.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title>회원가입(판매자용)</title>
</head>

<body>
    <nav>
        <div class="nav-left">
            <h1><a href="../index.php">MeatView.</a></h1>
        </div>
        <div class="nav-right">
            <ul>
                <?php
                echo '<li class="dropdown">';
                echo '<a href="#" class="dropbtn">Menu</a>';
                echo '<div class="dropdown-content">';
                echo '<a href="register_buyer.php">Sign up</a>';
                echo '<a href="login.php">Sign in</a>';
                echo '<a href="register_seller.php">Sign up (Seller)</a>';
                echo '</div>';
                echo '</li>';
                ?>
            </ul>
        </div>
    </nav>
    <div class="centered-form">
        <h2>Sign Up (Seller)</h2>
        <form method="post" action="register_seller.php">
            <label for="userid">ID</label>
            <input type="text" name="userid" required>
            <br>
            <label for="password">Password</label>
            <input type="password" name="password" required>
            <br>
            <label for="seller_name">Name</label>
            <input type="text" name="seller_name" required>
            <br>
            <label for="contact_number">Contact</label>
            <input type="text" name="contact_number" id="contact_number" placeholder="000-0000-0000" onfocus="clearDefault(this)" required> <br>
            <br>
            <label for="address">Address</label>
            <input type="text" name="address" required>
            <br>
            <input type="submit" value="Sign Up">
        </form>
    </div>

</body>

</html>