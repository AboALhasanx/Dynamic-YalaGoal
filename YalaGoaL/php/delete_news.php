<?php
include 'mysql_con.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['news_id'])) {
        $news_id = $_POST['news_id'];

        $deleteQuery = "DELETE FROM news WHERE news_id = ?";
        
        if ($stmt = $conn->prepare($deleteQuery)) {
            $stmt->bind_param("i", $news_id); 
            if ($stmt->execute()) {
                header('Location: newsPage.php');
                exit();
            } else {
                echo "Error: Could not delete the news post.";
            }
        } else {
            echo "Error: Failed to prepare the SQL query.";
        }
    } else {
        echo "Error: No news ID provided.";
    }

    $conn->close(); 
}
?>
