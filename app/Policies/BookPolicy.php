<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Book;

class BookPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Book $book): bool
    {
        return $user->id === $book->user_id || $book->status === 'published' || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAuthor();
    }

    public function update(User $user, Book $book): bool
    {
        return $user->id === $book->user_id || $user->isAdmin();
    }

    public function delete(User $user, Book $book): bool
    {
        return $user->id === $book->user_id || $user->isAdmin();
    }

    public function publish(User $user, Book $book): bool
    {
        return ($user->id === $book->user_id || $user->isAdmin()) && $book->status === 'draft';
    }
}
