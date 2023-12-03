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
    <link rel="stylesheet" href="../style/style2.css">
    <title>Shop List</title>
</head>

<body>
    <nav>
        <div class="nav-left">
            <h1><a href="../index.php">MeatView.</a></h1>
        </div>
        <div class="nav-right">
            <ul>
                <?php
                echo '<li><a href="buyer_dashboard.php">Home</a></li>'; // 경로 수정
                echo '<li><a href="../user/logout.php">Sign out</a></li>'; // 경로 수정
                ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2 style="text-align: center;">ButcherShop List</h2>
        <br>
        <div class="button-container">
            <form method="get" action="shoplist.php">
                <input type="text" name="search" id="searchFavorites" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="검색">
                <input type="submit" value="검색">
            </form>
        </div>

        <ul>
            <?php
            while ($row = $resultShops->fetch_assoc()) {
                echo "<li class='favorite-item'>
                <div>
                    <strong>{$row['shop_name']}</strong> - {$row['location']}
                </div>                    
                <div clss = 'button-group'>
                        <form method='post' action='add_favorites.php'>
                            <input type='hidden' name='shop_id' value='{$row['id']}'>
                            <input type='submit' class='view-shop-button' value='Add to Favorites'>
                        </form>
                    </div>
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

        echo "<div class='pagination'>";
        for ($i = 1; $i <= $totalPagesShops; $i++) {
            echo "<a href='shoplist.php?pageShops=$i&search=$searchTerm'>$i</a> ";
        }
        echo "</div>";
        ?>
    </div>

</body>

</html>