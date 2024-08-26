@extends('layouts.dashboard')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">المدفوعات</h2>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <form action="{{ route('payment') }}" method="GET" class="form-inline">
                <label for="status" class="mr-2">تصفية حسب الحالة:</label>
                <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>غير مدفوع</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                </select>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>اسم المستخدم</th>
                        <th>رقم الهاتف</th>
                        <th>المبلغ</th>
                        <th>طريقة الدفع</th>
                        <th>الرقم</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ $payment->user->name }}</td>
                            <td>{{ $payment->user->phone }}</td>
                            <td>{{ $payment->amount }}</td>
                            <td>{{ $payment->method }}</td>
                            <td>{{ $payment->transaction }}</td>
                            <td>
                                <span class="badge badge-{{ $payment->status == 'paid' ? 'success' : 'danger' }}">
                                    {{ $payment->status == 'paid' ? 'مدفوع' : 'غير مدفوع' }}
                                </span>
                            </td>
                            <td>
                                @if ($payment->status == 'unpaid')
                                    <!-- Button to Open the Modal -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#confirmModal{{ $payment->id }}">
                                        وضع كمدفوع
                                    </button>
                                @endif
                            </td>
                        </tr>

                        <!-- The Modal -->
                        <div class="modal fade" id="confirmModal{{ $payment->id }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">تأكيد الدفع</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <!-- Modal Body -->
                                    <div class="modal-body">
                                        هل أنت متأكد أنك تريد وضع هذه الدفعة كمدفوعة؟
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="modal-footer">
                                        <form action="{{ route('payment.update', $payment->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success">نعم، تأكيد</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">لا توجد مدفوعات.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
