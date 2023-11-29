<?php
include 'config.php'; // 데이터베이스 연결 설정 파일

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
            header('Location: seller_dashboard.php');
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
    <title>판매자 회원가입</title>
</head>
<body>

<h2>판매자 회원가입</h2>
<form method="post" action="register_seller.php">
    <label for="userid">아이디:</label>
    <input type="text" name="userid" required>
    <br>
    <label for="password">비밀번호:</label>
    <input type="password" name="password" required>
    <br>
    <label for="seller_name">이름:</label>
    <input type="text" name="seller_name" required>
    <br>
    <label for="contact_number">연락처:</label>
    <input type="text" name="contact_number" required>
    <br>
    <label for="address">주소:</label>
    <input type="text" name="address" required>
    <br>
    <input type="submit" value="가입하기">
</form>

</body>
</html>
