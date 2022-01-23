<?php

namespace App\Models;

class Ad
{
    private static $tableName = 'ads';
    private static $fillable = [
        'text',
        'price',
        'limit',
        'banner',
    ];

    private $id;
    private $text;
    private $price;
    private $limit;
    private $banner;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->text = $data['text'];
        $this->price = $data['price'];
        $this->limit = $data['limit'];
        $this->banner = $data['banner'];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return self::$tableName;
    }

    /**
     * Get fillable properties
     *
     * @return string[]
     */
    public static function getFillable(): array
    {
        return self::$fillable;
    }
}