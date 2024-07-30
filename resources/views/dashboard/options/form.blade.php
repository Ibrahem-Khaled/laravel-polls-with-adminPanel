<!-- resources/views/options/partials/form.blade.php -->

<div class="mb-3">
    <label for="option" class="form-label">Option</label>
    <input type="text" class="form-control" id="option" name="option" value="{{ old('option', $option->option) }}"
        required>
</div>
