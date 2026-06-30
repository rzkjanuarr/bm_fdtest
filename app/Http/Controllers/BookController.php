<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $books = Book::when($search, function ($query, $search) {
            $query->where('title', 'ilike', '%' . $search . '%')
                  ->orWhere('author', 'ilike', '%' . $search . '%');
        })->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $today = now();
        $weeklyLabels = [];
        $weeklyValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $weeklyLabels[] = $date->locale('id')->shortDayName;
            $weeklyValues[] = Book::whereDate('created_at', $date->toDateString())->count();
        }

        $newBooksThisMonth = Book::whereMonth('created_at', $today->month)->whereYear('created_at', $today->year)->count();
        $totalBooks = Book::count();
        $activeUsersCount = \App\Models\User::where('status', 1)->count();

        return Inertia::render('Book', [
            'books' => $books,
            'weeklyLabels' => $weeklyLabels,
            'weeklyValues' => $weeklyValues,
            'newBooksThisMonth' => $newBooksThisMonth,
            'totalBooks' => $totalBooks,
            'search' => $search,
            'totalBooksCount' => $totalBooks,
            'activeUsersCount' => $activeUsersCount
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'description' => $request->description,
            'thumbnail' => $request->thumbnail,
            'rating' => $request->rating,
        ]);

        return redirect()->route('book');
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'description' => $request->description,
            'thumbnail' => $request->thumbnail,
            'rating' => $request->rating,
        ]);

        return redirect()->route('book');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('book');
    }
}
