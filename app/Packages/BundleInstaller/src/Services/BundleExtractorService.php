<?php

namespace App\Packages\BundleInstaller\Services;

use Illuminate\Support\Facades\Storage;
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

        if ($zip->locateName('manifest.json') === false) {
            $zip->close();
            return ['valid' => false, 'error' => 'manifest.json not found in bundle'];
        }

        $manifestContent = $zip->getFromName('manifest.json');
        $manifest = json_decode($manifestContent, true);

        if (!$manifest || !isset($manifest['name'], $manifest['version'], $manifest['provider_class'], $manifest['package_path'])) {
            $zip->close();
            return ['valid' => false, 'error' => 'Invalid manifest.json format'];
        }

        $zip->close();

        return [
            'valid' => true,
            'manifest' => $manifest,
        ];
    }

    public function extract(string $zipPath, array $manifest): array
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

        if (!$zip->extractTo($packagePath)) {
            $zip->close();
            return ['success' => false, 'error' => 'Failed to extract ZIP file'];
        }

        $zip->close();

        return ['success' => true, 'packagePath' => $packagePath];
    }

    private function copyDir(string $src, string $dst): void
    {
        @mkdir($dst, 0755, true);
        $dir = opendir($src);
        while (($file = readdir($dir)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir("$src/$file")) {
                $this->copyDir("$src/$file", "$dst/$file");
            } else {
                copy("$src/$file", "$dst/$file");
            }
        }
        closedir($dir);
    }

    private function removeDir(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        $files = array_diff(scandir($path), ['.', '..']);
        foreach ($files as $file) {
            $file = "$path/$file";
            if (is_dir($file)) {
                $this->removeDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($path);
    }
}
