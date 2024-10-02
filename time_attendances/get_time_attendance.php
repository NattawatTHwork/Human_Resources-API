<?php
include_once '../include/header.php';
include_once '../vendor/firebase/php-jwt/src/JWT.php';
include_once '../vendor/firebase/php-jwt/src/Key.php';
include_once '../auth/authorization.php';
include_once '../class/time_attendances.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Check if personalId, month, and year parameters are provided
        if (isset($_GET['id'], $_GET['month'], $_GET['year'])) {
            $id = trim($_GET['id']);
            $month = trim($_GET['month']);
            $year = trim($_GET['year']);

            // Validate if the parameters are not empty
            if (!empty($id) && !empty($month) && !empty($year)) {
                $timeAttendances = new TimeAttendances();
                $attendanceData = $timeAttendances->getTimeAttendance($id, $month, $year);

                // Check if attendance data was found
                if ($attendanceData) {
                    http_response_code(200);
                    echo json_encode(["status" => "success", "data" => $attendanceData]);
                } else {
                    http_response_code(404);
                    echo json_encode(["status" => "error", "message" => "No attendance data found."]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "One or more parameters are empty."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Missing required parameters."]);
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
