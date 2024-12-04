<nav>
  <div class="logo">
    <img src="../images/global/mainLogo.svg" alt="Logo">
  </div>
  <ul>
    <li><a href="index.php" class="<?=basename($_SERVER['PHP_SELF'])=='index.php'?'active':''?>">Home</a></li>
    <li><a href="newsPage.php" class="<?=basename($_SERVER['PHP_SELF'])=='newsPage.php'?'active':''?>">News</a></li>
    <li><a href="aboutUs.php" class="<?= basename($_SERVER['PHP_SELF']) == 'aboutUs.php' ? 'active' : '' ?>">About</a></li>
    <li>
    <a href="account.php" class="<?= in_array(basename($_SERVER['PHP_SELF']), ['account.php','registerPage.php']) ? 'active' : '' ?>">Account</a>
    </a>
    </li>
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
      <li><a href="post.php" class="<?= basename($_SERVER['PHP_SELF']) == 'post.php' ? 'active' : '' ?>">Post</a></li>
    <?php endif; ?>
  </ul>
</nav>
