<?php
include_once '../connect/db_connect.php';

class Events
{
    private $db;
    private $connection;

    public function __construct()
    {
        $this->db = new DBConnect();
        $this->connection = $this->db->getConnection();
    }

    public function getEventsByPersonalId($personalId)
    {
        $query = 'SELECT main.*, tb_user.*
                  FROM event.tb_event_cloudmate AS main
                  INNER JOIN event.tb_event_cloudmate_user AS tb_user 
                  ON main.event_id = tb_user.event_cloudmate_id
                  WHERE main.personal_id = $1 OR tb_user.personal_id = $2
                  ORDER BY main.event_id DESC';
        
        $result = pg_prepare($this->connection, "get_events_by_personal_id", $query);
        if (!$result) {
            throw new Exception('Failed to prepare SQL query for getting events.');
        }

        $result = pg_execute($this->connection, "get_events_by_personal_id", array($personalId, $personalId));
        if (!$result) {
            throw new Exception('Failed to execute SQL query for getting events.');
        }

        $events = pg_fetch_all($result);
        return $events !== false ? $events : [];
    }

    public function getEventByEventId($eventId)
    {
        $query = 'SELECT main.*, tb_user.* 
                  FROM event.tb_event_cloudmate AS main
                  INNER JOIN event.tb_event_cloudmate_user AS tb_user 
                  ON main.event_id = tb_user.event_cloudmate_id
                  WHERE main.event_id = $1
                  ORDER BY main.event_id DESC';

        $result = pg_prepare($this->connection, "get_event_by_event_id", $query);
        if (!$result) {
            throw new Exception('Failed to prepare SQL query for getting event by event ID.');
        }

        $result = pg_execute($this->connection, "get_event_by_event_id", array($eventId));
        if (!$result) {
            throw new Exception('Failed to execute SQL query for getting event by event ID.');
        }

        $event = pg_fetch_assoc($result);
        return $event !== false ? $event : [];
    }

    public function getEventActivities($eventId, $personalId)
    {
        $query = 'SELECT main.*, log.* 
                  FROM event.tb_event_cloudmate AS main
                  RIGHT JOIN event.tb_event_cloudmate_activity AS log ON main.event_id = log.event_cloudmate_id
                  RIGHT JOIN event.tb_event_cloudmate_user AS tb_user ON main.event_id = tb_user.event_cloudmate_id
                  WHERE main.event_id = $1 AND tb_user.personal_id = $2
                  ORDER BY log.id DESC';

        $result = pg_prepare($this->connection, "get_event_activity_logs", $query);
        if (!$result) {
            throw new Exception('Failed to prepare SQL query for getting event activity logs.');
        }

        $result = pg_execute($this->connection, "get_event_activity_logs", array($eventId, $personalId));
        if (!$result) {
            throw new Exception('Failed to execute SQL query for getting event activity logs.');
        }

        $logs = pg_fetch_all($result);
        return $logs !== false ? $logs : [];
    }
}
?>
