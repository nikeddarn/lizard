<?php
/**
 * Fail conditions to insert vendor product
 */

namespace App\Exceptions\Vendor;


use Exception;
use Throwable;

class DisallowInsertProductException extends Exception
{
    const DEFAULT_MESSAGE = 'Fail conditions to insert vendor product';

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        if (!$message) {
            $this->message = self::DEFAULT_MESSAGE;
        }
    }
}
