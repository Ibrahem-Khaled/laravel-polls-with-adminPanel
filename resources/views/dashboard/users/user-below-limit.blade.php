@extends('layouts.dashboard')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>المستخدمون</h3>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الهاتف</th>
                        <th>العنوان</th>
                        <th>المبلغ</th>
                        <th>الصورة</th>
                        <th>الهوية</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->address }}</td>
                            <td>{{ $user->balance }}</td>
                            <td><img src="{{ Storage::url($user->image) }}" alt="صورة المستخدم" style="width: 50px;"></td>
                            <td><img src="{{ Storage::url($user->identity) }}" alt="هوية المستخدم" style="width: 50px;">
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editUserModal{{ $user->id }}">
                                    تعديل
                                </button>
                            </td>
                        </tr>
                        <!-- تعديل مستخدم Modal -->
                        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1"
                            aria-labelledby="editUserModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editUserModalLabel">تعديل مستخدم</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="إغلاق"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('users.update', $user->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            @include('dashboard.users.form', ['user' => $user])
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">إغلاق</button>
                                                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
