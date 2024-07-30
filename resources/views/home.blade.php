@extends('layouts.dashboard')

@section('content')
    <div class="container mt-5">
        <!-- شريط علوي -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>لوحة التحكم</h2>
        </div>

        <!-- بطاقات الإحصائيات -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">المستخدمون</h5>
                        <h3 class="card-text">{{ $userCount }}</h3>
                        <p class="card-text">إجمالي المستخدمين المسجلين</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">الاستطلاعات</h5>
                        <h3 class="card-text">{{ $pollCount }}</h3>
                        <p class="card-text">إجمالي الاستطلاعات المنشأة</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">الأسئلة</h5>
                        <h3 class="card-text">{{ $questionCount }}</h3>
                        <p class="card-text">إجمالي الأسئلة المنشأة</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">الخيارات</h5>
                        <h3 class="card-text">{{ $optionCount }}</h3>
                        <p class="card-text">إجمالي الخيارات المنشأة</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- الرسم البياني -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">تفاعل المستخدمين</h5>
                        <canvas id="userEngagementChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- الأنشطة الأخيرة والروابط السريعة -->
        <div class="row mb-4">
            <!-- الأنشطة الأخيرة -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">النشاطات الأخيرة</h5>
                        <ul class="list-group">
                            @foreach ($recentActivities as $activity)
                                <li class="list-group-item">
                                    {{ $activity->description }} -
                                    <small>{{ $activity->created_at->diffForHumans() }}</small>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- الروابط السريعة -->
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">روابط سريعة</h5>
                        <a href="{{ route('polls.index') }}" class="btn btn-primary btn-block mb-2">إنشاء استطلاع جديد</a>
                    </div>
                </div>

                <!-- قائمة المهام -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">قائمة المهام</h5>
                        <ul class="list-group">
                            @foreach ($tasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $task->task }}
                                    <span class="badge badge-primary badge-pill">{{ $task->due_date->diffForHumans() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الرسم البياني JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('userEngagementChart').getContext('2d');
        var userEngagementChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'تفاعل المستخدمين',
                    data: {!! json_encode($chartData) !!},
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
