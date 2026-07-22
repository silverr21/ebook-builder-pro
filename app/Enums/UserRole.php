<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case AUTHOR = 'author';
    case CORRECTOR = 'corrector';
    case DESIGNER = 'designer';
    case TRANSLATOR = 'translator';
    case READER = 'reader';
    case SUBSCRIBER = 'subscriber';
    case GUEST = 'guest';

    public function label(): string
    {
        return match($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::EDITOR => 'Editor',
            self::AUTHOR => 'Autor',
            self::CORRECTOR => 'Korektor',
            self::DESIGNER => 'Grafik',
            self::TRANSLATOR => 'Prekladateľ',
            self::READER => 'Čitateľ',
            self::SUBSCRIBER => 'Predplatiteľ',
            self::GUEST => 'Hosť',
        };
    }

    public function permissions(): array
    {
        return match($this) {
            self::SUPER_ADMIN => ['*'],
            self::ADMIN => ['books.*', 'users.*', 'analytics.*', 'settings.*'],
            self::EDITOR => ['books.edit', 'books.view', 'chapters.manage', 'sections.manage'],
            self::AUTHOR => ['books.create', 'books.edit', 'books.view', 'chapters.manage', 'export.*'],
            self::CORRECTOR => ['books.view', 'sections.review', 'comments.*'],
            self::DESIGNER => ['design.manage', 'cover.create', 'images.manage'],
            self::TRANSLATOR => ['translations.*', 'books.view'],
            self::READER => ['books.read', 'comments.view'],
            self::SUBSCRIBER => ['books.read', 'audiobooks.listen', 'comments.*'],
            self::GUEST => ['books.read:public'],
        };
    }

    public function isAdmin(): bool
    {
        return in_array($this, [self::SUPER_ADMIN, self::ADMIN]);
    }

    public function canCreateBooks(): bool
    {
        return in_array($this, [self::SUPER_ADMIN, self::ADMIN, self::AUTHOR, self::EDITOR]);
    }
}
