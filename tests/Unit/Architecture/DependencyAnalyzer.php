<?php

namespace Tests\Unit\Architecture;

class DependencyAnalyzer
{
    private const DISALLOWED_HELPERS = [
        'app',
        'resolve',
        'route',
        'request',
        'response',
        'config',
        'view',
        'abort',
        'session',
        'auth',
        'cache',
        'redirect',
        'logger',
        'dispatch',
        'validator',
        'event',
    ];

    /**
     * Analyze a PHP file or code snippet for architecture dependency violations.
     *
     * @return array<int, array{file: string, line: int, type: string, message: string}>
     */
    public static function analyzeFile(string $filePath): array
    {
        $code = file_get_contents($filePath);

        return self::analyzeCode($code, $filePath);
    }

    /**
     * Analyze PHP code for architecture dependency violations.
     *
     * @return array<int, array{file: string, line: int, type: string, message: string}>
     */
    public static function analyzeCode(string $code, string $filePath = 'in-memory'): array
    {
        $tokens = token_get_all($code);
        $violations = [];

        // 1. Identify namespace and layer
        $namespace = self::extractNamespace($tokens);
        $context = self::extractContext($namespace);
        $layer = self::extractLayer($namespace);

        // If not in Domain or Application layer, skip architecture boundary enforcement
        if (! $layer) {
            return [];
        }

        // 2. Scan tokens for imports, inline references, and forbidden helper calls
        $count = count($tokens);
        for ($i = 0; $i < $count; $i++) {
            $token = $tokens[$i];

            // Check imports: `use ...;`
            if ($token[0] === T_USE) {
                $importInfo = self::extractUseStatement($tokens, $i);
                if ($importInfo) {
                    $usePath = $importInfo['path'];
                    $line = $importInfo['line'];

                    // Check violations based on layer
                    if ($layer === 'Domain') {
                        // Domain cannot import Illuminate or Laravel
                        if (str_starts_with($usePath, 'Illuminate\\') || str_starts_with($usePath, 'Laravel\\')) {
                            $violations[] = [
                                'file' => $filePath,
                                'line' => $line,
                                'type' => 'LARAVEL_DEPENDENCY',
                                'message' => "Domain layer must not depend on Laravel framework [{$usePath}].",
                            ];
                        }

                        // Domain cannot import Http or Infrastructure
                        if (str_contains($usePath, '\\Http\\') || str_contains($usePath, '\\Infrastructure\\') || str_starts_with($usePath, 'App\\Http\\') || str_starts_with($usePath, 'App\\Models\\')) {
                            $violations[] = [
                                'file' => $filePath,
                                'line' => $line,
                                'type' => 'OUTER_LAYER_DEPENDENCY',
                                'message' => "Domain layer must not depend on outer Http or Infrastructure layers [{$usePath}].",
                            ];
                        }

                        // Domain cannot import another Bounded Context
                        if ($context && (str_starts_with($usePath, 'App\\Domains\\') || str_starts_with($usePath, 'Tests\\Unit\\Architecture\\Fixtures\\Domains\\'))) {
                            $parts = explode('\\', $usePath);
                            $importedContext = str_starts_with($usePath, 'Tests\\') ? ($parts[4] ?? '') : ($parts[2] ?? '');
                            if ($importedContext && $importedContext !== $context) {
                                $violations[] = [
                                    'file' => $filePath,
                                    'line' => $line,
                                    'type' => 'CROSS_CONTEXT_DEPENDENCY',
                                    'message' => "Domain layer of [{$context}] must not depend directly on other domain [{$importedContext}].",
                                ];
                            }
                        }
                    } elseif ($layer === 'Application') {
                        // Application cannot import Http or Infrastructure of any domain
                        if (str_contains($usePath, '\\Http\\') || str_contains($usePath, '\\Infrastructure\\') || str_starts_with($usePath, 'App\\Http\\') || str_starts_with($usePath, 'App\\Models\\')) {
                            $violations[] = [
                                'file' => $filePath,
                                'line' => $line,
                                'type' => 'OUTER_LAYER_DEPENDENCY',
                                'message' => "Application layer must not depend on Http or Infrastructure [{$usePath}].",
                            ];
                        }

                        // Application cannot import Application or Domain of another Context directly
                        if ($context && (str_starts_with($usePath, 'App\\Domains\\') || str_starts_with($usePath, 'Tests\\Unit\\Architecture\\Fixtures\\Domains\\'))) {
                            $parts = explode('\\', $usePath);
                            $importedContext = str_starts_with($usePath, 'Tests\\') ? ($parts[4] ?? '') : ($parts[2] ?? '');
                            if ($importedContext && $importedContext !== $context) {
                                $violations[] = [
                                    'file' => $filePath,
                                    'line' => $line,
                                    'type' => 'CROSS_CONTEXT_DEPENDENCY',
                                    'message' => "Application layer of [{$context}] must not import other context [{$importedContext}] directly. Use explicit ports.",
                                ];
                            }
                        }
                    }
                }
            }

            // Check forbidden global helper function calls in Domain and Application
            if (is_array($token) && $token[0] === T_STRING && in_array(strtolower($token[1]), self::DISALLOWED_HELPERS, true)) {
                $funcName = strtolower($token[1]);
                $line = $token[2];

                // Verify it's a function call: followed by `(` (ignoring whitespace)
                $nextIdx = $i + 1;
                while ($nextIdx < $count && is_array($tokens[$nextIdx]) && $tokens[$nextIdx][0] === T_WHITESPACE) {
                    $nextIdx++;
                }

                if ($nextIdx < $count && $tokens[$nextIdx] === '(') {
                    // Verify it's NOT a method call ($foo->bar()) or static method call (Foo::bar()) or function definition (function bar())
                    $prevIdx = $i - 1;
                    while ($prevIdx >= 0 && is_array($tokens[$prevIdx]) && $tokens[$prevIdx][0] === T_WHITESPACE) {
                        $prevIdx--;
                    }

                    $prevToken = $prevIdx >= 0 ? $tokens[$prevIdx] : null;
                    $isMethodCall = is_array($prevToken) && in_array($prevToken[0], [
                        T_OBJECT_OPERATOR,
                        defined('T_NULLSAFE_OBJECT_OPERATOR') ? T_NULLSAFE_OBJECT_OPERATOR : 388,
                        T_PAAMAYIM_NEKUDOTAYIM,
                        T_FUNCTION,
                    ], true);

                    if (! $isMethodCall) {
                        $violations[] = [
                            'file' => $filePath,
                            'line' => $line,
                            'type' => 'LARAVEL_HELPER_CALL',
                            'message' => "{$layer} layer must not call global framework helper [{$funcName}()].",
                        ];
                    }
                }
            }

            // Fully qualified references inside a class follow the same boundary as imports.
            if (is_array($token) && $token[0] === T_NAME_FULLY_QUALIFIED) {
                $reference = ltrim($token[1], '\\');
                if ($layer === 'Domain' && (str_starts_with($reference, 'Illuminate\\') || str_starts_with($reference, 'Laravel\\'))) {
                    $violations[] = [
                        'file' => $filePath,
                        'line' => $token[2],
                        'type' => 'LARAVEL_DEPENDENCY',
                        'message' => "Domain layer must not reference framework class [{$token[1]}].",
                    ];
                }

                if (str_contains($reference, '\\Http\\') || str_contains($reference, '\\Infrastructure\\') || str_starts_with($reference, 'App\\Http\\') || str_starts_with($reference, 'App\\Models\\')) {
                    $violations[] = [
                        'file' => $filePath,
                        'line' => $token[2],
                        'type' => 'OUTER_LAYER_DEPENDENCY',
                        'message' => "{$layer} layer must not reference outer Http or Infrastructure class [{$token[1]}].",
                    ];
                }

                if ($context && str_starts_with($reference, 'App\\Domains\\')) {
                    $parts = explode('\\', $reference);
                    $referencedContext = $parts[2] ?? '';
                    if ($referencedContext !== '' && $referencedContext !== $context) {
                        $violations[] = [
                            'file' => $filePath,
                            'line' => $token[2],
                            'type' => 'CROSS_CONTEXT_DEPENDENCY',
                            'message' => "{$layer} layer of [{$context}] must not reference other context [{$referencedContext}] directly.",
                        ];
                    }
                }
            }
        }

        return $violations;
    }

    private static function extractNamespace(array $tokens): string
    {
        $count = count($tokens);
        for ($i = 0; $i < $count; $i++) {
            if ($tokens[$i][0] === T_NAMESPACE) {
                $ns = '';
                for ($j = $i + 1; $j < $count; $j++) {
                    if ($tokens[$j] === '{' || $tokens[$j] === ';') {
                        break;
                    }
                    if (is_array($tokens[$j])) {
                        $ns .= $tokens[$j][1];
                    }
                }

                return trim($ns);
            }
        }

        return '';
    }

    private static function extractContext(string $namespace): ?string
    {
        if (preg_match('/^(?:App|Tests\\\\Unit\\\\Architecture\\\\Fixtures)\\\\Domains\\\\([a-zA-Z0-9_]+)\\\\/', $namespace, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private static function extractLayer(string $namespace): ?string
    {
        if (preg_match('/^(?:App|Tests\\\\Unit\\\\Architecture\\\\Fixtures)\\\\Domains\\\\[a-zA-Z0-9_]+\\\\(Domain|Application)(\\\\|$)/', $namespace, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @return array{path: string, line: int}|null
     */
    private static function extractUseStatement(array $tokens, int $startIndex): ?array
    {
        $count = count($tokens);
        $path = '';
        $line = is_array($tokens[$startIndex]) ? $tokens[$startIndex][2] : 0;

        for ($i = $startIndex + 1; $i < $count; $i++) {
            $t = $tokens[$i];
            if ($t === ';' || $t === '{') {
                break;
            }
            if (is_array($t)) {
                if ($t[0] === T_AS || $t[0] === T_CONST || $t[0] === T_FUNCTION) {
                    break;
                }
                if (in_array($t[0], [T_STRING, T_NAME_QUALIFIED, T_NAME_FULLY_QUALIFIED, T_NS_SEPARATOR], true)) {
                    $path .= $t[1];
                }
            }
        }

        $path = trim($path, " \t\n\r\\");

        return $path !== '' ? ['path' => $path, 'line' => $line] : null;
    }
}
