<?php
include '../config.php';

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
    header('Location: ../user/login.php');
    exit();
}

$buyerID = $_SESSION['id'];
$itemsPerPage = 5;
$pageFavorites = isset($_GET['pageFavorites']) ? (int)$_GET['pageFavorites'] : 1;

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
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
    <link rel="stylesheet" href="../style/style2.css">
    <title>Buyer Dashboard</title>
</head>

<body>
    <nav>
        <div class="nav-left">
            <h1><a href="../index.php">MeatView.</a></h1>
        </div>
        <div class="nav-right">
            <ul>
                <?php
                echo '<li><a href="../user/logout.php">Sign out</a></li>'; // 경로 수정
                ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2 style="text-align: center;">My ButcherShop</h2>
        <br>
        <div class="button-container">
            <form method="get" action="buyer_dashboard.php">
                <input type="text" name="search" id="searchFavorites" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="검색">
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
                <div>
                    <strong>{$row['shop_name']}</strong> - {$row['location']}
                </div>
                <div class='button-group'>
                    <form method='get' action='shopinfo.php'>
                        <input type='hidden' name='shopID' value='{$row['id']}'>
                        <input type='submit' class='view-shop-button' value='View Shop'>
                    </form>
                    <button class='delete-button' onclick='deleteShop({$row['id']})'>Delete</button>
                </div>
              </li>";
            }
            ?>
        </ul>

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
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_shop.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
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