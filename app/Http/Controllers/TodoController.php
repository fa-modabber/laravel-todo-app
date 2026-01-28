<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::paginate(3);
        return view('todos.index', compact('todos'));
    }

    public function show(Todo $todo)
    {
        return view('todos.show', compact('todo'));
    }

    public function complete(Todo $todo)
    {
        $todo->update([
            'status' => 1
        ]);
        return redirect()->route('todos.index');
    }

    public function create()
    {
        Gate::authorize('create', Todo::class);
        $categories = Category::all();
        return view('todos.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:5',
            'description' => 'required|min:5',
            'image' => 'required|max:2000|image',
            'category_id' => 'required|integer'
        ]);

        $filename = time() . "_" . $request->image->getClientOriginalName();
        $request->image->storeAs('\images', $filename);
        Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $filename,
            'category_id' => $request->category_id
        ]);
        return redirect()->route('todos.index');
    }

    public function edit(Todo $todo)
    {
        $categories = Category::all();
        return view('todos.edit', compact('todo', 'categories'));
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|min:5',
            'description' => 'required|min:5',
            'image' => 'nullable|max:2000|image',
            'category_id' => 'required|integer'
        ]);

        if ($request->hasFile('image')) {
            // Storage::delete('/images/' . $todo->image);
            $filename = time() . "_" . $request->image->getClientOriginalName();
            $request->image->storeAs('\images', $filename);
        }

        $todo->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->hasFile('image') ? $filename : $todo->image,
            'category_id' => $request->category_id
        ]);
        return redirect()->route('todos.show', ['todo' => $todo->id]);
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->route('todos.index');
    }
}
