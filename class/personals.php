<?php
include_once '../connect/db_connect.php';

class Personals
{
    private $db;
    private $connection;

    public function __construct()
    {
        $this->db = new DBConnect();
        $this->connection = $this->db->getConnection();
    }

    public function getAllPersonals()
    {
        $query = 'SELECT id, mem_fullname FROM personnel.tb_personal ORDER BY id ASC';
        $result = pg_prepare($this->connection, "get_all_personals", $query);
        if (!$result) {
            throw new Exception('Failed to prepare SQL query for getting all personnel.');
        }
        $result = pg_execute($this->connection, "get_all_personals", []);
        if (!$result) {
            throw new Exception('Failed to execute SQL query for getting all personnel.');
        }
        $personals = pg_fetch_all($result);
        if ($personals === false) {
            return [];
        }
        return $personals;
    }
}
