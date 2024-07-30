<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Poll;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;

class mainController extends Controller
{
    public function home()
    {
        // جلب عدد المستخدمين
        $userCount = User::count();

        // جلب عدد الاستطلاعات
        $pollCount = Poll::count();

        // جلب عدد الأسئلة
        $questionCount = Question::count();

        // جلب عدد الخيارات
        $optionCount = Option::count();

        // بيانات الرسم البياني (مثال)
        $chartLabels = ['January', 'February', 'March', 'April', 'May'];
        $chartData = [10, 15, 8, 12, 14];

        // الأنشطة الأخيرة (مثال)
        $recentActivities = [
            (object) [
                'description' => 'Added a new poll',
                'created_at' => now()->subMinutes(5),
            ],
            (object) [
                'description' => 'User signed up',
                'created_at' => now()->subMinutes(10),
            ],
        ];

        // المهام (مثال)
        $tasks = [
            (object) [
                'task' => 'Review new polls',
                'due_date' => now()->addDays(1),
            ],
            (object) [
                'task' => 'Update user permissions',
                'due_date' => now()->addDays(2),
            ],
        ];

        return view(
            'home',
            compact(
                'userCount',
                'pollCount',
                'questionCount',
                'optionCount',
                'chartLabels',
                'chartData',
                'recentActivities',
                'tasks'
            )
        );
    }
}
