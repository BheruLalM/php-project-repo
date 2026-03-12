@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>Officer Management</h1>
    <a href="{{ route('admin.officers.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Register New Officer
    </a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Badge #</th>
                <th>Name</th>
                <th>Rank</th>
                <th>Station / Branch</th>
                <th>System Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($officers as $officer)
            <tr>
                <td><strong>{{ $officer->badge_number }}</strong></td>
                <td>{{ $officer->name }}<br><small class="text-muted">{{ $officer->email }}</small></td>
                <td>{{ $officer->rank }}</td>
                <td>{{ $officer->station_branch }}</td>
                <td>
                    @if($officer->is_active)
                        <span class="badge badge-closed">Active</span>
                    @else
                        <span class="badge badge-archived">Deactivated</span>
                    @endif
                </td>
                <td>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('admin.officers.edit', $officer->id) }}" class="btn" style="background: #f1f5f9; padding: 0.2rem 0.6rem;"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                        
                        @if($officer->is_active)
                            <form action="{{ route('admin.officers.destroy', $officer->id) }}" method="POST" onsubmit="return confirm('Suspend this officer\'s access to the system?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.2rem 0.6rem;"><i class="fa-solid fa-ban"></i> Deactivate</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        {{ $officers->links() }}
    </div>
</div>
@endsection
