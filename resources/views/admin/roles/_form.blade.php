<div class="app-card section-card section-space">
    <div class="form-stack">
        <div class="input-shell">
            <label class="field-label">Role Name</label>
            <input type="text" name="name" value="{{ old('name', $role->name) }}" class="form-control" required>
            @error('name')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="input-shell">
            <label class="field-label">Role Code</label>
            <input type="text" name="code" value="{{ old('code', $role->code) }}" class="form-control" {{ !empty($role->is_system) ? 'readonly' : '' }} required>
            <div class="section-caption mt-1">Lowercase letters, numbers, and underscores only.</div>
            @error('code')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="input-shell">
            <label class="field-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $role->description) }}</textarea>
            @error('description')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="input-shell">
            <label class="field-label">Sort Order</label>
            <input type="number" min="1" max="999" name="sort_order" value="{{ old('sort_order', $role->sort_order ?: 1) }}" class="form-control">
            @error('sort_order')<div class="field-error">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="app-card section-card section-space">
    <div class="section-header">
        <div>
            <h3 class="section-title">Permission Assignment</h3>
            <p class="section-caption">Assign only the capabilities this role really needs.</p>
        </div>
    </div>

    <div class="task-stack">
        @foreach($permissionGroups as $group => $permissions)
            <div class="app-card" style="padding:14px; background: rgba(126,87,194,0.03); border:1px solid rgba(126,87,194,0.08); border-radius:18px;">
                <div class="section-title" style="font-size:14px; margin-bottom:10px;">{{ ucfirst($group) }}</div>
                <div class="task-stack">
                    @foreach($permissions as $permission)
                        <label class="switch-row" style="align-items:flex-start; gap:12px;">
                            <span>
                                <strong style="display:block; font-size:13px; color:#1f2937;">{{ $permission->name }}</strong>
                                <small style="display:block; color:#6b7280; font-size:12px;">{{ $permission->code }}</small>
                            </span>
                            <input type="checkbox" name="permissions[]" value="{{ $permission->code }}" @checked(in_array($permission->code, old('permissions', $selectedPermissions), true))>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="task-form-actions">
    <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i>{{ $submitLabel }}</button>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-light-custom">Back</a>
</div>
