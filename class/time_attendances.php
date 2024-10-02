<?php
include_once '../connect/db_connect.php';

class TimeAttendances
{
    private $db;
    private $connection;

    public function __construct()
    {
        $this->db = new DBConnect();
        $this->connection = $this->db->getConnection();
    }

    public function getTimeAttendance($personalId, $month, $year)
    {
        $query = 'SELECT 
                    DATE(time_stamp) AS date,
                    MIN(CASE WHEN time_flag = 1 THEN time_stamp END) AS time_in,
                    MAX(CASE WHEN time_flag = 2 THEN time_stamp END) AS time_out
                  FROM 
                    time_attendance.tb_time_attendance 
                  WHERE 
                    personal_id = $1 
                    AND DATE_PART(\'month\', time_stamp) = $2 
                    AND DATE_PART(\'year\', time_stamp) = $3 
                  GROUP BY 
                    personal_id, 
                    DATE(time_stamp) 
                  ORDER BY 
                    date, 
                    time_in';
        
        $result = pg_prepare($this->connection, "get_time_attendance", $query);
        if (!$result) {
            throw new Exception('Failed to prepare SQL query for getting time attendance.');
        }
        $result = pg_execute($this->connection, "get_time_attendance", array($personalId, $month, $year));
        if (!$result) {
            throw new Exception('Failed to execute SQL query for getting time attendance.');
        }
        $attendanceData = pg_fetch_all($result);
        if ($attendanceData === false) {
            return [];
        }
        return $attendanceData;
    }
}
