<?php

namespace App\Packages\BundleInstaller\Services;

use ZipArchive;

class BundleExtractorService
{
    public function validate(string $zipPath): array
    {
        if (!file_exists($zipPath)) {
            return ['valid' => false, 'error' => 'File not found'];
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            return ['valid' => false, 'error' => 'Invalid ZIP file'];
        }

        // Try manifest at ZIP root first, then inside a single root directory
        $manifestContent = $zip->getFromName('manifest.json');
        $prefix = '';

        if ($manifestContent === false) {
            $prefix = $this->detectRootPrefix($zip);
            if ($prefix !== '') {
                $manifestContent = $zip->getFromName("{$prefix}manifest.json");
            }
        }

        if ($manifestContent === false) {
            $zip->close();
            return ['valid' => false, 'error' => 'manifest.json not found in bundle'];
        }

        $manifest = json_decode($manifestContent, true);

        if (!$manifest || !isset($manifest['name'], $manifest['version'], $manifest['provider_class'], $manifest['package_path'])) {
            $zip->close();
            return ['valid' => false, 'error' => 'Invalid manifest.json format'];
        }

        $zip->close();

        return [
            'valid'    => true,
            'manifest' => $manifest,
            'prefix'   => $prefix,
        ];
    }

    public function extract(string $zipPath, array $manifest, string $prefix = ''): array
    {
        $packagePath = base_path("app/Packages/{$manifest['package_path']}");

        if (is_dir($packagePath)) {
            return ['success' => false, 'error' => "Package {$manifest['package_path']} already exists"];
        }

        @mkdir($packagePath, 0755, true);

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            return ['success' => false, 'error' => 'Failed to open ZIP file'];
        }

        if ($prefix === '') {
            if (!$zip->extractTo($packagePath)) {
                $zip->close();
                return ['success' => false, 'error' => 'Failed to extract ZIP file'];
            }
        } else {
            // Strip the root directory prefix when extracting
            $prefixLen = strlen($prefix);
            for ($i = 0; $i < $zip->count(); $i++) {
                $name = $zip->getNameIndex($i);
                if (!str_starts_with($name, $prefix)) {
                    continue;
                }
                $relativePath = substr($name, $prefixLen);
                if ($relativePath === '' || $relativePath === '/') {
                    continue;
                }
                $targetPath = $packagePath . '/' . $relativePath;
                if (str_ends_with($name, '/')) {
                    @mkdir($targetPath, 0755, true);
                } else {
                    $dir = dirname($targetPath);
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0755, true);
                    }
                    file_put_contents($targetPath, $zip->getFromIndex($i));
                }
            }
        }

        $zip->close();

        return ['success' => true, 'packagePath' => $packagePath];
    }

    // Detects a single common root directory in the ZIP (e.g. "LibraryManagement/").
    // Returns the prefix string (with trailing slash) or '' if no common root.
    private function detectRootPrefix(ZipArchive $zip): string
    {
        $count  = $zip->count();
        $prefix = null;

        for ($i = 0; $i < $count; $i++) {
            $name  = $zip->getNameIndex($i);
            $slash = strpos($name, '/');

            if ($slash === false) {
                // A file sits at ZIP root — no single root directory
                return '';
            }

            $dir = substr($name, 0, $slash + 1);

            if ($prefix === null) {
                $prefix = $dir;
            } elseif ($prefix !== $dir) {
                // Multiple root-level directories — no single prefix
                return '';
            }
        }

        return $prefix ?? '';
    }
}
