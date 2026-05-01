<?php

namespace App\Packages\Installer\Helpers;

class ServerRequirements
{
    private string $minPhpVersion = '8.3.0';

    public function validate(): array
    {
        $extensions = [
            'ctype', 'curl', 'dom', 'fileinfo', 'filter',
            'gd', 'hash', 'intl', 'json', 'mbstring',
            'openssl', 'pcre', 'pdo', 'session', 'tokenizer', 'xml',
        ];

        $results = [];

        foreach ($extensions as $ext) {
            $results['requirements']['php'][$ext] = extension_loaded($ext);

            if (! extension_loaded($ext)) {
                $results['errors'] = true;
            }
        }

        return $results;
    }

    public function checkPHPversion(?string $minPhpVersion = null): array
    {
        $min = $minPhpVersion ?? $this->minPhpVersion;
        $info = $this->getPhpVersionInfo();

        return [
            'full'      => $info['full'],
            'current'   => $info['version'],
            'minimum'   => $min,
            'supported' => version_compare($info['version'], $min) >= 0,
        ];
    }

    private static function getPhpVersionInfo(): array
    {
        $full = PHP_VERSION;
        preg_match('#^\d+(\.\d+)*#', $full, $filtered);

        return [
            'full'    => $full,
            'version' => $filtered[0] ?? $full,
        ];
    }
}
