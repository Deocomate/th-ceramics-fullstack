<?php

namespace App\Domains\Content\Domain;

final class HomePageNumbers
{
    /**
     * @param  array<int|string, mixed>  $raw
     * @return array<int, array{head: string, body: string}>
     */
    public static function sanitize(array $raw): array
    {
        $result = [];

        foreach ($raw as $item) {
            if (! is_array($item)) {
                continue;
            }

            $head = isset($item['head']) && is_string($item['head']) ? trim($item['head']) : '';
            $body = isset($item['body']) && is_string($item['body']) ? trim($item['body']) : '';

            if ($head !== '' || $body !== '') {
                $result[] = [
                    'head' => $head,
                    'body' => $body,
                ];
            }
        }

        return $result;
    }
}
