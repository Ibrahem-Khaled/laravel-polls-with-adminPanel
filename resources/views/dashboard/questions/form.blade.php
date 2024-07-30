<!-- resources/views/questions/partials/form.blade.php -->

<div class="mb-3">
    <label for="question" class="form-label">Question</label>
    <input type="text" class="form-control" id="question" name="question"
        value="{{ old('question', $question->question) }}" required>
</div>
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control" id="description" name="description">{{ old('description', $question->description) }}</textarea>
</div>
<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select class="form-control" id="status" name="status">
        <option value="active" {{ old('status', $question->status) == 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ old('status', $question->status) == 'inactive' ? 'selected' : '' }}>Inactive
        </option>
    </select>
</div>
