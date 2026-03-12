@extends('layouts.app')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1>{{ isset($officer) ? 'Update Officer Profile' : 'Register Law Enforcement Officer' }}</h1>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ isset($officer) ? route('admin.officers.update', $officer->id) : route('admin.officers.store') }}" method="POST">
        @csrf
        @if(isset($officer)) @method('PUT') @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $officer->name ?? '') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Official Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $officer->email ?? '') }}" required>
            </div>
        </div>

        @if(!isset($officer))
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label class="form-label">System Password</label>
                <input type="password" name="password" class="form-control" required minlength="8">
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required minlength="8">
            </div>
        </div>
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
            <div class="form-group">
                <label class="form-label">Badge Number</label>
                <input type="text" name="badge_number" class="form-control" value="{{ old('badge_number', $officer->badge_number ?? '') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Rank</label>
                <input type="text" name="rank" class="form-control" value="{{ old('rank', $officer->rank ?? '') }}" placeholder="E.g., Inspector, Sergeant" required>
            </div>
            <div class="form-group">
                <label class="form-label">Station / Branch</label>
                <input type="text" name="station_branch" class="form-control" value="{{ old('station_branch', $officer->station_branch ?? '') }}" required>
            </div>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">{{ isset($officer) ? 'Update Officer Portfolio' : 'Authorize Officer Access' }}</button>
            <a href="{{ route('admin.officers.index') }}" class="btn" style="background: #e2e8f0;">Cancel</a>
        </div>
    </form>
</div>
@endsection
