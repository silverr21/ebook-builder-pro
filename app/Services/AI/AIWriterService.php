<?php

namespace App\Services\AI;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class AIWriterService
{
    protected string $apiKey;
    protected string $model;
    protected string $provider = 'openai';
    protected string $baseUrl;
    protected int $maxTokens;
    protected float $temperature;
    protected int $timeout;
    protected string $language = 'slovak';

    protected array $providerDefaults = [
        'openai' => [
            'base_url' => 'https://api.openai.com/v1',
            'models' => ['gpt-4-turbo', 'gpt-4o', 'gpt-4o-mini', 'gpt-3.5-turbo', 'gpt-4', 'gpt-4-32k'],
            'default_model' => 'gpt-4-turbo',
        ],
        'anthropic' => [
            'base_url' => 'https://api.anthropic.com/v1',
            'models' => ['claude-3-opus-20240229', 'claude-3-sonnet-20240229', 'claude-3-haiku-20240307', 'claude-3-5-sonnet-20241022'],
            'default_model' => 'claude-3-sonnet-20240229',
        ],
        'gemini' => [
            'base_url' => 'https://generativelanguage.googleapis.com/v1beta',
            'models' => ['gemini-pro', 'gemini-1.5-pro', 'gemini-1.5-flash', 'gemini-2.0-flash-exp'],
            'default_model' => 'gemini-1.5-pro',
        ],
    ];

    public function __construct(?User $user = null)
    {
        $this->applyUserSettings($user);
    }

    protected function applyUserSettings(?User $user): void
    {
        if (!$user) {
            $this->apiKey = config('services.openai.api_key');
            $this->provider = 'openai';
            $this->model = config('services.openai.model', 'gpt-4-turbo');
            $this->maxTokens = (int) config('services.openai.max_tokens', 2000);
            $this->temperature = (float) config('services.openai.temperature', 0.7);
            $this->timeout = (int) config('services.openai.timeout', 60);
            $this->language = 'slovak';
            $this->baseUrl = $this->providerDefaults['openai']['base_url'];
            return;
        }

        $this->provider = $user->ai_provider ?? 'openai';
        $this->apiKey = $user->getAiApiKey();
        $this->model = $user->ai_model ?: ($this->providerDefaults[$this->provider]['default_model'] ?? 'gpt-4-turbo');
        $this->temperature = (float) ($user->ai_temperature ?? 0.7);
        $this->maxTokens = (int) ($user->ai_max_tokens ?? 2000);
        $this->timeout = (int) ($user->ai_timeout ?? 60);
        $this->language = $this->mapLanguage($user->ai_language ?? 'sk');
        $this->baseUrl = $user->ai_api_url ?: ($this->providerDefaults[$this->provider]['base_url'] ?? $this->providerDefaults['openai']['base_url']);
    }

    public function setUser(?User $user): self
    {
        $this->applyUserSettings($user);
        return $this;
    }

    protected function mapLanguage(string $code): string
    {
        return match($code) {
            'sk' => 'slovak',
            'cs' => 'czech',
            'en' => 'english',
            'de' => 'german',
            'pl' => 'polish',
            'hu' => 'hungarian',
            default => 'slovak',
        };
    }

    // --- Public API Methods ---

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
            ['title' => 'Kapitola 1', 'content' => $content]
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

    public function generateCoverDescription(string $title, string $genre, string $mood = 'professional'): string
    {
        return $this->call('generateCoverDescription', [
            'title' => $title,
            'genre' => $genre,
            'mood' => $mood,
        ]);
    }

    // --- Core API Call ---

    protected function call(string $prompt, array $params): string
    {
        if (empty($this->apiKey)) {
            throw new \Exception('AI API kľúč nie je nastavený. Nastavte ho v Profile > AI Nastavenia.');
        }

        return match($this->provider) {
            'anthropic' => $this->callAnthropic($prompt, $params),
            'gemini' => $this->callGemini($prompt, $params),
            default => $this->callOpenAI($prompt, $params),
        };
    }

    protected function callOpenAI(string $prompt, array $params): string
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->timeout($this->timeout)
                ->post("{$this->baseUrl}/chat/completions", [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'system', 'content' => $this->getSystemPrompt($prompt)],
                        ['role' => 'user', 'content' => $this->buildPrompt($prompt, $params)],
                    ],
                    'temperature' => $this->temperature,
                    'max_tokens' => $prompt === 'generateEntireBook' ? 4000 : $this->maxTokens,
                ]);

            if ($response->failed()) {
                $this->handleError($response);
            }

            return $response->json('choices.0.message.content');
        } catch (\Exception $e) {
            logger()->error('AI Writer Error', ['provider' => 'openai', 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function callAnthropic(string $prompt, array $params): string
    {
        try {
            $response = Http::withHeaders([
                    'x-api-key' => $this->apiKey,
                    'anthropic-version' => '2023-06-01',
                    'Content-Type' => 'application/json',
                ])
                ->timeout($this->timeout)
                ->post("{$this->baseUrl}/messages", [
                    'model' => $this->model,
                    'max_tokens' => $prompt === 'generateEntireBook' ? 4000 : $this->maxTokens,
                    'system' => $this->getSystemPrompt($prompt),
                    'messages' => [
                        ['role' => 'user', 'content' => $this->buildPrompt($prompt, $params)],
                    ],
                    'temperature' => $this->temperature,
                ]);

            if ($response->failed()) {
                $this->handleError($response);
            }

            // Extract text from Anthropic response format
            $content = $response->json('content');
            if (is_array($content)) {
                $text = '';
                foreach ($content as $block) {
                    if (($block['type'] ?? '') === 'text') {
                        $text .= $block['text'];
                    }
                }
                return $text;
            }

            return $response->json('content.0.text') ?? '';
        } catch (\Exception $e) {
            logger()->error('AI Writer Error', ['provider' => 'anthropic', 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function callGemini(string $prompt, array $params): string
    {
        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->timeout($this->timeout)
                ->post("{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}", [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $this->getSystemPrompt($prompt) . "\n\n" . $this->buildPrompt($prompt, $params)],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => $this->temperature,
                        'maxOutputTokens' => $prompt === 'generateEntireBook' ? 4000 : $this->maxTokens,
                    ],
                ]);

            if ($response->failed()) {
                $this->handleError($response);
            }

            $candidates = $response->json('candidates');
            if (is_array($candidates) && count($candidates) > 0) {
                $parts = $candidates[0]['content']['parts'] ?? [];
                $text = '';
                foreach ($parts as $part) {
                    if (isset($part['text'])) {
                        $text .= $part['text'];
                    }
                }
                return $text;
            }

            return $response->json('candidates.0.content.parts.0.text') ?? '';
        } catch (\Exception $e) {
            logger()->error('AI Writer Error', ['provider' => 'gemini', 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function handleError($response): void
    {
        $status = $response->status();
        $errorMsg = match(true) {
            $status === 401 => match($this->provider) {
                'anthropic' => 'Neplatný API kľúč pre Claude. Skontrolujte ho v Profile > AI Nastavenia.',
                'gemini' => 'Neplatný API kľúč pre Gemini. Skontrolujte ho v Profile > AI Nastavenia.',
                default => 'Neplatný OpenAI API kľúč. Skontrolujte ho v Profile > AI Nastavenia.',
            },
            $status === 429 => 'Prekročený limit API požiadaviek. Skúste neskôr.',
            $status === 500 => 'Chyba AI servisu. Skúste neskôr.',
            default => $response->json('error.message') ?? $response->json('error') ?? 'Neznáma chyba AI API',
        };

        throw new \Exception($errorMsg);
    }

    // --- Prompts ---

    protected function getSystemPrompt(string $prompt): string
    {
        $lang = $this->language;

        return match($prompt) {
            'generateTitle' => "You are a professional book title generator. Generate creative, engaging book titles in {$lang}. Return only the titles, one per line.",
            'generateIntroduction' => "You are an expert writer. Write engaging introductions for books in {$lang}. Use clear paragraphs with proper formatting.",
            'generateConclusion' => "You are an expert writer. Write compelling conclusions for books in {$lang}. Summarize key points and leave a lasting impression.",
            'generateChapter' => "You are a professional author. Write well-structured, engaging chapters in {$lang} with proper formatting, paragraphs, and natural flow.",
            'generateEntireBook' => "You are a professional author. Create a complete book outline and content in {$lang} in JSON format: [{\"title\": \"Chapter Title\", \"content\": \"Full chapter content...\"}]. Write engaging content.",
            'checkGrammar' => "You are a grammar expert. Check the text for grammar errors and return a JSON array of objects with \"message\", \"line\", and \"suggestion\" fields. Language: {$lang}.",
            'checkStyle' => "You are a style expert. Analyze the writing style and return a JSON array with observations about tone, readability, and suggestions for improvement. Language: {$lang}.",
            'generateSEO' => "You are an SEO expert. Generate SEO-optimized meta descriptions and keywords. Return as JSON with \"title\", \"description\", and \"keywords\" fields. Language: {$lang}.",
            'generateCoverDescription' => "You are a book cover designer. Generate a detailed visual description for a book cover in {$lang}. Include colors, mood, composition, typography suggestions.",
            default => "You are a professional writer and editor. Help with writing tasks. Provide well-formatted text in {$lang}.",
        };
    }

    protected function buildPrompt(string $prompt, array $params): string
    {
        return match($prompt) {
            'generateTitle' => "Topic: {$params['topic']}, Genre: {$params['genre']}. Generate 5 book titles in {$this->language}.",
            'generateSubtitle' => "Title: {$params['title']}, Genre: {$params['genre']}. Generate 3 subtitles in {$this->language}.",
            'generateIntroduction' => "Title: {$params['title']}, Topic: {$params['topic']}, Words: {$params['word_count']}. Write an engaging introduction in {$this->language}.",
            'generateConclusion' => "Title: {$params['title']}, Summary: {$params['summary']}, Words: {$params['word_count']}. Write a compelling conclusion in {$this->language}.",
            'generateChapter' => "Book Title: {$params['book_title']}, Topic: {$params['topic']}, Words: {$params['word_count']}. Write a well-structured chapter in {$this->language} with paragraphs.",
            'generateEntireBook' => "Create a complete book with {$params['chapters']} chapters in {$this->language}. Book Title: '{$params['book_title']}'. Topic: {$params['topic']}. Genre: {$params['genre']}. Style: {$params['style']}. Return JSON array with 'title' and 'content' for each chapter. Make each chapter detailed (at least 500 words each).",
            'generateSections' => "Chapter: {$params['chapter_title']}. Generate {$params['sections_count']} section titles as a JSON array in {$this->language}.",
            'expandText' => "Expand this text by {$params['ratio']}x while maintaining meaning and quality: {$params['text']}",
            'shortenText' => "Shorten this text to {$params['ratio']}x its original length while keeping key points: {$params['text']}",
            'rewriteText' => "Rewrite in {$params['style']} style (formal, casual, academic, poetic, or technical): {$params['text']}",
            'continueWriting' => "Continue this text naturally with {$params['words']} more words, maintaining the same style and voice: {$params['text']}",
            'checkGrammar' => "Check grammar in {$this->language} and return JSON array of issues. Each issue must have 'message' and 'suggestion' fields. Text: {$params['text']}",
            'checkStyle' => "Analyze writing style in {$this->language} and return JSON array with observations about tone, readability, suggestions. Text: {$params['text']}",
            'generateFAQ' => "Topic: {$params['topic']}, Count: {$params['questions']}. Generate FAQ in {$this->language} in JSON format with 'question' and 'answer' fields.",
            'generateSEO' => "Title: {$params['title']}, Description: {$params['description']}. Generate SEO meta data in {$this->language} in JSON.",
            'generateCoverDescription' => "Book title: {$params['title']}, Genre: {$params['genre']}, Mood: {$params['mood']}. Generate a visual cover description in {$this->language}.",
            default => json_encode($params),
        };
    }

    public function validateApiKey(string $apiKey): bool
    {
        try {
            $endpoint = match($this->provider) {
                'anthropic' => "{$this->baseUrl}/messages",
                'gemini' => "{$this->baseUrl}/models?key={$apiKey}",
                default => "{$this->baseUrl}/models",
            };

            $headers = match($this->provider) {
                'anthropic' => [
                    'x-api-key' => $apiKey,
                    'anthropic-version' => '2023-06-01',
                ],
                default => ['Authorization' => "Bearer {$apiKey}"],
            };

            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->get($endpoint);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getProviderModels(): array
    {
        return $this->providerDefaults[$this->provider]['models'] ?? $this->providerDefaults['openai']['models'];
    }

    public function getAvailableProviders(): array
    {
        return [
            'openai' => 'OpenAI (GPT-4, GPT-3.5)',
            'anthropic' => 'Anthropic (Claude 3)',
            'gemini' => 'Google (Gemini)',
        ];
    }

    public function getAvailableLanguages(): array
    {
        return [
            'sk' => 'Slovenčina',
            'cs' => 'Čeština',
            'en' => 'English',
            'de' => 'Deutsch',
            'pl' => 'Polski',
            'hu' => 'Magyar',
        ];
    }
}
