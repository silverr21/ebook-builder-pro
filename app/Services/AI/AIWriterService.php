<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class AIWriterService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
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

    public function generateSections(string $chapterTitle, int $sections = 5): array
    {
        $response = $this->call('generateSections', [
            'chapter_title' => $chapterTitle,
            'sections_count' => $sections,
        ]);
        
        return json_decode($response, true);
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
        
        return json_decode($response, true);
    }

    public function checkStyle(string $text): array
    {
        $response = $this->call('checkStyle', [
            'text' => $text,
        ]);
        
        return json_decode($response, true);
    }

    public function generateSEO(string $title, string $description): array
    {
        $response = $this->call('generateSEO', [
            'title' => $title,
            'description' => $description,
        ]);
        
        return json_decode($response, true);
    }

    public function generateFAQ(string $topic, int $questions = 10): array
    {
        $response = $this->call('generateFAQ', [
            'topic' => $topic,
            'questions' => $questions,
        ]);
        
        return json_decode($response, true);
    }

    public function generateGlossary(string $text): array
    {
        $response = $this->call('generateGlossary', [
            'text' => $text,
        ]);
        
        return json_decode($response, true);
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
        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(60)
                ->post("{$this->baseUrl}/chat/completions", [
                    'model' => 'gpt-4-turbo',
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
                    'temperature' => 0.7,
                    'max_tokens' => 2000,
                ]);

            if ($response->failed()) {
                throw new \Exception($response->json('error.message') ?? 'AI API Error');
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
            'generateTitle' => 'You are a professional book title generator. Generate creative, engaging book titles.',
            'generateIntroduction' => 'You are an expert writer. Write engaging introductions for books.',
            'generateChapter' => 'You are a professional author. Write well-structured, engaging chapters.',
            'checkGrammar' => 'You are a grammar expert. Check the text for grammar errors and provide suggestions.',
            'generateSEO' => 'You are an SEO expert. Generate SEO-optimized meta descriptions and keywords.',
            default => 'You are a professional writer and editor. Help with writing tasks.',
        };
    }

    protected function buildPrompt(string $prompt, array $params): string
    {
        return match($prompt) {
            'generateTitle' => "Topic: {$params['topic']}, Genre: {$params['genre']}. Generate 5 book titles.",
            'generateSubtitle' => "Title: {$params['title']}, Genre: {$params['genre']}. Generate 3 subtitles.",
            'generateIntroduction' => "Title: {$params['title']}, Topic: {$params['topic']}, Words: {$params['word_count']}. Write an introduction.",
            'generateChapter' => "Book Title: {$params['book_title']}, Topic: {$params['topic']}, Words: {$params['word_count']}. Write a chapter.",
            'expandText' => "Expand this text by {$params['ratio']}x while maintaining meaning: {$params['text']}",
            'shortenText' => "Shorten this text to {$params['ratio']}x its original length: {$params['text']}",
            'rewriteText' => "Rewrite in {$params['style']} style: {$params['text']}",
            'continueWriting' => "Continue this text with {$params['words']} more words: {$params['text']}",
            'checkGrammar' => "Check grammar and provide JSON array of errors: {$params['text']}",
            'generateFAQ' => "Topic: {$params['topic']}, Count: {$params['questions']}. Generate FAQ in JSON format.",
            'generateSEO' => "Title: {$params['title']}, Description: {$params['description']}. Generate SEO keywords and meta in JSON.",
            default => json_encode($params),
        };
    }
}
