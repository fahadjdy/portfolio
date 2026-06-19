<?php

namespace App\Enums;

enum LeadStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case InDiscussion = 'in_discussion';
    case Won = 'won';
    case Lost = 'lost';
    case Spam = 'spam';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Contacted => 'Contacted',
            self::InDiscussion => 'In discussion',
            self::Won => 'Won',
            self::Lost => 'Lost',
            self::Spam => 'Spam',
        };
    }

    /** Tailwind-ish token used for admin badge colors. */
    public function color(): string
    {
        return match ($this) {
            self::New => 'blue',
            self::Contacted => 'amber',
            self::InDiscussion => 'violet',
            self::Won => 'emerald',
            self::Lost => 'rose',
            self::Spam => 'slate',
        };
    }

    /** @return array<int, array{value:string,label:string,color:string}> */
    public static function options(): array
    {
        return array_map(fn (self $c) => [
            'value' => $c->value,
            'label' => $c->label(),
            'color' => $c->color(),
        ], self::cases());
    }
}
