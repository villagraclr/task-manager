<?php

declare(strict_types=1);

namespace App\Modules\Project\Domain\Exceptions;

use Exception;

class CannotAddOwnerAsMemberException extends Exception
{
    public function __construct()
    {
        parent::__construct('El dueño del proyecto ya tiene acceso completo; no necesita agregarse como miembro.');
    }
}
