@extends('layouts.dashboard')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Notifications</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNotificationModal">Create New
                Notification</button>
        </div>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($notifications as $notification)
                    <tr>
                        <td>
                            @if ($notification->image)
                                <img src="{{ Storage::url($notification->image) }}" alt="{{ $notification->title }}"
                                    width="100">
                            @else
                                <span>No Image</span>
                            @endif
                        </td>
                        <td>{{ $notification->title }}</td>
                        <td>{{ $notification->body }}</td>
                        <td>{{ ucfirst($notification->status) }}</td>
                        <td>
                            <button class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editNotificationModal{{ $notification->id }}">Edit</button>
                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Notification Modal -->
                    <div class="modal fade" id="editNotificationModal{{ $notification->id }}" tabindex="-1"
                        aria-labelledby="editNotificationModalLabel{{ $notification->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editNotificationModalLabel{{ $notification->id }}">Edit
                                        Notification</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('notifications.update', $notification->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="image{{ $notification->id }}" class="form-label">Image</label>
                                            <input type="file" class="form-control" id="image{{ $notification->id }}"
                                                name="image">
                                            @if ($notification->image)
                                                <img src="{{ Storage::url($notification->image) }}"
                                                    alt="{{ $notification->title }}" width="100" class="mt-2">
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label for="title{{ $notification->id }}" class="form-label">Title</label>
                                            <input type="text" class="form-control" id="title{{ $notification->id }}"
                                                name="title" value="{{ $notification->title }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="body{{ $notification->id }}" class="form-label">Body</label>
                                            <textarea class="form-control" id="body{{ $notification->id }}" name="body">{{ $notification->body }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="status{{ $notification->id }}" class="form-label">Status</label>
                                            <select class="form-control" id="status{{ $notification->id }}" name="status"
                                                required>
                                                <option value="read"
                                                    {{ $notification->status == 'read' ? 'selected' : '' }}>Read</option>
                                                <option value="unread"
                                                    {{ $notification->status == 'unread' ? 'selected' : '' }}>Unread
                                                </option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Notification Modal -->
    <div class="modal fade" id="addNotificationModal" tabindex="-1" aria-labelledby="addNotificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNotificationModalLabel">Create New Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('notifications.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="body" class="form-label">Body</label>
                            <textarea class="form-control" id="body" name="body"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="read">Read</option>
                                <option value="unread">Unread</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
