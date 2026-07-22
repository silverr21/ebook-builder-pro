<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Section;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()?->isAdmin()) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function dashboard(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'total_chapters' => Chapter::count(),
            'total_sections' => Section::count(),
            'total_words' => Section::sum('word_count'),
            'total_views' => Book::sum('view_count'),
            'published_books' => Book::where('status', 'published')->count(),
        ];

        // Users by role
        $usersByRole = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->orderBy('count', 'desc')
            ->get();

        // Recent users
        $recentUsers = User::latest()->take(10)->get();

        // Admin users
        $adminUsers = User::whereIn('role', ['super_admin', 'admin'])->get();

        // Top books by views
        $topBooks = Book::with('user')
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get();

        // Recent books
        $recentBooks = Book::with('user')
            ->latest()
            ->take(10)
            ->get();

        // System info
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug'),
            'db_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
        ];

        return view('admin.dashboard', compact(
            'stats', 'usersByRole', 'recentUsers', 'adminUsers',
            'topBooks', 'recentBooks', 'systemInfo'
        ));
    }

    public function users(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(20);
        $roles = UserRole::cases();

        return view('admin.users', compact('users', 'roles'));
    }

    public function createUser(): View
    {
        $roles = UserRole::cases();
        return view('admin.user-create', compact('roles'));
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:' . implode(',', array_column(UserRole::cases(), 'value'))],
            'is_active' => ['boolean'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['email_verified_at'] = $request->boolean('verified') ? now() : null;

        User::create($validated);

        return redirect()->route('admin.users')->with('success', 'Používateľ bol vytvorený.');
    }

    public function editUser(User $user): View
    {
        $roles = UserRole::cases();
        return view('admin.user-edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'string', 'in:' . implode(',', array_column(UserRole::cases(), 'value'))],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['email_verified_at'] = $request->boolean('verified') ? now() : null;

        if ($request->filled('password')) {
            $request->validate(['password' => ['string', 'min:8', 'confirmed']]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'Používateľ bol aktualizovaný.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->isAdmin() && User::whereIn('role', ['super_admin', 'admin'])->count() <= 1) {
            return back()->with('error', 'Nemôžete zmazať posledného administrátora.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Používateľ bol zmazaný.');
    }

    public function books(Request $request): View
    {
        $query = Book::with('user');

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $books = $query->latest()->paginate(20);

        return view('admin.books', compact('books'));
    }

    public function deleteBook(Book $book): RedirectResponse
    {
        $book->delete();
        return redirect()->route('admin.books')->with('success', 'Kniha bola zmazaná.');
    }
}
