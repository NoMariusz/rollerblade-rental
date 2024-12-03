<?php
class DbManager
{
    static function make_query($query)
    {
        $conn = self::make_connection();

        try {
            $stmt = $conn->query($query);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return false;
        } finally {
            $conn = null; // Close connection
        }
    }

    static function make_no_result_query($query)
    {
        $conn = self::make_connection();

        try {
            $stmt = $conn->query($query);
            return $stmt ? true : false;
        } catch (PDOException $e) {
            return false;
        } finally {
            $conn = null; // Close connection
        }
    }

    static function make_insert_id_query($query)
    {
        $conn = self::make_connection();

        try {
            $stmt = $conn->query($query);
            if (!$stmt) {
                return false;
            }

            // Get last inserted ID
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            return false;
        } finally {
            $conn = null; // Close connection
        }
    }

    /**
     * @param mixed $bindParams array of assoc arrays with elemenst 'key': String, 'type': PDO:PARAM and 'value': any
     * @return array|bool
     */
    static function make_safe_query($query, $bindParams)
    {
        $res = self::perform_safe_query($query, $bindParams);
        if (!$res) {
            return $res;
        }

        return $res[1]->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param mixed $bindParams array of assoc arrays with elemenst 'key': String, 'type': PDO:PARAM and 'value': any
     * @return string|bool
     */
    static function make_safe_insert_id_query($query, $bindParams)
    {
        $res = self::perform_safe_query($query, $bindParams);
        if (!$res) {
            return $res;
        }

        return $res[0]->lastInsertId();
    }

    private static function perform_safe_query($query, $bindParams)
    {
        $conn = self::make_connection();

        try {
            $stmt = $conn->prepare($query);

            foreach ($bindParams as $key => $value) {
                $stmt->bindValue($value['key'], $value['value'], $value['type']);
            }

            if (!$stmt->execute()) {
                return false;
            }

            return [$conn, $stmt];
        } catch (PDOException $e) {
            return false;
        } finally {
            $conn = null; // Close connection
        }
    }

    private static function make_connection()
    {
        global $host, $user, $passwd, $db;
        $conn = new PDO("pgsql:host=$host;dbname=$db", $user, $passwd);
        return $conn;
    }
}
?>