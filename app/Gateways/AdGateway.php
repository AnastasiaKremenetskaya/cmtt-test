<?php

namespace App\Gateways;

use App\Exceptions\Gateway\RecordNotFoundException;
use App\Exceptions\InternalServerException;
use App\Models\Ad;
use Database\DbConnection;
use Exception;
use PDO;
use PDOException;

class AdGateway
{
    /**
     * @var PDO
     */
    private $db;

    /**
     * @var string
     *
     * @psalm-var 'ads'
     */
    private $tableName = 'ads';

    /**
     * @var string[]
     *
     * @psalm-var array{0: 'text', 1: 'price', 2: 'limit', 3: 'banner'}
     */
    private $fillable = [
        'text',
        'price',
        'limit',
        'banner',
    ];

    public function __construct()
    {
        $this->db = (new DbConnection())->get();
    }

    /**
     * Get model by id
     *
     * @param int $id
     * @return Ad
     * @throws InternalServerException
     * @throws RecordNotFoundException
     */
    public function getById(int $id): Ad
    {
        $statement = "SELECT * FROM $this->tableName WHERE `id` = :id";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            $adData = $statement->fetchAll()[0];
            if (is_null($adData)) {
                throw new RecordNotFoundException(['id' => $id]);
            }
            return new Ad($adData);
        } catch (PDOException $e) {
            throw new InternalServerException();
        }
    }

    /**
     * Create model with the given attributes
     *
     * @param array $data
     * @return Ad
     * @throws InternalServerException
     */
    public function create(array $data): Ad
    {
        $properties = '`' . implode("`,`", $this->fillable) . '`';
        $values = ':' . implode(", :", $this->fillable);

        $statement = "INSERT INTO $this->tableName ($properties) VALUES ($values)";

        try {
            $statement = $this->db->prepare($statement);
            foreach ($this->fillable as $fillableProperty) {
                $statement->bindValue(":$fillableProperty", $data[$fillableProperty] ?? null);
            }
            $statement->execute();
            $id = $this->db->lastInsertId();

            return $this->getById($id);
        } catch (Exception $e) {
            throw new InternalServerException();
        }
    }

    /**
     * Update model with the given attributes
     *
     * @param int $id
     * @param array $data
     * @return Ad
     * @throws InternalServerException
     */
    public function update(int $id, array $data): Ad
    {
        $statement = "UPDATE $this->tableName SET ";

        $lastFillableKey = count(($this->fillable)) - 1;
        foreach ($this->fillable as $key => $fillableProperty) {
            $statement .= "`$fillableProperty` = :$fillableProperty";
            if ($key != $lastFillableKey) {
                $statement .= ",";
            }
        }

        $statement .= " WHERE `id` = :id";

        try {
            $statement = $this->db->prepare($statement);

            $statement->bindValue(":id", $id);
            foreach ($this->fillable as $fillableProperty) {
                $statement->bindValue(":$fillableProperty", $data[$fillableProperty] ?? null);
            }
            $statement->execute();

            return $this->getById($id);
        } catch (Exception $e) {
            throw new InternalServerException();
        }
    }

    /**
     * Get most relevant ad
     *
     * @return Ad
     * @throws InternalServerException
     * @throws RecordNotFoundException
     */
    public function getRelevant(): Ad
    {
        $statement = "SELECT `id`, `text`, `banner` FROM $this->tableName
            WHERE `limit` > 0 
            ORDER BY `price` desc LIMIT 1";

        try {
            $statement = $this->db->query($statement);
            $adData = $statement->fetchAll()[0];

            if (is_null($adData)) {
                throw new RecordNotFoundException(['status' => 'relevant']);
            }
            return new Ad($statement->fetchAll()[0]);
        } catch (PDOException $e) {
            throw new InternalServerException();
        }
    }

    /**
     * Decrease impressions limit
     *
     * @param int $id
     * @return Ad
     * @throws InternalServerException
     */
    public function decreaseLimit(int $id): Ad
    {
        $statement = "
            UPDATE $this->tableName 
            SET `limit` = `limit` - 1
            WHERE `id` = :id AND `limit` > 0";
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindValue(':id', $id);
            $statement->execute();

            return new Ad($statement->fetchAll()[0]);
        } catch (Exception $e) {
            throw new InternalServerException();
        }
    }
}
