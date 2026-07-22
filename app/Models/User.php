<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'avatar',
        'role', 'is_active', 'email_verified_at', 'two_factor_enabled',
        'ai_api_key', 'ai_model', 'ai_enabled',
        'ai_provider', 'ai_temperature', 'ai_max_tokens', 'ai_timeout',
        'ai_language', 'ai_api_url'
    ];

    protected $hidden = ['password', 'remember_token', 'ai_api_key'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'ai_enabled' => 'boolean',
        'ai_temperature' => 'string',
        'ai_max_tokens' => 'integer',
        'ai_timeout' => 'integer',
        'password' => 'hashed',
    ];

    public function getAiApiKey(): ?string
    {
        return $this->ai_api_key ?: config('services.' . $this->ai_provider . '.api_key', config('services.openai.api_key'));
    }

    public function hasAiApiKey(): bool
    {
        return !empty($this->ai_api_key) || !empty(config('services.openai.api_key')) || !empty(config('services.' . $this->ai_provider . '.api_key'));
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function isAdmin()
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isAuthor()
    {
        return in_array($this->role, ['super_admin', 'admin', 'author', 'editor']);
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function hasAnyRole($roles)
    {
        return in_array($this->role, (array) $roles);
    }
}
