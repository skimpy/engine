<?php

declare(strict_types=1);

namespace Skimpy\File\Transformer;

class TransformationFailure extends \RuntimeException
{
    public function __construct(
        $message = '',
        $code = 0,
        \Exception $previous = null,
        $filePath = null
    ) {
        $message .= $filePath ? PHP_EOL."File: $filePath" : '';
        parent::__construct($message, $code, $previous);
    }
}
