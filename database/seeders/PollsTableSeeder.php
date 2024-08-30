<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Poll;
use App\Models\Question;
use App\Models\Option;

class PollsTableSeeder extends Seeder
{
    public function run()
    {
        // بيانات الاستطلاع والأسئلة والخيارات
        $polls = [
            [
                'title' => 'استخدام الحافلات العامة',
                'questions' => [
                    [
                        'question' => 'كم مرة تستخدم الحافلات العامة؟',
                        'options' => ['يوميًا', 'أسبوعيًا', 'شهريًا', 'نادرًا']
                    ],
                    [
                        'question' => 'هل تعتبر الحافلات العامة مريحة؟',
                        'options' => ['نعم، جدًا', 'إلى حد ما', 'لا، غير مريحة', 'لم أجرب']
                    ],
                    // أضف باقي الأسئلة هنا
                ]
            ],
            // أضف باقي الاستطلاعات هنا
        ];

        foreach ($polls as $pollData) {
            $poll = Poll::create([
                'user_id' => 2,
                'title' => $pollData['title'],
                'description' => null,
                'image' => null,
                'price' => 0.10,
                'status' => 'active',
                'visibility' => 'public'
            ]);

            foreach ($pollData['questions'] as $questionData) {
                $question = Question::create([
                    'poll_id' => $poll->id,
                    'question' => $questionData['question'],
                    'description' => null,
                    'status' => 'active'
                ]);

                foreach ($questionData['options'] as $optionText) {
                    Option::create([
                        'question_id' => $question->id,
                        'option' => $optionText
                    ]);
                }
            }
        }
    }
}
