<?php

use Tests\Unit\Architecture\DependencyAnalyzer;

describe('Clean Architecture Dependency Rules', function () {

    test('detects Laravel helper calls in Domain layer', function () {
        $fixture = __DIR__.'/Fixtures/Domains/Catalog/Domain/DomainViolatingLaravelHelper.php';
        $violations = DependencyAnalyzer::analyzeFile($fixture);

        expect($violations)->not->toBeEmpty()
            ->and($violations[0]['type'])->toBe('LARAVEL_HELPER_CALL')
            ->and($violations[0]['message'])->toContain('route()');
    });

    test('detects Illuminate/Laravel imports in Domain layer', function () {
        $fixture = __DIR__.'/Fixtures/Domains/Catalog/Domain/DomainViolatingLaravelImport.php';
        $violations = DependencyAnalyzer::analyzeFile($fixture);

        expect($violations)->not->toBeEmpty()
            ->and($violations[0]['type'])->toBe('LARAVEL_DEPENDENCY')
            ->and($violations[0]['message'])->toContain('Illuminate\Support\Str');
    });

    test('detects Infrastructure imports in Domain layer', function () {
        $fixture = __DIR__.'/Fixtures/Domains/Catalog/Domain/DomainViolatingInfrastructureImport.php';
        $violations = DependencyAnalyzer::analyzeFile($fixture);

        expect($violations)->not->toBeEmpty()
            ->and($violations[0]['type'])->toBe('OUTER_LAYER_DEPENDENCY');
    });

    test('detects cross-context imports in Domain layer', function () {
        $fixture = __DIR__.'/Fixtures/Domains/Catalog/Domain/DomainViolatingCrossContextImport.php';
        $violations = DependencyAnalyzer::analyzeFile($fixture);

        expect($violations)->not->toBeEmpty()
            ->and($violations[0]['type'])->toBe('CROSS_CONTEXT_DEPENDENCY');
    });

    test('detects Http layer imports in Application layer', function () {
        $fixture = __DIR__.'/Fixtures/Domains/Catalog/Application/ApplicationViolatingHttpImport.php';
        $violations = DependencyAnalyzer::analyzeFile($fixture);

        expect($violations)->not->toBeEmpty()
            ->and($violations[0]['type'])->toBe('OUTER_LAYER_DEPENDENCY');
    });

    test('detects direct cross-context imports in Application layer', function () {
        $fixture = __DIR__.'/Fixtures/Domains/Catalog/Application/ApplicationViolatingCrossContextImport.php';
        $violations = DependencyAnalyzer::analyzeFile($fixture);

        expect($violations)->not->toBeEmpty()
            ->and($violations[0]['type'])->toBe('CROSS_CONTEXT_DEPENDENCY');
    });

    test('verifies pure PHP Domain class has zero violations', function () {
        $fixture = __DIR__.'/Fixtures/Domains/Catalog/Domain/ValidDomainClass.php';
        $violations = DependencyAnalyzer::analyzeFile($fixture);

        expect($violations)->toBeEmpty();
    });

    test('detects fully qualified cross-context references inside classes', function () {
        $code = '<?php namespace App\\Domains\\Catalog\\Application; class Example { public function run(): void { new \\App\\Domains\\Commerce\\Application\\CartService(); } }';
        $violations = DependencyAnalyzer::analyzeCode($code);

        expect($violations)->toHaveCount(1)
            ->and($violations[0]['type'])->toBe('CROSS_CONTEXT_DEPENDENCY');
    });

    test('enforces clean architecture dependency direction across all app/Domains classes', function () {
        $baseDir = dirname(__DIR__, 3);
        $domainsDir = $baseDir.'/app/Domains';
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($domainsDir));

        // Documented baseline exceptions (if any existing file in app/Domains violates rules temporarily)
        // Format: 'relative_path' => ['reason' => '...', 'expiry' => 'phase-X']
        $baselineExceptions = [];

        $allViolations = [];

        foreach ($it as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $filePath = str_replace('\\', '/', $file->getPathname());

                // Only scan classes situated in Domain/ or Application/ sub-namespaces
                if (! str_contains($filePath, '/Domain/') && ! str_contains($filePath, '/Application/')) {
                    continue;
                }

                $violations = DependencyAnalyzer::analyzeFile($filePath);
                if (! empty($violations)) {
                    $relPath = str_replace(str_replace('\\', '/', $baseDir).'/', '', $filePath);
                    if (! isset($baselineExceptions[$relPath])) {
                        $allViolations[$relPath] = $violations;
                    }
                }
            }
        }

        if (! empty($allViolations)) {
            $formatted = json_encode($allViolations, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $this->fail("Architecture dependency violations found:\n".$formatted);
        }

        expect($allViolations)->toBeEmpty();
    });
});
