<!-- resources/views/polls/partials/form.blade.php -->

<div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $poll->title) }}"
        required>
</div>
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control" id="description" name="description">{{ old('description', $poll->description) }}</textarea>
</div>
<div class="mb-3">
    <label for="price" class="form-label">Price</label>
    <input type="number" step="0.01" class="form-control" id="price" name="price"
        value="{{ old('price', $poll->price) }}" required>
</div>
<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select class="form-control" id="status" name="status">
        <option value="active" {{ old('status', $poll->status) == 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ old('status', $poll->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
</div>
<div class="mb-3">
    <label for="visibility" class="form-label">Visibility</label>
    <select class="form-control" id="visibility" name="visibility">
        <option value="public" {{ old('visibility', $poll->visibility) == 'public' ? 'selected' : '' }}>Public</option>
        <option value="private" {{ old('visibility', $poll->visibility) == 'private' ? 'selected' : '' }}>Private
        </option>
    </select>
</div>
<div class="mb-3">
    <label for="image" class="form-label">Image</label>
    <input type="file" class="form-control" id="image" name="image">
</div>
<div class="mb-3">
    <label for="user_id" class="form-label">User ID</label>
    <select class="form-control" id="user_id" name="user_id">
        @foreach ($users as $user)
            <option value="{{ $user->id }}" {{ old('user_id', $poll->user_id) == $user->id ? 'selected' : '' }}>
                {{ $user->name }}</option>
        @endforeach
    </select>
</div>
