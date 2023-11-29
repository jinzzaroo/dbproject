<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['buyer_id'])) {
    echo "구매자로 로그인 중입니다. 환영합니다, " . $_SESSION['buyer_name'] . "님!";
    echo '<br><a href="logout.php">로그아웃</a>';
} elseif (isset($_SESSION['seller_id'])) {
    echo "판매자로 로그인 중입니다. 환영합니다, " . $_SESSION['seller_name'] . "님!";
    echo '<br><a href="logout.php">로그아웃</a>';
} else {
    // If not logged in, show login and register links
    echo '<a href="login_buyer.php">구매자 로그인</a><br>';
    echo '<a href="register_buyer.php">구매자 회원가입</a><br>';
    echo '<br>';
    echo '<a href="login_seller.php">판매자 로그인</a><br>';
    echo '<a href="register_seller.php">판매자 회원가입</a>';
}
?>
