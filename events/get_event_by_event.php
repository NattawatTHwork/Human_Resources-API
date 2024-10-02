<?php
include_once '../include/header.php';
include_once '../vendor/firebase/php-jwt/src/JWT.php';
include_once '../vendor/firebase/php-jwt/src/Key.php';
include_once '../auth/authorization.php';
include_once '../class/events.php';  // Updated to include the Events class

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['event_id'])) {
            $event_id = trim($_GET['event_id']);
            if (!empty($event_id)) {
                $events = new Events();  // Instantiate the Events class
                $event = $events->getEventByEventId($event_id);  // Call the method

                if ($event) {
                    http_response_code(200);
                    echo json_encode(["status" => "success", "data" => $event]);
                } else {
                    http_response_code(404);
                    echo json_encode(["status" => "error", "message" => "Event not found."]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Event ID is empty."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Event ID is missing."]);
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
