<?php

declare(strict_types=1);

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    /**
     * Get translated label
     */
    public function label(): string
    {
        return __("enums.task_status.{$this->value}");
    }

    /**
     * Get all task status labels
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get an array value => label
     */
    public static function options(): array
    {
        return collect(self::cases())
        ->mapWithKeys(fn(self $status) => [
            $status->value => $status->label()
        ])
        ->toArray();
    }
    
    /**
     * Sugested color to UI
     */
    public function color(): string
    {
        return match($this) {
            self::PENDING => 'gray',
            self::IN_PROGRESS => 'blue',
            self::COMPLETED => 'green',
            self::CANCELLED => 'red',
        };
    }
}
