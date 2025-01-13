<?php
date_default_timezone_set('Asia/Tehran');
require_once '../config.php';
require_once '../botapi.php';
require_once '../panels.php';
require_once '../functions.php';
$ManagePanel = new ManagePanel();
$stmt = $pdo->prepare("SELECT * FROM invoice WHERE status = 'active' AND name_product = 'usertest'");
$stmt->execute();
while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $resultt  = trim($result['username']);
    $result = $result;
    $marzban_list_get = select("marzban_panel","*","name_panel",$result['Service_location'],"select");
    $get_username_Check = $ManagePanel->DataUser($result['Service_location'],$result['username']);
    if (!in_array($get_username_Check['status'],['active','on_hold','Unsuccessful','disabled'])) {
        $ManagePanel->RemoveUser($result['Service_location'],$resultt);
        update("invoice","status","disabled","username",$resultt);
        $Response = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => "🛍 Купить сервис", 'callback_data' => 'buy'],
                ],
            ]
        ]);
        $textexpire = "Здравствуйте, уважаемый пользователь.
    Ваш тестовый сервис с именем пользователя $resultt завершен.
    Надеемся, что у вас был хороший опыт использования нашего сервиса. Если вы остались довольны тестовым сервисом, вы можете приобрести собственный сервис и наслаждаться свободным интернетом с максимальным качеством 😉🔥
    🛍 Чтобы приобрести качественный сервис, вы можете использовать кнопку ниже.";
        sendmessage($result['id_user'], $textexpire, $Response, 'HTML');
        }
}