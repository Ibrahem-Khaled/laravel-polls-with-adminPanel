<!-- resources/views/options/index.blade.php -->

@extends('layouts.dashboard')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>الخيارات للسؤال: {{ $question->question }}</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOptionModal">
                إضافة خيار
            </button>
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
                        <th>الخيار</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($options as $option)
                        <tr>
                            <td>{{ $option->option }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editOptionModal{{ $option->id }}">
                                    تعديل
                                </button>
                                <form action="{{ route('questions.options.destroy', [$question->id, $option->id]) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">حذف</button>
                                </form>
                            </td>
                        </tr>

                        <!-- تعديل خيار Modal -->
                        <div class="modal fade" id="editOptionModal{{ $option->id }}" tabindex="-1"
                            aria-labelledby="editOptionModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editOptionModalLabel">تعديل الخيار</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="إغلاق"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('questions.options.update', [$question->id, $option->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            @include('dashboard.options.form', ['option' => $option])
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
                {{ $options->links() }}
            </div>
        </div>
    </div>

    <!-- إضافة خيار Modal -->
    <div class="modal fade" id="addOptionModal" tabindex="-1" aria-labelledby="addOptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addOptionModalLabel">إضافة خيار</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('questions.options.store', $question->id) }}" method="POST">
                        @csrf
                        @include('dashboard.options.form', ['option' => new App\Models\Option()])
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
