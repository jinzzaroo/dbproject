<!-- index.php -->

<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MeatView</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body class="main">

    <!-- Navigation Bar -->
    <nav>
        <div class="nav-left">
            <h1><a href="index.php">MeatView.</a></h1>
        </div>
        <div class="nav-right">
            <ul>
                <?php
                // Check if the user is a seller
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller') {
                    echo '<li><a href="seller/seller_dashboard.php">Seller Home</a></li>';
                    echo '<li><a href="user/logout.php">Sign out</a></li>';
                } elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer') {
                    echo '<li><a href="buyer/buyer_dashboard.php">User Home</a></li>';
                    echo '<li><a href="user/logout.php">Sign out</a></li>';
                } else {
                    // If not a seller or buyer (assuming not logged in)
                    echo '<li class="dropdown">';
                    echo '<a href="#" class="dropbtn">Menu</a>';
                    echo '<div class="dropdown-content">';
                    echo '<a href="user/register_buyer.php">Sign up</a>';
                    echo '<a href="user/login.php">Sign in</a>';
                    echo '<a href="user/register_seller.php">Sign up (Seller)</a>';
                    echo '</div>';
                    echo '</li>';
                }
                ?>
            </ul>
        </div>
    </nav>

    <div class="main-heading">
        <h1>Discover Quality Meats,<br>Local & Fresh!</h1>
    </div>
    <!-- Rest of your content -->

</body>

</html>