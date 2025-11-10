# Checkbox Multi-Select Implementation Task

## Context
This Laravel application is a photo ordering system for schools. Currently, when creating/editing Events and Schools, the relationship management uses standard `<select multiple>` dropdowns which are not user-friendly.

## Goal
Implement a clean, checkbox-based multi-select UI for managing relationships between:
- **Events** ↔ **Schools** (many-to-many relationship)
- **Schools** ↔ **Events** (inverse of above)

## Current Implementation

### Event Model
- Has `schools()` belongsToMany relationship
- Uses pivot table: `event_school` (columns: `event_id`, `school_id`)

### School Model
- Has `events()` belongsToMany relationship
- Same pivot table: `event_school`

### Current Forms Location
- **Events Create/Edit**: `resources/views/admin/events/create.blade.php` and `edit.blade.php`
- **Schools Create/Edit**: `resources/views/admin/schools/create.blade.php` and `edit.blade.php`

### Current Form Implementation
Uses standard select multiple:
```blade
<select name="schools[]" multiple>
    @foreach($schools as $school)
        <option value="{{ $school->id }}">{{ $school->name }}</option>
    @endforeach
</select>
```

## Requirements

### UI Design
1. **Replace select multiple with checkbox list**
   - Display as a vertical list of checkboxes
   - Each checkbox shows the school/event name
   - Use clean, minimalistic styling matching the rest of the app
   - Follow the outlined button style pattern used elsewhere (indigo border, transparent background)

2. **Styling Requirements**
   - Dark mode support (uses `dark:` Tailwind classes)
   - Consistent with existing button patterns:
     - Border: `border border-gray-300 dark:border-gray-600`
     - Text: `text-gray-900 dark:text-gray-100`
     - Hover states for accessibility
   - Maximum height with scroll if many items (e.g., `max-h-60 overflow-y-auto`)
   - Proper spacing between items

3. **Pre-selection**
   - On edit forms, pre-check the currently associated items
   - For Events: pre-check schools already assigned to the event
   - For Schools: pre-check events already assigned to the school

### Technical Implementation

#### Blade Components to Update

**Events Create Form** (`resources/views/admin/events/create.blade.php`):
```blade
<div class="mt-4">
    <x-input-label for="schools" :value="__('Schools')" />
    <div class="mt-2 p-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 max-h-60 overflow-y-auto">
        @foreach($schools as $school)
            <label class="flex items-center py-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 px-2 rounded">
                <input type="checkbox" name="schools[]" value="{{ $school->id }}"
                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                <span class="ml-2 text-sm text-gray-900 dark:text-gray-100">{{ $school->name }}</span>
            </label>
        @endforeach
    </div>
    <x-input-error class="mt-2" :messages="$errors->get('schools')" />
</div>
```

**Events Edit Form** (`resources/views/admin/events/edit.blade.php`):
```blade
<div class="mt-4">
    <x-input-label for="schools" :value="__('Schools')" />
    <div class="mt-2 p-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 max-h-60 overflow-y-auto">
        @foreach($schools as $school)
            <label class="flex items-center py-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 px-2 rounded">
                <input type="checkbox" name="schools[]" value="{{ $school->id }}"
                       {{ in_array($school->id, $event->schools->pluck('id')->toArray()) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                <span class="ml-2 text-sm text-gray-900 dark:text-gray-100">{{ $school->name }}</span>
            </label>
        @endforeach
    </div>
    <x-input-error class="mt-2" :messages="$errors->get('schools')" />
</div>
```

**Similar implementation needed for**:
- `resources/views/admin/schools/create.blade.php` (with events)
- `resources/views/admin/schools/edit.blade.php` (with events, pre-checked)

#### Controller Verification

**EventController**:
- `create()` method should pass `$schools = School::orderBy('name')->get()` to view
- `store()` method should handle `$request->input('schools', [])` and sync relationships
- `edit()` method should pass both `$event` and `$schools` to view
- `update()` method should sync relationships using `$event->schools()->sync($request->input('schools', []))`

**SchoolController**:
- `create()` method should pass `$events = Event::orderBy('name')->get()` to view
- `store()` method should handle `$request->input('events', [])` and sync relationships
- `edit()` method should pass both `$school` and `$events` to view
- `update()` method should sync relationships using `$school->events()->sync($request->input('events', []))`

### Validation

Update Form Request classes if they exist:
- Events: ensure `schools` is `array|nullable`
- Schools: ensure `events` is `array|nullable`
- Each array item should be `exists:schools,id` or `exists:events,id`

### Testing Checklist

After implementation:
- [ ] Create new event with multiple schools selected
- [ ] Edit event and change school assignments
- [ ] Create event with no schools selected (should work)
- [ ] Create new school with multiple events selected
- [ ] Edit school and change event assignments
- [ ] Create school with no events selected (should work)
- [ ] Verify dark mode styling looks good
- [ ] Verify scrolling works when many items exist
- [ ] Verify hover states are visible
- [ ] Test on mobile devices (checkboxes should be touch-friendly)

## Design Notes

- Keep it **clean and minimalistic** - matching the overall app aesthetic
- The checkbox container should have subtle border, not heavy
- Use consistent spacing (py-2 for each item)
- Hover effect helps users see what they're about to select
- Scroll container ensures form doesn't become too long with many items
- Proper focus states for accessibility (keyboard navigation)

## Files to Modify

1. `resources/views/admin/events/create.blade.php`
2. `resources/views/admin/events/edit.blade.php`
3. `resources/views/admin/schools/create.blade.php`
4. `resources/views/admin/schools/edit.blade.php`
5. Verify controllers:
   - `app/Http/Controllers/Admin/EventController.php`
   - `app/Http/Controllers/Admin/SchoolController.php`
6. Verify/update validation:
   - `app/Http/Requests/Admin/StoreEventRequest.php` (if exists)
   - `app/Http/Requests/Admin/UpdateEventRequest.php` (if exists)
   - `app/Http/Requests/Admin/StoreSchoolRequest.php` (if exists)
   - `app/Http/Requests/Admin/UpdateSchoolRequest.php` (if exists)

## Current Tech Stack
- **Laravel** with Breeze authentication
- **Tailwind CSS** with dark mode
- **Alpine.js** available for interactions if needed
- Blade components: `<x-input-label>`, `<x-input-error>` already in use

## Success Criteria
- No more `<select multiple>` dropdowns
- Clean checkbox UI that matches app design
- Proper pre-selection on edit forms
- Relationships saved correctly to database
- Dark mode fully supported
- Mobile-friendly touch targets
