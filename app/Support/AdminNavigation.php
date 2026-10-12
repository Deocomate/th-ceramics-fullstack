<?php

namespace App\Support;

use Illuminate\Http\Request;

class AdminNavigation
{
    /**
     * Resolve the configured navigation tree for one request.
     *
     * @param  array<string, int>  $badges  counters keyed by item key
     * @param  array<string, string|null>  $hints  short notes keyed by item key
     * @return array{modules: list<array<string, mixed>>, current: array<string, mixed>, trail: list<string>}
     */
    public static function resolve(Request $request, bool $isSuperAdmin, array $badges = [], array $hints = []): array
    {
        $modules = [];
        $current = null;
        $trail = [];

        foreach ((array) config('admin_navigation', []) as $module) {
            $items = self::items($module['items'] ?? [], $request, $isSuperAdmin, $badges, $hints);
            $links = self::links($items);
            if ($links === []) {
                continue;
            }

            $module['items'] = $items;
            $module['url'] = route($module['route']);
            $module['badge'] = array_sum(array_column($links, 'badge'));
            $module['active'] = $current === null && in_array(true, array_column($links, 'matches'), true);

            if ($module['active']) {
                $current = $module;
                $trail = self::trail($module);
            }

            $modules[] = $module;
        }

        return ['modules' => $modules, 'current' => $current ?? $modules[0], 'trail' => $trail];
    }

    /**
     * @param  list<array<string, mixed>>  $nodes
     * @return list<array<string, mixed>>
     */
    private static function items(array $nodes, Request $request, bool $isSuperAdmin, array $badges, array $hints): array
    {
        $items = [];

        foreach ($nodes as $node) {
            if (isset($node['heading'])) {
                $items[] = ['type' => 'heading', 'label' => $node['heading']];

                continue;
            }

            if (isset($node['children'])) {
                $children = self::items($node['children'], $request, $isSuperAdmin, $badges, $hints);
                if ($children !== []) {
                    $matches = array_column($children, 'matches');
                    $items[] = ['type' => 'group', 'children' => $children, 'open' => in_array(true, $matches, true)] + $node;
                }

                continue;
            }

            if (($node['superadmin'] ?? false) && ! $isSuperAdmin) {
                continue;
            }

            $params = $node['params'] ?? [];
            $matches = $request->routeIs(...$node['active']);
            $key = $node['key'] ?? null;

            $items[] = [
                'type' => 'link',
                'url' => route($node['route'], $params),
                'matches' => $matches,
                'active' => $matches && array_intersect_key($request->query(), $params) == $params,
                'badge' => (int) ($badges[$key] ?? 0),
                'hint' => $hints[$key] ?? null,
            ] + $node;
        }

        return self::withoutEmptyHeadings($items);
    }

    /**
     * A heading is kept only while a link or group still follows it.
     *
     * @param  list<array<string, mixed>>  $items
     * @return list<array<string, mixed>>
     */
    private static function withoutEmptyHeadings(array $items): array
    {
        $kept = [];

        foreach ($items as $index => $item) {
            $next = $items[$index + 1]['type'] ?? 'heading';
            if ($item['type'] !== 'heading' || $next !== 'heading') {
                $kept[] = $item;
            }
        }

        return $kept;
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return list<array<string, mixed>>
     */
    private static function links(array $items): array
    {
        $links = [];

        foreach ($items as $item) {
            if ($item['type'] === 'group') {
                array_push($links, ...self::links($item['children']));
            } elseif ($item['type'] === 'link') {
                $links[] = $item;
            }
        }

        return $links;
    }

    /**
     * @param  array<string, mixed>  $module
     * @return list<string>
     */
    private static function trail(array $module): array
    {
        foreach ($module['items'] as $item) {
            if ($item['type'] === 'link' && $item['active']) {
                return array_values(array_unique([$module['label'], $item['label']]));
            }

            if ($item['type'] === 'group' && $item['open']) {
                $trail = [$module['label'], $item['label']];
                $section = null;

                foreach ($item['children'] as $child) {
                    if ($child['type'] === 'heading') {
                        $section = $child['label'];
                    } elseif ($child['active']) {
                        array_push($trail, ...array_filter([$section, $child['label']]));
                        break;
                    }
                }

                return $trail;
            }
        }

        return [$module['label']];
    }
}
