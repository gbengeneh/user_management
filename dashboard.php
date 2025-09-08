<?php
    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }

    require_once'config/database.php';

    // get user Information
    $user_id = $_SESSION["id"];
    $sql = "SELECT username , email, created_at , profile_image FROM users WHERE id=:id";
    $stmt = $pdo->prepare(($sql));
    $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
   <div class="container">
       <div class="header">
        <h2>Welcome , <?php echo htmlspecialchars($_SESSION["username"]) ?></h2>
       </div>




    <div class="user-info">
        <h3>Your Account Information</h3>
        .
    </div>
   </div>

</body>
</html>