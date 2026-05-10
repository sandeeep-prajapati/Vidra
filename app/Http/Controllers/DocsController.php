<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;

class DocsController extends Controller
{
    public function show($page = 'getting-started/readme')
    {
        $docsPath = resource_path('views/docs');

        // Markdown file
        $path = "{$docsPath}/{$page}.md";

        if (!File::exists($path)) {
            abort(404);
        }

        // Read markdown
        $markdown = File::get($path);

        // Convert markdown
        $converter = new CommonMarkConverter();
        $html = $converter->convert($markdown);

        // Sidebar
        $menu = $this->generateMenu($docsPath);

        // Detect demo video automatically
        $slug = basename($page);

        $videoPath = public_path("demo/{$slug}.mp4");

        $video = null;

        if (File::exists($videoPath)) {
            $video = asset("demo/{$slug}.mp4");
        }

        return view('docs.show', [
            'content' => $html,
            'menu' => $menu,
            'currentPage' => $page,
            'video' => $video,
        ]);
    }

    private function generateMenu($docsPath)
    {
        $menu = [];

        $folders = File::directories($docsPath);

        foreach ($folders as $folder) {

            $folderName = basename($folder);

            $files = File::files($folder);

            $menu[$folderName] = collect($files)
                ->filter(fn($file) =>
                    $file->getExtension() === 'md'
                )
                ->map(function ($file) use ($folderName) {

                    $slug = pathinfo(
                        $file->getFilename(),
                        PATHINFO_FILENAME
                    );

                    return [
                        'title' => Str::headline($slug),

                        'slug' => $slug,

                        'url' => url(
                            "/manual/{$folderName}/{$slug}"
                        ),
                    ];
                })
                ->values()
                ->toArray();
        }

        return $menu;
    }
}