@extends('layouts.dashboard')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">رسائل اتصل بنا</h2>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <form action="{{ route('contact-us') }}" method="GET" class="form-inline">
                    <label for="status" class="mr-2">تصفية حسب الحالة:</label>
                    <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="accepted" {{ $status == 'accepted' ? 'selected' : '' }}>مقبول</option>
                        <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>اسم المستخدم</th>
                        <th>رقم الهاتف</th>
                        <th>الموضوع</th>
                        <th>الرسالة</th>
                        <th>الحالة</th>
                        <th>الصورة</th>
                        <th>تاريخ الإنشاء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr>
                            <td>{{ $contact->id }}</td>
                            <td>{{ $contact->user->name }}</td>
                            <td>{{ $contact->user->phone }}</td>
                            <td>{{ $contact->subject }}</td>
                            <td>{{ $contact->message }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $contact->status == 'accepted' ? 'success' : ($contact->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($contact->status) == 'Pending' ? 'معلق' : (ucfirst($contact->status) == 'Accepted' ? 'مقبول' : 'مرفوض') }}
                                </span>
                            </td>
                            <td>
                                @if ($contact->image)
                                    <img src="{{ asset( $contact->image) }}" alt="صورة"
                                        class="img-thumbnail" style="width: 100px;">
                                @else
                                    لا توجد صورة
                                @endif
                            </td>
                            <td>{{ $contact?->created_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">لم يتم العثور على رسائل لهذه الحالة.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
