<?php

namespace App\Services\Export;

use App\Models\Book;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ExportService
{
    public function exportPDF(Book $book, array $options = []): \Barryvdh\DomPDF\PDF
    {
        $html = $this->buildHTML($book, $options);
        
        return Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 15)
            ->setOption('margin-bottom', 15)
            ->setOption('margin-left', 20)
            ->setOption('margin-right', 20)
            ->setOption('dpi', 300);
    }

    public function exportEPUB(Book $book, array $options = []): string
    {
        $filename = storage_path("exports/{$book->slug}.epub");
        
        $epub = new \EPub\EPub();
        $epub->setTitle($book->title);
        $epub->setAuthor($book->author ?? $book->user->name);
        $epub->setDescription($book->description);
        
        if ($book->cover_image) {
            $epub->setCoverImage($book->cover_image);
        }

        $chapterNum = 1;
        foreach ($book->chapters as $chapter) {
            $content = "<h1>{$chapter->title}</h1>";
            
            foreach ($chapter->sections as $section) {
                if ($section->title) {
                    $content .= "<h2>{$section->title}</h2>";
                }
                $content .= "<div class='section'>{$section->content}</div>";
            }

            $epub->addChapter(
                "Chapter {$chapterNum}: {$chapter->title}",
                "chapter_{$chapterNum}.xhtml",
                $content
            );
            $chapterNum++;
        }

        $epub->finalize();
        $epub->sendFile($filename);
        
        return $filename;
    }

    public function exportMOBI(Book $book, array $options = []): string
    {
        // Za pomocou calibre alebo amazon-kindlegen
        $epubFile = $this->exportEPUB($book, $options);
        $outputFile = storage_path("exports/{$book->slug}.mobi");
        
        shell_exec("kindlegen {$epubFile} -o " . basename($outputFile));
        
        return $outputFile;
    }

    public function exportDOCX(Book $book, array $options = []): string
    {
        $filename = storage_path("exports/{$book->slug}.docx");
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        $section = $phpWord->addSection();
        $section->addTitle($book->title);
        $section->addText($book->description ?? '');

        foreach ($book->chapters as $chapter) {
            $section->addHeading($chapter->title, 1);
            
            foreach ($chapter->sections as $sectionContent) {
                if ($sectionContent->title) {
                    $section->addHeading($sectionContent->title, 2);
                }
                $section->addText($sectionContent->content);
            }
        }

        $phpWord->save($filename);
        return $filename;
    }

    public function exportMarkdown(Book $book): string
    {
        $content = "# {$book->title}\n\n";
        $content .= "**Autor:** {$book->author}\n\n";
        $content .= $book->description . "\n\n";
        $content .= "## Obsah\n\n";

        foreach ($book->chapters as $chapter) {
            $content .= "- [{$chapter->title}](#{$chapter->slug})\n";
        }

        $content .= "\n---\n\n";

        foreach ($book->chapters as $chapter) {
            $content .= "## {$chapter->title}\n\n";
            
            foreach ($chapter->sections as $section) {
                if ($section->title) {
                    $content .= "### {$section->title}\n\n";
                }
                $content .= $section->content . "\n\n";
            }
        }

        $filename = storage_path("exports/{$book->slug}.md");
        file_put_contents($filename, $content);
        
        return $filename;
    }

    public function exportHTML(Book $book, array $options = []): string
    {
        $html = $this->buildHTML($book, $options);
        $filename = storage_path("exports/{$book->slug}.html");
        file_put_contents($filename, $html);
        
        return $filename;
    }

    protected function buildHTML(Book $book, array $options = []): string
    {
        $toc = '';
        foreach ($book->chapters as $chapter) {
            $toc .= "<li><a href='#{$chapter->slug}'>{$chapter->title}</a></li>";
        }

        $content = '';
        foreach ($book->chapters as $chapter) {
            $content .= "<h1 id='{$chapter->slug}'>{$chapter->title}</h1>";
            
            foreach ($chapter->sections as $section) {
                if ($section->title) {
                    $content .= "<h2>{$section->title}</h2>";
                }
                $content .= "<div class='section'>{$section->content}</div>";
            }
        }

        return <<<HTML
        <!DOCTYPE html>
        <html lang="sk">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>{$book->title}</title>
            <style>
                body { font-family: Georgia, serif; line-height: 1.6; margin: 2cm; }
                h1 { page-break-before: always; color: #333; margin-top: 2em; }
                h2 { color: #555; margin-top: 1.5em; }
                .toc { page-break-after: always; }
                .section { margin: 1em 0; }
            </style>
        </head>
        <body>
            <h1>{$book->title}</h1>
            <p><strong>{$book->author}</strong></p>
            <p>{$book->description}</p>
            
            <div class="toc">
                <h2>Obsah</h2>
                <ul>$toc</ul>
            </div>
            
            $content
        </body>
        </html>
        HTML;
    }
}
