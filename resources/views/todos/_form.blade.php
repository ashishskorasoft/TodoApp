@php
    $labelsString = old('labels', isset($todo->labels) ? implode(', ', (array) $todo->labels) : '');
    $subtasks = old('subtasks', isset($todo) && $todo->relationLoaded('checklistItems') ? $todo->checklistItems->pluck('title')->all() : ['', '']);
    $repeatWeekdays = old('repeat_weekdays', $todo->repeat_weekdays ?? []);
    $dueAt = old('due_at', isset($todo->due_at) && $todo->due_at ? $todo->due_at->format('Y-m-d\TH:i') : '');
    $dueDateValue = old('due_date', isset($todo->due_at) && $todo->due_at ? $todo->due_at->format('Y-m-d') : '');
    $dueHourValue = old('due_hour', isset($todo->due_at) && $todo->due_at ? $todo->due_at->format('h') : '');
    $dueMinuteValue = old('due_minute', isset($todo->due_at) && $todo->due_at ? $todo->due_at->format('i') : '');
    $duePeriodValue = old('due_period', isset($todo->due_at) && $todo->due_at ? $todo->due_at->format('A') : 'AM');
@endphp
<div class="app-form">
    <input type="hidden" name="due_at" id="dueAtHidden" value="{{ $dueAt }}">

    <div class="form-group">
        <label class="form-label"><i class="bi bi-check2-square me-2"></i>Task Title</label>
        <input type="text" name="title" value="{{ old('title', $todo->title ?? '') }}" class="form-control" placeholder="What needs to be done?">
        @error('title')<div class="small-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label class="form-label"><i class="bi bi-journal-text me-2"></i>Notes</label>
        <textarea name="notes" class="form-control" placeholder="Add task notes, checklist context, or next action...">{{ old('notes', $todo->notes ?? '') }}</textarea>
        @error('notes')<div class="small-error">{{ $message }}</div>@enderror
    </div>

    <div class="inline-fields">
        <div class="form-group">
            <label class="form-label"><i class="bi bi-flag me-2"></i>Priority</label>
            <select name="priority" class="form-select">
                @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $key => $label)
                    <option value="{{ $key }}" @selected(old('priority', $todo->priority ?? 'medium') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            @error('priority')<div class="small-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label"><i class="bi bi-ui-checks-grid me-2"></i>Status</label>
            <select name="status" class="form-select">
                <option value="pending" @selected(old('status', $todo->status ?? 'pending') === 'pending')>Pending</option>
                <option value="completed" @selected(old('status', $todo->status ?? 'pending') === 'completed')>Completed</option>
            </select>
            @error('status')<div class="small-error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="form-group">
        <label class="form-label"><i class="bi bi-calendar-event me-2"></i>Due Date</label>
        <input type="date" name="due_date" id="dueDateInput" value="{{ $dueDateValue }}" class="form-control">
        @error('due_at')<div class="small-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label class="form-label"><i class="bi bi-clock me-2"></i>Time (12-hour format)</label>
        <div class="time-picker-grid">
            <select name="due_hour" id="dueHourInput" class="form-select time-select">
                <option value="">Hour</option>
                @for($h = 1; $h <= 12; $h++)
                    @php $value = str_pad((string) $h, 2, '0', STR_PAD_LEFT); @endphp
                    <option value="{{ $value }}" @selected($dueHourValue === $value)>{{ $value }}</option>
                @endfor
            </select>
            <select name="due_minute" id="dueMinuteInput" class="form-select time-select">
                <option value="">Min</option>
                @foreach(['00','05','10','15','20','25','30','35','40','45','50','55'] as $minute)
                    <option value="{{ $minute }}" @selected($dueMinuteValue === $minute)>{{ $minute }}</option>
                @endforeach
            </select>
            <select name="due_period" id="duePeriodInput" class="form-select time-select">
                <option value="AM" @selected($duePeriodValue === 'AM')>AM</option>
                <option value="PM" @selected($duePeriodValue === 'PM')>PM</option>
            </select>
        </div>
        <div class="time-helper"><i class="bi bi-info-circle me-1"></i>Choose date and time. The app saves it automatically in one due timestamp.</div>
    </div>

    <div class="form-group">
        <label class="form-label"><i class="bi bi-bell me-2"></i>Reminder (minutes before)</label>
        <input type="number" name="reminder_minutes_before" min="0" max="10080" value="{{ old('reminder_minutes_before', $todo->reminder_minutes_before ?? 30) }}" class="form-control" placeholder="30">
        @error('reminder_minutes_before')<div class="small-error">{{ $message }}</div>@enderror
    </div>

    <div class="inline-fields">
        <div class="form-group">
            <label class="form-label"><i class="bi bi-tags me-2"></i>Tags</label>
            <input type="text" name="labels" value="{{ $labelsString }}" class="form-control" placeholder="work, urgent, home">
            @error('labels')<div class="small-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label"><i class="bi bi-arrow-repeat me-2"></i>Repeat</label>
            <select name="repeat_type" class="form-select">
                <option value="">Does not repeat</option>
                <option value="daily" @selected(old('repeat_type', $todo->repeat_type ?? '') === 'daily')>Daily</option>
                <option value="weekly" @selected(old('repeat_type', $todo->repeat_type ?? '') === 'weekly')>Weekly</option>
                <option value="monthly" @selected(old('repeat_type', $todo->repeat_type ?? '') === 'monthly')>Monthly</option>
                <option value="custom" @selected(old('repeat_type', $todo->repeat_type ?? '') === 'custom')>Custom Interval</option>
            </select>
            @error('repeat_type')<div class="small-error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="inline-fields">
        <div class="form-group">
            <label class="form-label"><i class="bi bi-sliders me-2"></i>Custom Interval</label>
            <input type="number" name="repeat_interval" min="1" max="365" value="{{ old('repeat_interval', $todo->repeat_interval ?? 1) }}" class="form-control" placeholder="Every X cycles">
            @error('repeat_interval')<div class="small-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label"><i class="bi bi-calendar-week me-2"></i>Weekly Days</label>
            <div class="weekday-grid">
                @foreach(['mon' => 'M', 'tue' => 'T', 'wed' => 'W', 'thu' => 'T', 'fri' => 'F', 'sat' => 'S', 'sun' => 'S'] as $day => $label)
                    <label class="weekday-check">
                        <input type="checkbox" name="repeat_weekdays[]" value="{{ $day }}" @checked(in_array($day, $repeatWeekdays, true))>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            @error('repeat_weekdays')<div class="small-error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="form-group">
        <label class="form-label"><i class="bi bi-list-check me-2"></i>Subtasks</label>
        <div class="subtask-stack">
            @for($i = 0; $i < 4; $i++)
                <input type="text" name="subtasks[]" value="{{ $subtasks[$i] ?? '' }}" class="form-control" placeholder="Subtask {{ $i + 1 }}">
            @endfor
        </div>
        @error('subtasks')<div class="small-error">{{ $message }}</div>@enderror
    </div>
</div>
