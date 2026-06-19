<?php

namespace App\Enums;

enum TechCategory: string
{
    case Language = 'language';
    case Framework = 'framework';
    case Database = 'database';
    case Frontend = 'frontend';
    case Devops = 'devops';
    case Tool = 'tool';
    case Ai = 'ai';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Ai => 'AI',
            self::Devops => 'DevOps',
            default => ucfirst($this->value),
        };
    }

    /** @return array<int, array{value:string,label:string}> */
    public static function options(): array
    {
        return array_map(fn (self $c) => ['value' => $c->value, 'label' => $c->label()], self::cases());
    }
}
