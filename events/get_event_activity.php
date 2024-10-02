<?php
include_once '../include/header.php';
include_once '../vendor/firebase/php-jwt/src/JWT.php';
include_once '../vendor/firebase/php-jwt/src/Key.php';
include_once '../auth/authorization.php';
include_once '../class/events.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['event_id']) && isset($_GET['personal_id'])) {
            $event_id = trim($_GET['event_id']);
            $personal_id = trim($_GET['personal_id']);

            if (!empty($event_id) && !empty($personal_id)) {
                $events = new Events();
                $eventDetails = $events->getEventActivities($event_id, $personal_id);

                if ($eventDetails) {
                    http_response_code(200);
                    echo json_encode(["status" => "success", "data" => $eventDetails]);
                } else {
                    http_response_code(404);
                    echo json_encode(["status" => "error", "message" => "Event details not found."]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Event ID or Personal ID is empty."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Event ID or Personal ID is missing."]);
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
