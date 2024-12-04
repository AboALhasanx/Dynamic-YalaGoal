<!DOCTYPE html>
<html lang="en">
<?php
$page_title = 'YalaGoaL - News';
include 'header.php';
?>

<body>

  <?php include 'nav.php'; ?>

  <div class="main">
    <?php

    function format_date($datetime)
    {
      $date = new DateTime($datetime);
      return $date->format('Y/m/d'); 
    }

    include 'mysql_con.php';

    $leaguesQuery = "SELECT * FROM leagues";
    $leaguesResult = $conn->query($leaguesQuery);
    $leagues = $leaguesResult->fetch_all(MYSQLI_ASSOC);

    foreach ($leagues as $league): ?>
      <div class="league" id="<?= ($league['id']) ?>">
        <div class="leagueHeader">
          <img src="../<?= ($league['logo']) ?>" alt="<?= ($league['name']) ?>" class="leagueIcon">
          <p class="leagueTitle"><?= ($league['name']) . " ― " . ($league['country']) ?></p>
        </div>
        <div class="newsContainer">
          <?php
          $newsQuery = "SELECT * FROM news WHERE league_id = '" . $league['id'] . "' ORDER BY published_at DESC";
          $newsResult = $conn->query($newsQuery);
          $newsArticles = $newsResult->fetch_all(MYSQLI_ASSOC);

          foreach ($newsArticles as $news): ?>
            <div class="newsCard">
              <img src="../images/news/<?= ($news['image']) ?>">
              <h1><?= ($news['title']) ?></h1>
              <p><?= ($news['sre']) . " | " . format_date($news['published_at']) ?></p>
              
              <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <div class="newsCardBtn">
                <form action="delete_news.php" method="POST" style="display:inline;">
                  <input type="hidden" name="news_id" value="<?= ($news['news_id']) ?>">
                  <button type="submit" class="delete-btn">Delete</button>
                </form>
                <form action="edit_news.php" method="GET" style="display:inline;">
                  <input type="hidden" name="news_id" value="<?= ($news['news_id']) ?>">
                  <button type="submit" class="edit-btn">Edit</button>
                </form>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <?php include 'footer.php'; ?>

</body>

</html>
