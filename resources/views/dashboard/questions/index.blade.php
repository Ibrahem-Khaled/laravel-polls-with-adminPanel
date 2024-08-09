@extends('layouts.dashboard')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>الأسئلة لاستطلاع الرأي: {{ $poll->title }}</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                إضافة سؤال
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
                        <th>السؤال</th>
                        <th>الوصف</th>
                        <th>الحالة</th>
                        <th>الخيارات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questions as $question)
                        <tr>
                            <td>{{ $question->question }}</td>
                            <td>{{ $question->description }}</td>
                            <td>{{ $question->status }}</td>
                            <td>
                                <ul>
                                    @foreach ($question->options as $option)
                                        <li>{{ $option->option }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <a href="{{ route('questions.options.index', $question->id) }}" class="btn btn-info">
                                    إدارة الخيارات
                                </a>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editQuestionModal{{ $question->id }}">
                                    تعديل
                                </button>
                                <form action="{{ route('polls.questions.destroy', [$poll->id, $question->id]) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">حذف</button>
                                </form>
                            </td>
                        </tr>

                        <!-- تعديل سؤال Modal -->
                        <div class="modal fade" id="editQuestionModal{{ $question->id }}" tabindex="-1"
                            aria-labelledby="editQuestionModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editQuestionModalLabel">تعديل السؤال</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="إغلاق"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('polls.questions.update', [$poll->id, $question->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            @include('dashboard.questions.form', ['question' => $question])
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
                {{ $questions->links() }}
            </div>
        </div>
    </div>

    <!-- إضافة سؤال Modal -->
    <div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addQuestionModalLabel">إضافة سؤال</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('polls.questions.store', $poll->id) }}" method="POST">
                        @csrf
                        @include('dashboard.questions.form', ['question' => new App\Models\Question()])
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
