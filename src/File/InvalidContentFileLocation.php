<?php

declare(strict_types=1);

namespace Skimpy\File;

class InvalidContentFileLocation extends \RuntimeException
{
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        $defaultMessage = "Content files must be in a directory or subdirectory of a folder named 'pages'";
        $message = is_null($message) ? $defaultMessage : $message;
        parent::__construct($message, $code, $previous);
    }
}
