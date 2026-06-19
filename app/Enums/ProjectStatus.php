<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Draft = 'draft';
    case Published = 'published';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    /** @return array<int, array{value:string,label:string}> */
    public static function options(): array
    {
        return array_map(fn (self $c) => ['value' => $c->value, 'label' => $c->label()], self::cases());
    }
}
