@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>Criminal Records</h1>
    <a href="{{ route(auth()->user()->role . '.criminals.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> New Criminal Record
    </a>
</div>

<div class="card">
    <form action="{{ route(auth()->user()->role . '.criminals.index') }}" method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
        <input type="text" name="search" class="form-control" placeholder="Search by name or alias..." value="{{ request('search') }}" style="flex: 1;">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="{{ route(auth()->user()->role . '.criminals.index') }}" class="btn btn-secondary" style="background: #e2e8f0;">Reset</a>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Full Name</th>
                <th>Alias</th>
                <th>Status</th>
                <th>D.O.B</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($criminals as $criminal)
            <tr>
                <td>
                    <img src="{{ $criminal->photo_url }}" alt="Mugshot" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; background: #eee;">
                </td>
                <td><strong>{{ $criminal->full_name }}</strong></td>
                <td>{{ $criminal->alias ?? '-' }}</td>
                <td>
                    <span class="badge badge-{{ $criminal->status }}">
                        {{ ucfirst($criminal->status) }}
                    </span>
                </td>
                <td>{{ $criminal->date_of_birth ? $criminal->date_of_birth->format('d M, Y') : '-' }}</td>
                <td>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route(auth()->user()->role . '.criminals.show', $criminal->id) }}" class="btn" style="background: #f1f5f9; padding: 0.2rem 0.6rem;"><i class="fa-solid fa-eye"></i></a>
                        <a href="{{ route(auth()->user()->role . '.criminals.edit', $criminal->id) }}" class="btn" style="background: #f1f5f9; padding: 0.2rem 0.6rem;"><i class="fa-solid fa-pen-to-square"></i></a>
                        @can('delete', $criminal)
                            <form action="{{ route('admin.criminals.destroy', $criminal->id) }}" method="POST" onsubmit="return confirm('Archive/Delete this record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.2rem 0.6rem;"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        {{ $criminals->withQueryString()->links() }}
    </div>
</div>
@endsection
