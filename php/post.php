<?php
$page_title = 'YalaGoaL - Post News';
include 'header.php';
include 'mysql_con.php'; 

$error_message = ''; 

$leagues = $conn->query("SELECT id, name FROM leagues")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ;
    $source = $_POST['source'] ;
    $league_id = $_POST['league_id'] ;

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $target_directory = '../images/news/';
        
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($image['type'], $allowed_types)) {
            $error_message = "Error: Only images (JPG, PNG, GIF, WEBP) are allowed.";
        } else {
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0755, true);
            }

            $count = count(glob($target_directory . 'news_image*.{jpg,jpeg,png,webp}', GLOB_BRACE)) + 1;
            $image_name = 'news_image' . $count . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
            $target_file = $target_directory . $image_name;

            if (move_uploaded_file($image['tmp_name'], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO news (title, image, sre, league_id, published_at) VALUES (?, ?, ?, ?, CURRENT_TIME)");
                if ($stmt) {
                    $stmt->bind_param("ssss", $title, $image_name, $source, $league_id);
                    
                    if ($stmt->execute()) {
                        header('Location: newsPage.php');
                        exit();
                    } else {
                        $error_message = "Error: Could not post news. Please try again.";
                    }
                } else {
                    $error_message = "Error: Could not prepare the query.";
                }
            } else {
                $error_message = "Error: Could not upload image.";
            }
        }
    } else {
        $error_message = "Error: Please select an image.";
    }

    if (isset($stmt)) {
        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<body>
<?php include('nav.php'); ?>

<div class="containerPost">
    <section>
        <h1>Post News</h1>
        <?php if ($error_message) { echo "<p class='error'>$error_message</p>"; } ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="image" required class="inputbox">
            <input type="text" name="title" placeholder="Title (max 100 characters)" required class="inputbox" maxlength="100">
            <div>
                <h4>Select League:</h4>
                <?php foreach ($leagues as $league): ?>
                    <input type="radio" name="league_id" value="<?= $league['id'] ?>" required>
                    <label><?= $league['name'] ?></label><br>
                <?php endforeach; ?>
            </div>
            <input type="text" name="source" placeholder="Source (max 100 characters)" required class="inputbox" maxlength="100">
            <div class="buttons">
                <input type="submit" value="Publish" class="submit-btn">
            </div>
        </form>
    </section>
</div>

</body>
</html>
