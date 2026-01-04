<?php
require 'db.php';
function getDatabaseStructureJSON($pdo, $dbName) {
    try {
        $sql = "SELECT
                    TABLE_NAME,
                    COLUMN_NAME,
                    DATA_TYPE,
                    IS_NULLABLE,
                    COLUMN_KEY
                FROM
                    INFORMATION_SCHEMA.COLUMNS
                WHERE
                    TABLE_SCHEMA = :db_name
                ORDER BY
                    TABLE_NAME, ORDINAL_POSITION";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['db_name' => $dbName]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $structure = [];
        foreach ($rows as $row) {
            $tableName = $row['TABLE_NAME'];

            if (!isset($structure[$tableName])) {
                $structure[$tableName] = [];
            }

            $structure[$tableName][] = [
                'column'    => $row['COLUMN_NAME'],
                'type'      => $row['DATA_TYPE'],
                'nullable'  => $row['IS_NULLABLE'],
                'key'       => $row['COLUMN_KEY'],
            ];
        }

        return json_encode($structure, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        return json_encode(['error' => $e->getMessage()]);
    }
}