@extends('layouts.dashboard')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>الشرائح</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSlideModal">إنشاء شريحة جديدة</button>
        </div>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>العنوان</th>
                    <th>الوصف</th>
                    <th>الرابط</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($slides as $slide)
                    <tr>
                        <td><img src="{{ Storage::url($slide->image) }}" alt="{{ $slide->title }}" width="100"></td>
                        <td>{{ $slide->title }}</td>
                        <td>{{ $slide->description }}</td>
                        <td><a href="{{ $slide->link }}" target="_blank">{{ $slide->link }}</a></td>
                        <td>{{ ucfirst($slide->status) }}</td>
                        <td>
                            <button class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editSlideModal{{ $slide->id }}">تعديل</button>
                            <form action="{{ route('sliders.destroy', $slide->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>

                    <!-- تعديل شريحة Modal -->
                    <div class="modal fade" id="editSlideModal{{ $slide->id }}" tabindex="-1"
                        aria-labelledby="editSlideModalLabel{{ $slide->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editSlideModalLabel{{ $slide->id }}">تعديل الشريحة</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="إغلاق"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('sliders.update', $slide->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="image{{ $slide->id }}" class="form-label">الصورة</label>
                                            <input type="file" class="form-control" id="image{{ $slide->id }}"
                                                name="image">
                                            <img src="{{ Storage::url($slide->image) }}" alt="{{ $slide->title }}"
                                                width="100" class="mt-2">
                                        </div>
                                        <div class="mb-3">
                                            <label for="title{{ $slide->id }}" class="form-label">العنوان</label>
                                            <input type="text" class="form-control" id="title{{ $slide->id }}"
                                                name="title" value="{{ $slide->title }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="description{{ $slide->id }}" class="form-label">الوصف</label>
                                            <input type="text" class="form-control" id="description{{ $slide->id }}"
                                                name="description" value="{{ $slide->description }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="link{{ $slide->id }}" class="form-label">الرابط</label>
                                            <input type="url" class="form-control" id="link{{ $slide->id }}"
                                                name="link" value="{{ $slide->link }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="status{{ $slide->id }}" class="form-label">الحالة</label>
                                            <select class="form-control" id="status{{ $slide->id }}" name="status"
                                                required>
                                                <option value="active" {{ $slide->status == 'active' ? 'selected' : '' }}>
                                                    نشط</option>
                                                <option value="inactive"
                                                    {{ $slide->status == 'inactive' ? 'selected' : '' }}>غير نشط</option>
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

    <!-- إضافة شريحة Modal -->
    <div class="modal fade" id="addSlideModal" tabindex="-1" aria-labelledby="addSlideModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSlideModalLabel">إنشاء شريحة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sliders.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="image" class="form-label">الصورة</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">العنوان</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف</label>
                            <input type="text" class="form-control" id="description" name="description">
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">الرابط</label>
                            <input type="url" class="form-control" id="link" name="link">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">الحالة</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">إنشاء</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
