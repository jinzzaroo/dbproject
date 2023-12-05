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
    $newMeatName = $_POST['new_meat_name'];
    $newPrice = $_POST['new_price'];
    $newQuantity = $_POST['new_quantity'];
    $updateQuery = "UPDATE Meat SET meat_name = '$newMeatName', price = '$newPrice', quantity = '$newQuantity' WHERE id = '$meatID'";

    if ($conn->query($updateQuery) === TRUE) {
        echo "고기 정보가 성공적으로 업데이트되었습니다!";
        header("Location: meatlist.php?shop_id=$shopID");
        exit();
    } else {
        echo "Error: " . $updateQuery . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/style2.css">
    <title>고기 수정</title>
    <style>
    form {
        max-width: 400px;
        margin: 2rem auto;
        padding: 1rem;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-top: 1rem;
        font-weight: bold;
    }

    input {
        width: calc(100% - 1rem); 
        padding: 0.5rem;
        margin: 0.5rem 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
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
            <h1><a href="../../index.php">MeatView.</a></h1>
        </div>
        <div class="nav-right">
            <ul>
                <?php
                echo '<li><a href="../seller_dashboard.php">Home</a></li>';
                echo '<li><a href="../../user/logout.php">Sign out</a></li>';
                ?>
            </ul>
        </div>
    </nav>

    <form method="post" action="edit_meat.php?meat_id=<?php echo $meatID; ?>">
        <h1 style="text-align: center;">고기 수정</h1>
        <br>
        <label for="new_meat_name">고기 이름</label>
        <input type="text" name="new_meat_name" placeholder="<?php echo htmlspecialchars($meatRow['meat_name']); ?>" onfocus="clearDefault(this)" required>
        <br>
        <label for="new_price">가격</label>
        <input type="text" name="new_price" placeholder="<?php echo htmlspecialchars($meatRow['price']); ?>" onfocus="clearDefault(this)" required>
        <br>
        <label for="new_quantity">수량</label>
        <input type="text" name="new_quantity" placeholder="<?php echo htmlspecialchars($meatRow['quantity']); ?>" onfocus="clearDefault(this)" required>
        <br>
        <input type="submit" value="Update">
    </form>

</body>

</html>