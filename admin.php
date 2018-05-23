<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Загрузка файла с тестом</title>
</head>
<body>

<?php
include 'menu.php';
if (isset($_POST['upload'])) { //Если форма отправлена
    if (isset($_FILES['test'])) { //Файл передан
        $uploaddir = 'uploads/';
        $uploadfile = $uploaddir . basename($_FILES['test']['name']);
        if (substr($_FILES['test']['name'], -4)!='json') {
            die('Неверный тип файла');
        }
        if ($_FILES['test']['size']>2097152) {
            die('Слишком большой файл, максимальный размер 2 МБ');
        }
        $jsonData = file_get_contents($_FILES['test']['tmp_name']);
        if (!$jsonData) {
            die('Не удалось загрузить файл');
        }
        $test = json_decode($jsonData);
        if ((!$test)||($test==NULL)){
            die('Неверный формат json файла');
        }
        //проверка структуры файла
        foreach ($test as $qId => $question) {
            if ((!isset($question->text))||(!isset($question->options[0]))||(!isset($question->answers[0]))) {
                die('Неверная структура файла с тестом');
            }
        }
        if (move_uploaded_file($_FILES['test']['tmp_name'], $uploadfile)) { //Удалось загрузить файл
            echo '<b>Файл был успешно загружен.</b>';
            echo '</body></html>';
            exit();
        } else {
            die ('Ошибка загрузки');
        }
    } else {
        die ('Файл не получен');
    }
}
?>

<h1>Загрузка файла с тестом</h1>
<form  enctype="multipart/form-data" method="post" action="admin.php">
    <p>Файл с тестом <input type="file" name="test" accept=".json"></p>
    <input type="submit" name="upload" value="Загрузить">
</form>
</body>
</html>