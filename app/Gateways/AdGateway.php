<?php

namespace App\Gateways;

use App\Models\Ad;
use Database\DbConnection;
use PDO;
use PDOException;

class AdGateway
{
    private $db;
    private $tableName;
    private $fillableProperties;

    public function __construct()
    {
        $this->db = (new DbConnection())->get();
        $this->tableName = Ad::getTableName();
        $this->fillableProperties = Ad::getFillable();
    }

    /**
     * @param int $id
     * @return Ad
     */
    public function getById(int $id): Ad
    {
        $statement = "SELECT * FROM $this->tableName WHERE `id` = :id";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            return new Ad($statement->fetchAll()[0]);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * Create model with the given attributes
     *
     * @param array $data
     * @return Ad
     */
    public function create(array $data): Ad
    {
        $properties = '`' . implode("`,`", $this->fillableProperties) . '`';
        $values = ':' . implode(", :", $this->fillableProperties);

        $statement = "INSERT INTO $this->tableName ($properties) VALUES ($values)";

        try {
            $statement = $this->db->prepare($statement);
            foreach ($this->fillableProperties as $fillableProperty) {
                $statement->bindValue(":$fillableProperty", $data[$fillableProperty] ?? null);
            }
            $statement->execute();
            $id = $this->db->lastInsertId();

            return $this->getById($id);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * Update model with the given attributes
     *
     * @param int $id
     * @param array $data
     * @return Ad
     */
    public function update(int $id, array $data): Ad
    {
        $statement = "UPDATE $this->tableName SET ";

        $lastFillableKey = count(($this->fillableProperties)) - 1;
        foreach ($this->fillableProperties as $key => $fillableProperty) {
            $statement .= "`$fillableProperty` = :$fillableProperty";
            if ($key != $lastFillableKey) {
                $statement .= ",";
            }
        }

        $statement .= " WHERE `id` = :id";

        try {
            $statement = $this->db->prepare($statement);

            $statement->bindValue(":id", $id);
            foreach ($this->fillableProperties as $fillableProperty) {
                $statement->bindValue(":$fillableProperty", $data[$fillableProperty] ?? null);
            }
            $statement->execute();

            return $this->getById($id);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * @return Ad
     */
    public function getRelevant(): Ad
    {
        $statement = "SELECT `id`, `text`, `banner` FROM $this->tableName
            WHERE `limit` > 0 
            ORDER BY `price` desc LIMIT 1";

        try {
            $statement = $this->db->query($statement);

            return new Ad($statement->fetchAll()[0]);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * Decrease impressions limit
     *
     * @param int $id
     */
    public function decreaseLimit(int $id): void
    {
        $statement = "
            UPDATE $this->tableName 
            SET `limit` = `limit` - 1
            WHERE `id` = :id AND `limit` > 0";
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindValue(':id', $id);
            $statement->execute();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}
