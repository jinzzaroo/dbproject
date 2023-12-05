<?php
include '../../config.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header('Location: ../../user/login.php');
    exit();
}

if (isset($_GET['meat_id'])) {
    $meatID = $_GET['meat_id'];
    $getShopIDQuery = "SELECT shop_id FROM Meat WHERE id = '$meatID'";
    $shopIDResult = $conn->query($getShopIDQuery);

    if ($shopIDResult->num_rows > 0) {
        $shopIDRow = $shopIDResult->fetch_assoc();
        $shopID = $shopIDRow['shop_id'];
    } else {
        echo "해당 고기의 정육점을 찾을 수 없습니다.";
        exit();
    }

    $meatQuery = "SELECT * FROM Meat WHERE id = '$meatID'";
    $meatResult = $conn->query($meatQuery);

    if ($meatResult->num_rows > 0) {
        $meatRow = $meatResult->fetch_assoc();
    } else {
        echo "해당 고기를 찾을 수 없습니다.";
        exit();
    }
} else {
    echo "고기 ID가 제공되지 않았습니다.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleteQuery = "DELETE FROM Meat WHERE id = '$meatID'";

    if ($conn->query($deleteQuery) === TRUE) {
        echo "고기가 성공적으로 삭제되었습니다!";
        header("Location: meatlist.php?shop_id=$shopID");
        exit();
    } else {
        echo "Error: " . $deleteQuery . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>고기 삭제</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 20px;
        }

        h2,
        h3 {
            color: #333;
        }

        p {
            margin-bottom: 10px;
        }

        form {
            margin-top: 15px;
        }

        input[type="submit"] {
            background-color: #d9534f;
            color: #fff;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #c9302c;
        }
    </style>
</head>

<body>

    <div style="max-width: 600px; margin: auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <h2>고기 삭제</h2>

        <h3>현재 고기 정보:</h3>
        <p>고기 이름: <?php echo $meatRow['meat_name']; ?></p>
        <p>가격: <?php echo $meatRow['price']; ?>원</p>
        <p>수량: <?php echo $meatRow['quantity']; ?></p>
        <p>정말로 삭제하시겠습니까?</p>
        <form method="post" action="delete_meat.php?meat_id=<?php echo $meatID; ?>">
            <input type="submit" value="삭제">
        </form>
    </div>

</body>

</html>