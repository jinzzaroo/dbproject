<?php
include '../config.php';

session_start();

// 구매자로 로그인된 경우에만 접근 허용
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
    header('Location: ../user/login.php'); // 구매자가 아니면 로그인 페이지로 이동
    exit();
}

// 현재 로그인한 구매자의 ID
$buyerID = $_SESSION['id'];

// 페이지네이션
$itemsPerPage = 5; // 페이지당 아이템 수
$pageFavorites = isset($_GET['pageFavorites']) ? $_GET['pageFavorites'] : 1;

// 검색어
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// 즐겨찾기 목록 가져오기 (검색어 적용)
$queryFavorites = "SELECT bs.id, bs.shop_name, bs.location
                FROM ButcherShop bs
                JOIN Favorites f ON bs.id = f.shop_id
                WHERE f.buyer_id = '$buyerID'
                    AND (bs.shop_name LIKE '%$searchTerm%' OR bs.location LIKE '%$searchTerm%')
                LIMIT " . ($pageFavorites - 1) * $itemsPerPage . ", $itemsPerPage";
$resultFavorites = $conn->query($queryFavorites);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Dashboard</title>
    <style>
        body {
            display: flex;
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }

        .dashboard-container {
            flex: 1;
            margin-right: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .navbar {
            background-color: #333;
            padding: 10px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }

        h2 {
            color: #333;
        }

        input[type="text"] {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .button-container {
            display: flex;
            margin-bottom: 10px;
        }

        .view-shop-button,
        .shop-list-button,
        .delete-button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 5px;
        }

        .delete-button {
            background-color: #f44336;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        .favorite-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            justify-content: center;
        }

        .pagination a {
            margin-right: 5px;
            text-decoration: none;
            color: #333;
            background-color: #f9f9f9;
            padding: 5px 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="dashboard-container" id="favorites">
    <div class="navbar">
        <a href="../index.php">Home</a>
        <a href="../user/logout.php">Logout</a>
    </div>
    <h2>즐겨찾기 목록</h2>
    <!-- 검색창 및 버튼 컨테이너 추가 -->
    <div class="button-container">
        <form method="get" action="buyer_dashboard.php">
            <input type="text" name="search" id="searchFavorites" value="<?= $searchTerm ?>" placeholder="검색">
            <input type="submit" value="검색">
        </form>
        <form method="get" action="shoplist.php">
            <input type="submit" class="shop-list-button" value="Shop List">
        </form>
    </div>

    <ul>
        <?php
        while ($row = $resultFavorites->fetch_assoc()) {
            echo "<li class='favorite-item'>
                    {$row['shop_name']} - {$row['location']} 
                    <form method='get' action='shopinfo.php'>
                        <input type='hidden' name='shopID' value='{$row['id']}'>
                        <input type='submit' class='view-shop-button' value='View Shop'>
                    </form>
                    <button class='delete-button' onclick='deleteShop({$row['id']})'>Delete</button>
                  </li>";
        }
        ?>
    </ul>

    <!-- 페이징 링크 추가 -->
    <div class="pagination">
        <?php
        $queryCountFavorites = "SELECT COUNT(*) as count FROM Favorites f
                                JOIN ButcherShop bs ON f.shop_id = bs.id
                                WHERE f.buyer_id = '$buyerID'
                                AND (bs.shop_name LIKE '%$searchTerm%' OR bs.location LIKE '%$searchTerm%')";
        $countFavorites = $conn->query($queryCountFavorites)->fetch_assoc()['count'];
        $totalPagesFavorites = ceil($countFavorites / $itemsPerPage);

        for ($i = 1; $i <= $totalPagesFavorites; $i++) {
            echo "<a href='buyer_dashboard.php?pageFavorites=$i&search=$searchTerm'>$i</a>";
        }
        ?>
    </div>
</div>

<script>
    function deleteShop(shopID) {
        if (confirm("정말로 삭제하시겠습니까?")) {
            // Make an asynchronous request to deleteShop.php
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_shop.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Reload the page or update the list after successful deletion
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            };
            xhr.send("shopID=" + shopID);
        }
    }
</script>

</body>
</html>
