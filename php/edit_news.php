<?php
$page_title = 'YalaGoaL - Edit News';
include 'header.php';
include 'mysql_con.php';

$error_message = ''; 

// التحقق من وجود معلمة news_id في الرابط
if (isset($_GET['news_id']) && is_numeric($_GET['news_id'])) {
    $news_id = $_GET['news_id'];

    // جلب الخبر من قاعدة البيانات
    $stmt = $conn->prepare("SELECT * FROM news WHERE news_id = ?");
    $stmt->bind_param("i", $news_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $news = $result->fetch_assoc();

    if (!$news) {
        $error_message = "Error: News not found.";
    }
    
    $leagues = $conn->query("SELECT id, name FROM leagues")->fetch_all(MYSQLI_ASSOC);

    // التحقق من إرسال البيانات من النموذج
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $source = $_POST['source'];
        $league_id = $_POST['league_id'];

        // التحقق من وجود صورة جديدة
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image'];
            $target_directory = '../images/news/';

            // التحقق من نوع الصورة (JPEG, PNG, GIF, WEBP)
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($image['type'], $allowed_types)) {
                $error_message = "Error: Only images (JPG, PNG, GIF, WEBP) are allowed.";
            } else {
                if (!is_dir($target_directory)) {
                    mkdir($target_directory, 0755, true);
                }

                $image_name = 'news_image' . time() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                $target_file = $target_directory . $image_name;

                if (move_uploaded_file($image['tmp_name'], $target_file)) {
                    // تحديث البيانات في قاعدة البيانات
                    $stmt = $conn->prepare("UPDATE news SET title = ?, image = ?, sre = ?, league_id = ? WHERE news_id = ?");
                    $stmt->bind_param("sssii", $title, $image_name, $source, $league_id, $news_id);
                    
                    if ($stmt->execute()) {
                        header('Location: newsPage.php');
                        exit();
                    } else {
                        $error_message = "Error: Could not update the news. Please try again.";
                    }
                } else {
                    $error_message = "Error: Could not upload image.";
                }
            }
        } else {
            // إذا لم يتم تحميل صورة جديدة، نقوم بتحديث البيانات بدون تغيير الصورة
            $stmt = $conn->prepare("UPDATE news SET title = ?, sre = ?, league_id = ? WHERE news_id = ?");
            $stmt->bind_param("ssii", $title, $source, $league_id, $news_id);

            if ($stmt->execute()) {
                header('Location: newsPage.php');
                exit();
            } else {
                $error_message = "Error: Could not update the news. Please try again.";
            }
        }
    }

    if (isset($stmt)) {
        $stmt->close();
    }
} else {
    $error_message = "Invalid or missing News ID.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<body>
  <?php include 'nav.php'; ?>

  <div class="containerPost">
    <section>
      <h1>Edit News</h1>
      <?php if ($error_message) { echo "<p class='error'>$error_message</p>"; } ?>

      <form method="POST" enctype="multipart/form-data">
        <input type="file" name="image" class="inputbox">
        <input type="text" name="title" placeholder="Title (max 100 characters)" value="<?= htmlspecialchars($news['title']) ?>" required class="inputbox" maxlength="100">

        <div>
            <h4>Select League:</h4>
            <?php foreach ($leagues as $league): ?>
                <input type="radio" name="league_id" value="<?= $league['id'] ?>" <?= $league['id'] == $news['league_id'] ? 'checked' : '' ?> required>
                <label><?= $league['name'] ?></label><br>
            <?php endforeach; ?>
        </div>

        <input type="text" name="source" placeholder="Source (max 100 characters)" value="<?= htmlspecialchars($news['sre']) ?>" required class="inputbox" maxlength="100">
        
        <div class="buttons">
          <input type="submit" value="Update News" class="submit-btn">
        </div>
      </form>
    </section>
  </div>

</body>
</html>
