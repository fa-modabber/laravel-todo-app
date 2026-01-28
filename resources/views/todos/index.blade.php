@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="">Todos</h5>
            <a href="{{ route('todos.create') }}" class="btn btn-dark">create</a>
        </div>
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($todos as $todo)
                        <tr>
                            <td>
                                <img src="{{ asset('/images/' . $todo->image) }}" alt="todos image" class="rounded todo-img">
                            </td>
                            <td>{{ $todo->title }}</td>
                            <td>{{ $todo->category->title }}</td>
                            <td>
                                <a href="{{ route('todos.show', ['todo' => $todo->id]) }}"
                                    class="btn btn-sm btn-secondary">Show</a>
                                @if ($todo->status)
                                    <button disabled class="btn btn-sm btn-success">Completed</button>
                                @else
                                    <a href="{{ route('todos.complete', ['todo' => $todo->id]) }}" class="btn btn-sm btn-primary">Done?</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            {{ $todos->links('layout.paginate') }}
        </div>
    </div>
@endsection
