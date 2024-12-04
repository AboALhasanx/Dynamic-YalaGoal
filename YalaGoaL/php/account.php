<?php

$page_title = 'YalaGoaL - Account';
include 'header.php';
include 'mysql_con.php'; 

$error_message = ''; // لتخزين رسالة الخطأ

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_SESSION['user_role'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password, name FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($db_password, $name);
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if ($password === $db_password) {
            $_SESSION['user_role'] = 'admin';
            $_SESSION['username'] = $name; 
            header('Location: account.php'); 
            exit();
        } else {
            $error_message = "Invalid password. Please try again.";
        }
    } else {
        $error_message = "No user found with that email.";
    }

    $stmt->close();
    $conn->close();
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: account.php'); 
    exit();
}
?>

<body>
<?php include('nav.php')?>

<div class="containerLogin">
    <section>
        <?php if (isset($_SESSION['user_role'])): ?>
            <h1>Welcome, <?= $_SESSION['username'] ?>!</h1> 
            <form method="POST">
                <div class="buttons">
                <input type="hidden" name="logout" value="true">
                <input type="submit" value="Log out">
            </div>
            </form>
        <?php else: ?>
            <h1>Welcome Back</h1>
            <?php if ($error_message) { echo "<p class='error'>$error_message</p>"; } ?>
            <form method="POST">
                <input type="email" name="email" placeholder="Email" required class="inputbox">
                <input type="password" name="password" placeholder="Password" required class="inputbox">
                <div class="check">
                    <input type="checkbox" required>
                    <label>I am over 18 and have read the <a href="#">T&Cs</a> and <a href="#">privacy policy</a></label>
                    </div>
                <div class="buttons">
                    <input type="submit" value="login">
                    <div>Don't have an account? <a href="registerPage.php">Sign in</a></div>
                </div>
            </form>
        <?php endif; ?>
    </section>
</div>

</body>
</html>
