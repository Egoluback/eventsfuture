<!DOCTYPE HTML>
<html>
  <head>
    <title>Events in future</title>
    <script src = "vue.js"></script>
    <link href = "https://fonts.googleapis.com/css?family=Oswald" rel = "stylesheet">
    <link href = "css/form.css" rel = "stylesheet">
  </head>
  <body>
    <div id = "app">
        <form method = "GET">
            <input type = "text" name = "value" placeholder = "Что произойдет?">
            <input type = "number" name = "year" placeholder = "Год события.">
            <input type = "submit" value = "Сохранить">
        </form>
        <a href = "index.php">Вернуться на главную страницу</a>
    </div>
    <?php
        require('config.php');
        if (isset($_GET['year']) and isset($_GET['value'])){
            $year = $_GET['year'];
            $value = $_GET['value'];

            $query = "INSERT INTO events (year, value) VALUES ($year, '$value')";
            $result = mysqli_query($mysqli, $query);
            echo "<script>window.location = 'addForm.php'; </script>";
        }

    ?>
  </body>
</html>