<?php

$conn = connectSQL();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_comment'])) {
    $commentId = $_POST['comment_id'];
    $userId = $_SESSION['user_id'];

    $checkSql = "SELECT * FROM comments WHERE id = $commentId AND user_id = $userId";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        $deleteSql = "DELETE FROM comments WHERE id = $commentId";
        if ($conn->query($deleteSql) === TRUE) {
            echo "";
        } else {
            echo "";
        }
    } else {
        echo "";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_image'])) {
    $imageId = $_POST['image_id'];
    $userId = $_SESSION['user_id'];

    $checkImageSql = "SELECT * FROM images WHERE id = $imageId AND author = (SELECT username FROM users WHERE id = $userId)";
    $checkImageResult = $conn->query($checkImageSql);

    if ($checkImageResult->num_rows > 0) {
        $deleteCommentsSql = "DELETE FROM comments WHERE image_id = $imageId";
        $conn->query($deleteCommentsSql);

        $deleteImageSql = "DELETE FROM images WHERE id = $imageId";
        if ($conn->query($deleteImageSql) === TRUE) {
            echo "";
        } else {
            echo "";
        }
    } else {
        echo "";
    }
}

$sql = "SELECT * FROM images ORDER BY upload_date DESC";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_comment'])) {
    $comment = $_POST['comment'];
    $imageId = $_POST['image_id'];
    $userId = $_SESSION['user_id'];

    $commentSql = "INSERT INTO comments (image_id, user_id, comment) VALUES ($imageId, $userId, '$comment')";
    $conn->query($commentSql);
}
?>

<ul class="gallery">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $filePath = $uploadDir . $row['file_name'];
            $author = $row['author'];
            $uploadDate = $row['upload_date'];
            $lightboxId = "img" . $row['id'];

            echo "<li class='gallery-item'>
                    <a href='#$lightboxId'>
                        <img src='$filePath' alt='Image'>
                    </a>
                  </li>";

            echo "<div id='$lightboxId' class='lightbox'>
                    <a href='#' class='lightbox-overlay'></a>
                    <div class='lightbox-content'>
                        <img src='$filePath' alt='Image'>
                        <div class='lightbox-right'>
                            <div class='lightbox-description'>
                                <p><strong>Автор:</strong> $author</p>
                                <p><strong>Дата:</strong> $uploadDate</p>";

            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['author_id']) {
                echo "<form action='' method='POST' style='display: inline;'>
                        <input type='hidden' name='image_id' value='" . $row['id'] . "'>
                        <button type='submit' class='delete' name='delete_image'>Удалить картинку</button>
                      </form>";
            }
            echo "</div>";

            echo "<div class='comments'>";
            $imageId = $row['id'];
            $commentSql = "SELECT comments.id AS comment_id, comments.comment, users.username, comments.created_at, comments.user_id 
                           FROM comments
                           JOIN users ON comments.user_id = users.id
                           WHERE comments.image_id = $imageId
                           ORDER BY comments.created_at DESC";
            $commentResult = $conn->query($commentSql);

            if ($commentResult->num_rows > 0) {
                while ($commentRow = $commentResult->fetch_assoc()) {
                    echo "<div class='comment'>
                            <p class='comment-author'>" . $commentRow['username'] . "</p>
                            <p class='comment-date'>" . $commentRow['created_at'] . "</p>
                            <p class='comment-text'>" . $commentRow['comment'] . "</p>";

                    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $commentRow['user_id']) {
                        echo "<form action='' method='POST' style='display: inline;'>
                                <input type='hidden' name='comment_id' value='" . $commentRow['comment_id'] . "'>
                                <button type='submit' class='delete' name='delete_comment'>Удалить</button>
                              </form>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<p>Комментариев нет.</p>";
            }
            echo "</div>";

            if (isset($_SESSION['user_id'])) {
                echo "<form action='' method='POST'>
                        <textarea class='comment-textarea' name='comment' placeholder='Добавьте комментарий...' required></textarea>
                        <input type='hidden' name='image_id' value='" . $row['id'] . "'>
                        <button type='submit' name='submit_comment'>Отправить</button>
                      </form>";
            } else {
                echo "<p>Для добавления комментариев нужно войти.</p>";
            }

            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>Нет изображений.</p>";
    }
    ?>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a class="upload-btn" href="?page=upload">Загрузить</a>
    <?php endif; ?>
</ul>
<?php $conn->close(); ?>
