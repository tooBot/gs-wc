<?php

namespace core;
require 'vendor/autoload.php';

?>

<html>
<head>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/normilize.css">
</head>

<body>
    <p hidden id="err-msg"></p>

    <form method="get" action="ajax.php">
        <div class="form-group" style="width: 100px;">
            <input type="text" id="clan-id" name="clan-id" placeholder="ID клана">
        </div>

        <button type="submit" class="btn btn-primary">Получить данные</button>
    </form>

    <p>Сервис не учитывает персонажей, которые провели <b>ВСЕ</b> бои под клановой аурой невидимости, а так же персонажей, которые вступили в заявку, но бой не начался...</p>
</body>

</html>
