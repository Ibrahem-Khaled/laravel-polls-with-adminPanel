<!-- resources/views/users/partials/form.blade.php -->

<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
</div>
<div class="mb-3">
    <label for="phone" class="form-label">Phone</label>
    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
</div>
<div class="mb-3">
    <label for="balance" class="form-label">Balance</label>
    <input type="number" step="0.01" class="form-control" id="balance" name="balance"
        value="{{ old('balance', $user->balance) }}">
</div>
<div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" id="password" name="password" {{ $user->exists ? '' : 'required' }}>
</div>
<div class="mb-3">
    <label for="image" class="form-label">Image</label>
    <input type="file" class="form-control" id="image" name="image">
</div>
<div class="mb-3">
    <label for="identity" class="form-label">Identity</label>
    <input type="file" class="form-control" id="identity" name="identity">
</div>
<div class="mb-3">
    <label for="address" class="form-label">Address</label>
    <textarea class="form-control" id="address" name="address">{{ old('address', $user->address) }}</textarea>
</div>
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control" id="description" name="description">{{ old('description', $user->description) }}</textarea>
</div>
<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select class="form-control" id="status" name="status">
        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
</div>
<div class="mb-3">
    <label for="is_verified" class="form-label">Verification Status</label>
    <select class="form-control" id="is_verified" name="is_verified">
        <option value="verified" {{ old('is_verified', $user->is_verified) == 'verified' ? 'selected' : '' }}>Verified
        </option>
        <option value="unverified" {{ old('is_verified', $user->is_verified) == 'unverified' ? 'selected' : '' }}>
            Unverified</option>
    </select>
</div>
<div class="mb-3">
    <label for="role" class="form-label">Role</label>
    <select class="form-control" id="role" name="role">
        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="company" {{ old('role', $user->role) == 'company' ? 'selected' : '' }}>Company</option>
    </select>
</div>
