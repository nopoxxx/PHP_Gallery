<?php
$conn = connectSQL();

if (isset($_SESSION['user_id'])) {
    header("Location: ?page=sign-up");
    exit();
}

$errorMessages = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['images'])) {
    $images = $_FILES['images'];

    foreach ($images['name'] as $index => $name) {
        if ($images['error'][$index] === 0) {
            $imageType = $images['type'][$index];
            $imageSize = $images['size'][$index];

            // Проверка типа и размера файла
            if (!in_array($imageType, $ALLOWED_TYPES)) {
                $errorMessages[] = "Недопустимый тип файла: $name";
                continue;
            }

            if ($imageSize > $MAX_FILE_SIZE) {
                $errorMessages[] = "Размер файла превышает допустимый: $name";
                continue;
            }

            // Создание уникального имени файла
            $imageFileType = pathinfo($name, PATHINFO_EXTENSION);
            $newFileName = time() . "_" . rand(1000, 9999) . ".$imageFileType";
            $targetFile = $uploadDir . $newFileName;

            if (move_uploaded_file($images["tmp_name"][$index], $targetFile)) {
                $userId = $_SESSION['user_id'];
                $author = $_SESSION['username'];
                $uploadDate = date("Y-m-d H:i:s");

                $sql = "INSERT INTO images (file_name, author, upload_date, author_id) VALUES ('$newFileName', '$author', '$uploadDate', $userId)";

                if ($conn->query($sql) !== TRUE) {
                    $errorMessages[] = "Ошибка при добавлении файла в базу данных: $name";
                }
            } else {
                $errorMessages[] = "Не удалось загрузить файл: $name";
            }
        } else {
            $errorMessages[] = "Ошибка при загрузке файла: $name";
        }
    }
}

$conn->close();
?>

<div class="upload">
    <h2>Загрузить файлы</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="dropzone" id="dropzone">Перетащите файлы сюда или нажмите для выбора</div>
        <input type="file" name="images[]" id="fileInput" accept="image/*" multiple required>
        <p class="file-name" id="fileName">Файлы не выбраны</p>
        <button type="submit">Загрузить</button>
        <?php if (!empty($errorMessages)): ?>
        <p class="error-msg" id="errorMessages">
            <?php echo implode('<br>', $errorMessages); ?>
        </p>
        <?php endif; ?>
    </form>
</div>

<script>
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');

    dropzone.addEventListener('click', () => {
        fileInput.click();
    });

    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('dragover');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('dragover');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            updateFileName(Array.from(files).map(file => file.name).join(', '));
        }
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            updateFileName(Array.from(fileInput.files).map(file => file.name).join(', '));
        }
    });

    function updateFileName(names) {
        fileName.textContent = `Выбранные файлы: ${names}`;
    }
</script>
