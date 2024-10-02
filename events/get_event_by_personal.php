<?php
include_once '../include/header.php';
include_once '../vendor/firebase/php-jwt/src/JWT.php';
include_once '../vendor/firebase/php-jwt/src/Key.php';
include_once '../auth/authorization.php';
include_once '../class/events.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['personal_id'])) {
            $personal_id = trim($_GET['personal_id']);
            if (!empty($personal_id)) {
                $events = new Events(); 
                $eventList = $events->getEventsByPersonalId($personal_id);

                if ($eventList) {
                    http_response_code(200);
                    echo json_encode(["status" => "success", "data" => $eventList]);
                } else {
                    http_response_code(404);
                    echo json_encode(["status" => "error", "message" => "Events not found for this personal ID."]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Personal ID is empty."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Personal ID is missing."]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Method not allowed."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
