<div class="row g-4">
    <div class="col-12">
        <label class="form-label fw-semibold">Title</label>
        <input
            type="text"
            name="title"
            class="form-control @error('title') is-invalid @enderror"
            value="{{ old('title', $todo->title ?? '') }}"
            placeholder="Enter task title"
        >
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Description</label>
        <textarea
            name="description"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Write task details here..."
        >{{ old('description', $todo->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            <option value="pending" {{ old('status', $todo->status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="completed" {{ old('status', $todo->status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Due Date</label>
        <input
            type="date"
            name="due_date"
            class="form-control @error('due_date') is-invalid @enderror"
            value="{{ old('due_date', isset($todo->due_date) && $todo->due_date ? $todo->due_date->format('Y-m-d') : '') }}"
        >
        @error('due_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>