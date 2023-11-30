<?php
include '../config.php';

// 페이지네이션
$itemsPerPage = 5; // 페이지당 아이템 수
$pageShops = isset($_GET['pageShops']) ? $_GET['pageShops'] : 1;

// 검색어
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// 정육점 목록 가져오기 (페이지네이션 및 검색어 적용)
$queryShops = "SELECT id, shop_name, location FROM ButcherShop
               WHERE shop_name LIKE '%$searchTerm%' OR location LIKE '%$searchTerm%'
               LIMIT " . ($pageShops - 1) * $itemsPerPage . ", $itemsPerPage";
$resultShops = $conn->query($queryShops);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop List</title>
    <style>
        body {
            display: flex;
        }

        .dashboard-container {
            flex: 1;
            margin-right: 20px;
        }

        .shop-list {
            list-style-type: none;
            padding: 0;
        }

        .shop-list-item {
            margin-bottom: 10px;
        }

        .add-to-favorites {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="text"] {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="dashboard-container">

<div class="navbar">
        <a href="buyer_dashboard.php">Home</a>
        <a href="../user/logout.php">Logout</a>
</div>

    <h2>Shop List</h2>

    <!-- 검색창 추가 -->
    <form method="get" action="shoplist.php">
        <label for="searchShops">Search:</label>
        <input type="text" name="search" id="searchShops" value="<?= $searchTerm ?>">
        <input type="submit" value="Search">
    </form>

    <ul class="shop-list">
        <?php
        while ($row = $resultShops->fetch_assoc()) {
            echo "<li class='shop-list-item'>
                    {$row['shop_name']} - {$row['location']}
                    <form method='post' action='add_favorites.php'>
                        <input type='hidden' name='shop_id' value='{$row['id']}'>
                        <input type='submit' class='add-to-favorites' value='Add to Favorites'>
                    </form>
                  </li>";
        }
        ?>
    </ul>

    <!-- 페이징 링크 추가 -->
    <?php
    $queryCountShops = "SELECT COUNT(*) as count FROM ButcherShop
                        WHERE shop_name LIKE '%$searchTerm%' OR location LIKE '%$searchTerm%'";
    $countShops = $conn->query($queryCountShops)->fetch_assoc()['count'];
    $totalPagesShops = ceil($countShops / $itemsPerPage);

    for ($i = 1; $i <= $totalPagesShops; $i++) {
        echo "<a href='shoplist.php?pageShops=$i&search=$searchTerm'>$i</a> ";
    }
    ?>
</div>

</body>
</html>
