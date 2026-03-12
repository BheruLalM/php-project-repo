@extends('layouts.app')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1>{{ isset($criminal) ? 'Edit' : 'New' }} Criminal Record</h1>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ isset($criminal) ? route(auth()->user()->role . '.criminals.update', $criminal->id) : route(auth()->user()->role . '.criminals.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($criminal)) @method('PUT') @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $criminal->full_name ?? '') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Alias / Street Name</label>
                <input type="text" name="alias" class="form-control" value="{{ old('alias', $criminal->alias ?? '') }}">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', optional($criminal->date_of_birth ?? null)->format('Y-m-d')) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Nationality</label>
                <input type="text" name="nationality" class="form-control" value="{{ old('nationality', $criminal->nationality ?? '') }}">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                @foreach(['wanted', 'arrested', 'released', 'deceased'] as $status)
                    <option value="{{ $status }}" {{ old('status', $criminal->status ?? '') == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label class="form-label">Physical Markers (Scars, Tattoos, Marks)</label>
            <textarea name="physical_markers" class="form-control" rows="3">{{ old('physical_markers', $criminal->physical_markers ?? '') }}</textarea>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label class="form-label">Last Known Address</label>
            <textarea name="address" class="form-control" rows="2">{{ old('address', $criminal->address ?? '') }}</textarea>
        </div>

        <div class="form-group" style="margin-bottom: 2rem;">
            <label class="form-label">Mugshot Photo (JPEG/PNG, max 2MB)</label>
            @if(isset($criminal) && $criminal->photo_path)
                <div style="margin-bottom: 0.5rem;">
                    <img src="{{ $criminal->photo_url }}" alt="Current" style="width: 100px; border-radius: 0.5rem;">
                </div>
            @endif
            <input type="file" name="photo" class="form-control">
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">Save Criminal Record</button>
            <a href="{{ route(auth()->user()->role . '.criminals.index') }}" class="btn" style="background: #e2e8f0;">Cancel</a>
        </div>
    </form>
</div>
@endsection
