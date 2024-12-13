<?php

if (isset($_GET['Submit'])) {
    // Подключение к базе данных
    $connection = $GLOBALS["___mysqli_ston"];

    // Получение ввода от пользователя
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

    // Подготовленный SQL-запрос для защиты от инъекций
    $stmt = $connection->prepare("SELECT first_name, last_name FROM users WHERE user_id = ?");
    
    if ($stmt) {
        // Привязываем параметр (в данном случае ожидаем строку)
        $stmt->bind_param("s", $id);

        // Выполняем запрос
        $stmt->execute();

        // Получаем результат
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Если пользователь найден
            $html .= '<pre>User ID exists in the database.</pre>';
        } else {
            // Если пользователь не найден
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
            $html .= '<pre>User ID is MISSING from the database.</pre>';
        }

        // Закрываем подготовленный запрос
        $stmt->close();
    } else {
        // Обработка ошибки при подготовке запроса
        $html .= '<pre>Database query error.</pre>';
    }

    // Закрываем соединение с базой данных
    ((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
}
