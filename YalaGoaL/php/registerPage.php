<?php
$page_title = 'YalaGoaL - Register';
include 'mysql_con.php'; 

$error_message = ''; // لتخزين رسالة الخطأ

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];    

    $stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        header('Location: account.php'); 
        exit();} 
    else{
        $error_message = "Error: Could not create account. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include('header.php');?>

<body>
<?php include('nav.php');?>

<div class="containerLogin">
    <section>
        <h1>Make a New Account</h1>
        <form method="POST">
            <input type="text" name="name" placeholder="Name" required class="inputbox">
            <input type="email" name="email" placeholder="Email" required class="inputbox">
            <input type="text
            " name="password" placeholder="Password" required class="inputbox">
            <div class="check">
                <input type="checkbox" required>
                <label>I am over 18 and have read the <a href="#">T&Cs</a> and <a href="#">privacy policy</a></label>
            </div>
            <div class="buttons">
                <input type="submit" value="Sign in">
                <div>Already have an account? <a href="account.php">Login</a></div>
            </div>
            <?php if ($error_message) { echo "<p class='error'>$error_message</p>"; } ?>
        </form>
    </section>
</div>

</body>
</html>
