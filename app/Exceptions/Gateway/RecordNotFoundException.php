<?php

namespace App\Exceptions\Gateway;

class RecordNotFoundException extends GenericException
{
    public function __construct($data)
    {
        if (is_array($data)) {
            $dataAttributes = array_map(function($value, $key) {
                return $key.' = '.$value;
            }, array_values($data), array_keys($data));

            $params = implode(' ', $dataAttributes);

            $message = "Record where $params not found.";
        } else {
            $message = "Record not found.";
        }

        parent::__construct($message);
    }
}
