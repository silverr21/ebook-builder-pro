<?php

namespace App\Services\AI;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class AIWriterService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://api.openai.com/v1';
    protected int $maxTokens;
    protected float $temperature;
    protected int $timeout;

    public function __construct(?User $user = null)
    {
        $this->apiKey = $user?->getAiApiKey() ?: config('services.openai.api_key');
        $this->model = $user?->ai_model ?: config('services.openai.model', 'gpt-4-turbo');
        $this->maxTokens = (int) config('services.openai.max_tokens', 2000);
        $this->temperature = (float) config('services.openai.temperature', 0.7);
        $this->timeout = (int) config('services.openai.timeout', 60);
    }

    public function setUser(?User $user): self
    {
        if ($user) {
            $this->apiKey = $user->getAiApiKey();
            $this->model = $user->ai_model ?: $this->model;
        }
        return $this;
    }

    public function generateTitle(string $topic, string $genre): string
    {
        return $this->call('generateTitle', [
            'topic' => $topic,
            'genre' => $genre,
        ]);
    }

    public function generateSubtitle(string $title, string $genre): string
    {
        return $this->call('generateSubtitle', [
            'title' => $title,
            'genre' => $genre,
        ]);
    }

    public function generateIntroduction(string $title, string $topic, int $wordCount = 300): string
    {
        return $this->call('generateIntroduction', [
            'title' => $title,
            'topic' => $topic,
            'word_count' => $wordCount,
        ]);
    }

    public function generateConclusion(string $title, string $summary, int $wordCount = 300): string
    {
        return $this->call('generateConclusion', [
            'title' => $title,
            'summary' => $summary,
            'word_count' => $wordCount,
        ]);
    }

    public function generateChapter(string $bookTitle, string $topic, int $wordCount = 2000): string
    {
        return $this->call('generateChapter', [
            'book_title' => $bookTitle,
            'topic' => $topic,
            'word_count' => $wordCount,
        ]);
    }

    public function generateEntireBook(string $bookTitle, string $topic, string $genre, string $style, int $chapters = 5): array
    {
        $content = $this->call('generateEntireBook', [
            'book_title' => $bookTitle,
            'topic' => $topic,
            'genre' => $genre,
            'style' => $style,
            'chapters' => $chapters,
        ]);
        
        return json_decode($content, true) ?: $this->parseBookContent($content);
    }

    protected function parseBookContent(string $content): array
    {
        $chapters = [];
        $lines = explode("\n", $content);
        $currentChapter = null;
        $currentContent = '';

        foreach ($lines as $line) {
            if (preg_match('/^#{1,3}\s+(.+)$/', $line, $matches)) {
                if ($currentChapter) {
                    $chapters[] = ['title' => $currentChapter, 'content' => trim($currentContent)];
                }
                $currentChapter = trim($matches[1]);
                $currentContent = '';
            } elseif ($currentChapter) {
                $currentContent .= $line . "\n";
            }
        }
        if ($currentChapter) {
            $chapters[] = ['title' => $currentChapter, 'content' => trim($currentContent)];
        }

        return $chapters ?: [
            ['title' => 'Chapter 1', 'content' => $content]
        ];
    }

    public function generateSections(string $chapterTitle, int $sections = 5): array
    {
        $response = $this->call('generateSections', [
            'chapter_title' => $chapterTitle,
            'sections_count' => $sections,
        ]);
        
        return json_decode($response, true) ?: [];
    }

    public function expandText(string $text, float $ratio = 1.5): string
    {
        return $this->call('expandText', [
            'text' => $text,
            'ratio' => $ratio,
        ]);
    }

    public function shortenText(string $text, float $ratio = 0.7): string
    {
        return $this->call('shortenText', [
            'text' => $text,
            'ratio' => $ratio,
        ]);
    }

    public function rewriteText(string $text, string $style = 'formal'): string
    {
        return $this->call('rewriteText', [
            'text' => $text,
            'style' => $style,
        ]);
    }

    public function continueWriting(string $text, int $words = 500): string
    {
        return $this->call('continueWriting', [
            'text' => $text,
            'words' => $words,
        ]);
    }

    public function checkGrammar(string $text): array
    {
        $response = $this->call('checkGrammar', [
            'text' => $text,
        ]);
        
        return json_decode($response, true) ?: [];
    }

    public function checkStyle(string $text): array
    {
        $response = $this->call('checkStyle', [
            'text' => $text,
        ]);
        
        return json_decode($response, true) ?: [];
    }

    public function generateSEO(string $title, string $description): array
    {
        $response = $this->call('generateSEO', [
            'title' => $title,
            'description' => $description,
        ]);
        
        return json_decode($response, true) ?: [];
    }

    public function generateFAQ(string $topic, int $questions = 10): array
    {
        $response = $this->call('generateFAQ', [
            'topic' => $topic,
            'questions' => $questions,
        ]);
        
        return json_decode($response, true) ?: [];
    }

    public function generateGlossary(string $text): array
    {
        $response = $this->call('generateGlossary', [
            'text' => $text,
        ]);
        
        return json_decode($response, true) ?: [];
    }

    public function generateSummary(string $text, int $sentenceCount = 5): string
    {
        return $this->call('generateSummary', [
            'text' => $text,
            'sentence_count' => $sentenceCount,
        ]);
    }

    protected function call(string $prompt, array $params): string
    {
        if (empty($this->apiKey)) {
            throw new \Exception('AI API kľúč nie je nastavený. Nastavte ho v Profile > AI Nastavenia.');
        }

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout($this->timeout)
                ->post("{$this->baseUrl}/chat/completions", [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $this->getSystemPrompt($prompt),
                        ],
                        [
                            'role' => 'user',
                            'content' => $this->buildPrompt($prompt, $params),
                        ],
                    ],
                    'temperature' => $this->temperature,
                    'max_tokens' => $prompt === 'generateEntireBook' ? 4000 : $this->maxTokens,
                ]);

            if ($response->failed()) {
                $errorMsg = $response->json('error.message') ?? 'AI API Error';
                if ($response->status() === 401) {
                    throw new \Exception('Neplatný API kľúč. Skontrolujte ho v Profile > AI Nastavenia.');
                }
                throw new \Exception($errorMsg);
            }

            return $response->json('choices.0.message.content');
        } catch (\Exception $e) {
            logger()->error('AI Writer Error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function getSystemPrompt(string $prompt): string
    {
        return match($prompt) {
            'generateTitle' => 'You are a professional book title generator. Generate creative, engaging book titles. Return only the titles, one per line.',
            'generateIntroduction' => 'You are an expert writer. Write engaging introductions for books. Use clear paragraphs with proper formatting.',
            'generateChapter' => 'You are a professional author. Write well-structured, engaging chapters with proper formatting, paragraphs, and natural flow.',
            'generateEntireBook' => 'You are a professional author. Create a complete book outline and content in JSON format with this structure: [{"title": "Chapter Title", "content": "Full chapter content with paragraphs..."}]. Write engaging, well-structured content.',
            'checkGrammar' => 'You are a grammar expert. Check the text for grammar errors and return a JSON array of objects with "message", "line", and "suggestion" fields.',
            'generateSEO' => 'You are an SEO expert. Generate SEO-optimized meta descriptions and keywords. Return as JSON with "title", "description", and "keywords" fields.',
            default => 'You are a professional writer and editor. Help with writing tasks. Provide well-formatted text.',
        };
    }

    protected function buildPrompt(string $prompt, array $params): string
    {
        return match($prompt) {
            'generateTitle' => "Topic: {$params['topic']}, Genre: {$params['genre']}. Generate 5 book titles.",
            'generateSubtitle' => "Title: {$params['title']}, Genre: {$params['genre']}. Generate 3 subtitles.",
            'generateIntroduction' => "Title: {$params['title']}, Topic: {$params['topic']}, Words: {$params['word_count']}. Write an engaging introduction.",
            'generateChapter' => "Book Title: {$params['book_title']}, Topic: {$params['topic']}, Words: {$params['word_count']}. Write a well-structured chapter with paragraphs.",
            'generateEntireBook' => "Create a complete book with {$params['chapters']} chapters. Book Title: '{$params['book_title']}'. Topic: {$params['topic']}. Genre: {$params['genre']}. Style: {$params['style']}. Return JSON array with 'title' and 'content' for each chapter. Make each chapter detailed and well-written (at least 500 words each).",
            'generateSections' => "Chapter: {$params['chapter_title']}. Generate {$params['sections_count']} section titles as a JSON array.",
            'expandText' => "Expand this text by {$params['ratio']}x while maintaining meaning and quality: {$params['text']}",
            'shortenText' => "Shorten this text to {$params['ratio']}x its original length while keeping key points: {$params['text']}",
            'rewriteText' => "Rewrite in {$params['style']} style (formal, casual, academic, poetic, or technical): {$params['text']}",
            'continueWriting' => "Continue this text naturally with {$params['words']} more words, maintaining the same style and voice: {$params['text']}",
            'checkGrammar' => "Check grammar and return JSON array of issues found. Each issue should have 'message', 'suggestion' fields. Text: {$params['text']}",
            'generateFAQ' => "Topic: {$params['topic']}, Count: {$params['questions']}. Generate FAQ in JSON format with 'question' and 'answer' fields.",
            'generateSEO' => "Title: {$params['title']}, Description: {$params['description']}. Generate SEO meta data in JSON.",
            default => json_encode($params),
        };
    }

    public function validateApiKey(string $apiKey): bool
    {
        try {
            $response = Http::withToken($apiKey)
                ->timeout(10)
                ->get("{$this->baseUrl}/models");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
