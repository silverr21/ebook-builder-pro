<?php

namespace App\Services\Design;

use App\Models\Book;
use App\Models\Design;

class DesignService
{
    protected const TEMPLATES = [
        'modern' => [
            'typography' => [
                'heading' => ['fontFamily' => 'Poppins', 'fontSize' => 32, 'fontWeight' => 700, 'color' => '#1F2937'],
                'body' => ['fontFamily' => 'Inter', 'fontSize' => 16, 'lineHeight' => 1.6, 'color' => '#374151'],
                'caption' => ['fontFamily' => 'Inter', 'fontSize' => 14, 'color' => '#6B7280'],
            ],
            'colors' => [
                'primary' => '#3B82F6',
                'secondary' => '#10B981',
                'accent' => '#F59E0B',
                'text' => '#1F2937',
                'textLight' => '#6B7280',
                'background' => '#FFFFFF',
                'backgroundLight' => '#F9FAFB',
            ],
            'spacing' => ['margin' => 24, 'padding' => 32, 'lineSpacing' => 1.6],
            'layout' => ['columns' => 1, 'maxWidth' => 800, 'alignment' => 'left']
        ],
        'classic' => [
            'typography' => [
                'heading' => ['fontFamily' => 'Georgia', 'fontSize' => 36, 'fontWeight' => 700, 'color' => '#1F2937'],
                'body' => ['fontFamily' => 'Georgia', 'fontSize' => 18, 'lineHeight' => 1.8, 'color' => '#374151'],
            ],
            'colors' => [
                'primary' => '#8B4513',
                'secondary' => '#D2B48C',
                'text' => '#2C1810',
                'background' => '#FFFAF0',
            ],
            'spacing' => ['margin' => 32, 'padding' => 40, 'lineSpacing' => 1.8],
            'layout' => ['columns' => 1, 'maxWidth' => 900]
        ],
        'minimal' => [
            'typography' => [
                'heading' => ['fontFamily' => 'Helvetica', 'fontSize' => 24, 'fontWeight' => 600, 'color' => '#000000'],
                'body' => ['fontFamily' => 'Helvetica', 'fontSize' => 14, 'lineHeight' => 1.5, 'color' => '#333333'],
            ],
            'colors' => [
                'primary' => '#000000',
                'secondary' => '#CCCCCC',
                'text' => '#000000',
                'background' => '#FFFFFF',
            ],
            'spacing' => ['margin' => 16, 'padding' => 24, 'lineSpacing' => 1.5],
            'layout' => ['columns' => 1, 'maxWidth' => 700]
        ],
        'academic' => [
            'typography' => [
                'heading' => ['fontFamily' => 'Times New Roman', 'fontSize' => 28, 'fontWeight' => 700],
                'body' => ['fontFamily' => 'Times New Roman', 'fontSize' => 12, 'lineHeight' => 2.0],
            ],
            'colors' => [
                'primary' => '#003366',
                'text' => '#000000',
                'background' => '#FFFFFF',
            ],
            'spacing' => ['margin' => 25.4, 'padding' => 25.4, 'lineSpacing' => 2.0],
            'layout' => ['columns' => 1, 'maxWidth' => 800]
        ],
    ];

    public function createOrUpdateDesign(Book $book, array $data): Design
    {
        $design = $book->design ?? new Design(['book_id' => $book->id]);
        $design->fill($data);
        $design->save();
        return $design;
    }

    public function applyTemplate(Book $book, string $templateName): Design
    {
        if (!isset(self::TEMPLATES[$templateName])) {
            throw new \InvalidArgumentException("Template '$templateName' not found");
        }

        $template = self::TEMPLATES[$templateName];
        
        return $this->createOrUpdateDesign($book, [
            'name' => $templateName,
            'template' => $templateName,
            'typography' => $template['typography'],
            'colors' => $template['colors'],
            'spacing' => $template['spacing'],
            'layout' => $template['layout'],
        ]);
    }

    public function getAvailableTemplates(): array
    {
        return array_keys(self::TEMPLATES);
    }

    public function generateCoverDesign(Book $book, array $options): string
    {
        // Integracia s AI image generation service
        $prompt = $this->buildCoverPrompt($book, $options);
        
        // Volanie do Dall-E alebo inej AI image service
        return $this->generateImage($prompt, $options);
    }

    public function generateIllustration(string $prompt, string $style): string
    {
        return $this->generateImage($prompt, ['style' => $style]);
    }

    public function customizeTypography(Design $design, array $typography): Design
    {
        $design->typography = array_merge($design->typography ?? [], $typography);
        $design->save();
        return $design;
    }

    public function customizeColors(Design $design, array $colors): Design
    {
        $design->colors = array_merge($design->colors ?? [], $colors);
        $design->save();
        return $design;
    }

    public function exportDesignAsJSON(Design $design): string
    {
        return json_encode([
            'name' => $design->name,
            'template' => $design->template,
            'typography' => $design->typography,
            'colors' => $design->colors,
            'spacing' => $design->spacing,
            'layout' => $design->layout,
        ], JSON_PRETTY_PRINT);
    }

    public function importDesignFromJSON(Book $book, string $json): Design
    {
        $data = json_decode($json, true);
        return $this->createOrUpdateDesign($book, $data);
    }

    protected function buildCoverPrompt(Book $book, array $options): string
    {
        $genre = $options['genre'] ?? 'fiction';
        $mood = $options['mood'] ?? 'professional';
        
        return "Create a professional ebook cover for: '{$book->title}' by {$book->author}. " .
               "Genre: {$genre}. Mood: {$mood}. Style: {$options['style'] ?? 'modern'}. " .
               "Description: {$book->description}";
    }

    protected function generateImage(string $prompt, array $options): string
    {
        // Placeholder - implementovať AI image generation
        // Možnosti: DALL-E, Midjourney, Stable Diffusion
        return 'image_placeholder.png';
    }
}
