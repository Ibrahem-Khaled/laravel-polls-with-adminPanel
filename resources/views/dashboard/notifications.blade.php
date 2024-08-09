@extends('layouts.dashboard')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>الإشعارات</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNotificationModal">إنشاء إشعار جديد
            </button>
        </div>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>العنوان</th>
                    <th>المحتوى</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
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
                                <span>لا توجد صورة</span>
                            @endif
                        </td>
                        <td>{{ $notification->title }}</td>
                        <td>{{ $notification->body }}</td>
                        <td>{{ ucfirst($notification->status) }}</td>
                        <td>
                            <button class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editNotificationModal{{ $notification->id }}">تعديل</button>
                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>

                    <!-- تعديل إشعار Modal -->
                    <div class="modal fade" id="editNotificationModal{{ $notification->id }}" tabindex="-1"
                        aria-labelledby="editNotificationModalLabel{{ $notification->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editNotificationModalLabel{{ $notification->id }}">تعديل
                                        الإشعار</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="إغلاق"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('notifications.update', $notification->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="image{{ $notification->id }}" class="form-label">الصورة</label>
                                            <input type="file" class="form-control" id="image{{ $notification->id }}"
                                                name="image">
                                            @if ($notification->image)
                                                <img src="{{ Storage::url($notification->image) }}"
                                                    alt="{{ $notification->title }}" width="100" class="mt-2">
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label for="title{{ $notification->id }}" class="form-label">العنوان</label>
                                            <input type="text" class="form-control" id="title{{ $notification->id }}"
                                                name="title" value="{{ $notification->title }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="body{{ $notification->id }}" class="form-label">المحتوى</label>
                                            <textarea class="form-control" id="body{{ $notification->id }}" name="body">{{ $notification->body }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="status{{ $notification->id }}" class="form-label">الحالة</label>
                                            <select class="form-control" id="status{{ $notification->id }}" name="status"
                                                required>
                                                <option value="read"
                                                    {{ $notification->status == 'read' ? 'selected' : '' }}>مقروء</option>
                                                <option value="unread"
                                                    {{ $notification->status == 'unread' ? 'selected' : '' }}>غير مقروء
                                                </option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- إضافة إشعار Modal -->
    <div class="modal fade" id="addNotificationModal" tabindex="-1" aria-labelledby="addNotificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNotificationModalLabel">إنشاء إشعار جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('notifications.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="image" class="form-label">الصورة</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">العنوان</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="body" class="form-label">المحتوى</label>
                            <textarea class="form-control" id="body" name="body"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">الحالة</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="read">مقروء</option>
                                <option value="unread">غير مقروء</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">إنشاء</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
