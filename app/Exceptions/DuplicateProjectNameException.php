<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class DuplicateProjectNameException extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct("Ya tienes un proyecto llamado \"{$name}\".");
    }
}
