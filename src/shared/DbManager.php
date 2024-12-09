<?php

$GLOBALS['host'] = $host;
$GLOBALS['db'] = $db;
$GLOBALS['user'] = $user;
$GLOBALS['passwd'] = $passwd;

class DbManager
{
    private $conn;

    public function __construct()
    {
        $this->conn = self::make_connection();
    }

    private function make_connection()
    {
        return new PDO("pgsql:host=" . $GLOBALS['host'] . ";dbname=" . $GLOBALS['db'], $GLOBALS['user'], $GLOBALS['passwd']);
    }

    function make_query($query)
    {
        try {
            $stmt = $this->conn->query($query);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            CommunicationUtils::sendResponseInDebug($e->getMessage(), 500);
            return false;
        }
    }

    function make_no_result_query($query)
    {
        try {
            $stmt = $this->conn->query($query);
            return $stmt ? true : false;
        } catch (PDOException $e) {
            CommunicationUtils::sendResponseInDebug($e->getMessage(), 500);
            return false;
        }
    }

    function make_insert_id_query($query)
    {
        try {
            $stmt = $this->conn->query($query);
            if (!$stmt) {
                return false;
            }

            // Get last inserted ID
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            CommunicationUtils::sendResponseInDebug($e->getMessage(), 500);
            return false;
        }
    }

    /**
     * @param mixed $bindParams array of assoc arrays with elemenst 'key': String, 'type': PDO:PARAM and 'value': any
     * @return array|bool
     */
    function make_safe_no_result_query($query, $bindParams)
    {
        $res = self::perform_safe_query($query, $bindParams);
        if (!$res) {
            return $res;
        }

        return $res ? true : false;
    }

    function make_safe_query($query, $bindParams)
    {
        $res = self::perform_safe_query($query, $bindParams);
        if (!$res) {
            return $res;
        }

        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param mixed $bindParams array of assoc arrays with elemenst 'key': String, 'type': PDO:PARAM and 'value': any
     * @return string|bool
     */
    function make_safe_insert_id_query($query, $bindParams)
    {
        $res = self::perform_safe_query($query, $bindParams);
        if (!$res) {
            return $res;
        }

        return $this->conn->lastInsertId();
    }

    private function perform_safe_query($query, $bindParams)
    {
        try {
            $stmt = $this->conn->prepare($query);

            foreach ($bindParams as $key => $value) {
                $stmt->bindValue($value['key'], $value['value'], $value['type']);
            }

            if (!$stmt->execute()) {
                CommunicationUtils::sendResponseInDebug($stmt->errorInfo(), 500);
                return false;
            }

            return $stmt;
        } catch (PDOException $e) {
            CommunicationUtils::sendResponseInDebug($e->getMessage(), 500);
            return false;
        }
    }

    // Transactions

    function begin_transaction()
    {
        $this->conn->beginTransaction();
    }

    function commit_transaction()
    {
        $this->conn->commit();
    }

    function rollback_transaction()
    {
        $this->conn->rollBack();
    }
}
?>