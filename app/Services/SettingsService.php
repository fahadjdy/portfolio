<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

/**
 * Cached key-value settings store. The full set is cached forever and flushed
 * on any write, so reads on public pages never hit the database after warm-up.
 */
class SettingsService
{
    public const CACHE_KEY = 'settings.all';

    protected ?array $items = null;

    /** @return array<string, mixed> key => cast value */
    public function all(): array
    {
        return $this->items ??= Cache::rememberForever(self::CACHE_KEY, function () {
            return Setting::query()->get()
                ->mapWithKeys(fn (Setting $s) => [$s->key => $this->castValue($s->value, $s->type)])
                ->all();
        });
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->all(), $key, $default);
    }

    public function has(string $key): bool
    {
        return Arr::has($this->all(), $key);
    }

    /** Settings rows (key => value) for a single group — used by the admin forms. */
    public function group(string $group): array
    {
        return Setting::query()->where('group', $group)->get()
            ->mapWithKeys(fn (Setting $s) => [$s->key => $this->castValue($s->value, $s->type)])
            ->all();
    }

    public function set(string $key, mixed $value, string $type = 'string', string $group = 'general'): void
    {
        Setting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $this->prepareValue($value, $type), 'type' => $type, 'group' => $group],
        );

        $this->flush();
    }

    /** @param array<string, mixed> $values key => value (type inferred unless given in $types) */
    public function setMany(array $values, string $group = 'general', array $types = []): void
    {
        foreach ($values as $key => $value) {
            $type = $types[$key] ?? $this->inferType($value);
            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $this->prepareValue($value, $type), 'type' => $type, 'group' => $group],
            );
        }

        $this->flush();
    }

    public function flush(): void
    {
        $this->items = null;
        Cache::forget(self::CACHE_KEY);
    }

    protected function castValue(?string $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => $value === null ? null : (int) $value,
            'json' => $value === null ? null : json_decode($value, true),
            default => $value,
        };
    }

    protected function prepareValue(mixed $value, string $type): ?string
    {
        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'json' => json_encode($value),
            default => $value === null ? null : (string) $value,
        };
    }

    protected function inferType(mixed $value): string
    {
        return match (true) {
            is_bool($value) => 'boolean',
            is_int($value) => 'integer',
            is_array($value) => 'json',
            default => 'string',
        };
    }
}
