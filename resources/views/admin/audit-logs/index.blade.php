@extends('layouts.app')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1>System Audit Logs</h1>
    <p class="text-muted">Tracking automated and manual system operations.</p>
</div>

<div class="card">
    <form action="{{ route('admin.audit-logs.index') }}" method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        
        <select name="action" class="form-control" style="flex: 1; min-width: 150px;">
            <option value="">All Actions</option>
            @foreach($actions as $action)
                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
            @endforeach
        </select>

        <select name="user_id" class="form-control" style="flex: 1; min-width: 150px;">
            <option value="">All Users</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->badge_number ?? 'Admin' }})</option>
            @endforeach
        </select>

        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" style="flex: 1; min-width: 150px;">
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" style="flex: 1; min-width: 150px;">

        <button type="submit" class="btn btn-primary" style="white-space: nowrap;">Apply Filters</button>
        <a href="{{ route('admin.audit-logs.index') }}" class="btn" style="background: #e2e8f0; white-space: nowrap;">Reset</a>
    </form>

    <table class="table" style="font-size: 0.875rem;">
        <thead>
            <tr>
                <th>Time</th>
                <th>Officer / User</th>
                <th>Action</th>
                <th>Subject</th>
                <th>Description</th>
                <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td style="white-space: nowrap;">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                <td>
                    <strong>{{ $log->user->name ?? 'System' }}</strong>
                </td>
                <td>
                    <span class="badge" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1;">
                        {{ strtoupper($log->action) }}
                    </span>
                </td>
                <td>
                    {{ $log->subject_type }} <small class="text-muted">#{{ $log->subject_id }}</small>
                </td>
                <td>{{ $log->description }}</td>
                <td style="font-family: monospace; color: var(--text-muted);">{{ $log->ip_address }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">No audit logs found matching the criteria.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        {{ $logs->links() }}
    </div>
</div>
@endsection
