<?php
include '../config.php'; // 데이터베이스 연결 설정 파일

session_start();

// 판매자로 로그인된 경우에만 접근 허용
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header('Location: ../user/login.php'); // 판매자가 아니면 로그인 페이지로 이동
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shopName = $_POST['shop_name'];
    $location = $_POST['location'];
    $id = $_SESSION['id'];

    // 중복 체크 쿼리
    $duplicateCheckQuery = "SELECT COUNT(*) AS count FROM ButcherShop WHERE (shop_name = '$shopName' AND location = '$location') AND seller_id = '$id'";
    $duplicateCheckResult = $conn->query($duplicateCheckQuery);
    $duplicateCheckRow = $duplicateCheckResult->fetch_assoc();
    $duplicateCount = $duplicateCheckRow['count'];

    // 중복이 없는 경우에만 등록
    if ($duplicateCount == 0) {
        // 정육점 등록 쿼리 (주의: 반드시 입력값을 안전하게 처리해야 함)
        $query = "INSERT INTO ButcherShop (shop_name, location, seller_id) VALUES ('$shopName', '$location', '$id')";

        if ($conn->query($query) === TRUE) {
            echo "정육점이 등록되었습니다!";
            header('Location: seller_dashboard.php');
            exit();
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
            header('Location: register_shop.php');
            exit();
        }
    } else {
        echo "이미 등록된 정육점이 있습니다. 정육점 이름과 위치를 확인해주세요.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style2.css">
    <title>정육점 등록</title>
    <style>


        .container {
            max-width: 600px;
            margin: 2rem auto;
            background-color: #fff;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 1rem;
            font-weight: bold;
        }

        input {
            padding: 0.5rem;
            margin: 0.5rem 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <nav>
        <div class="nav-left">
            <h1><a href="../index.php">MeatView.</a></h1>
        </div>
        <div class="nav-right">
            <ul>
                <?php
                echo '<li><a href="seller_dashboard.php">Home</a></li>'; // 경로 수정
                echo '<li><a href="../user/logout.php">Sign out</a></li>'; // 경로 수정
                ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1 style="text-align: center;">정육점 등록</h1>
        <form method="post" action="register_shop.php">
            <label for="shop_name">정육점 이름:</label>
            <input type="text" name="shop_name" required>
            <label for="location">위치:</label>
            <input type="text" name="location" required>
            <input type="submit" value="등록">
        </form>
    </div>

</body>

</html>