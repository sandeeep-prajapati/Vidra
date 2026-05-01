<?php

namespace App\Packages\CommunicationManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunicationManagementSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateTimeString();

        // Messages
        $messages = [
            [
                'title'        => 'Welcome to Academic Year 2024-25',
                'content'      => 'Dear Parents and Students, We warmly welcome you to the new academic year 2024-25. Please review the school calendar attached.',
                'message_type' => 'Announcement',
                'created_by'   => 1,
                'scheduled_at' => null,
                'priority'     => 'High',
                'is_sent'      => true,
            ],
            [
                'title'        => 'Mid-Term Exam Schedule',
                'content'      => 'The Mid-Term examination schedule for 2024-25 has been released. Kindly check the school portal for class-wise timetables.',
                'message_type' => 'App Notification',
                'created_by'   => 1,
                'scheduled_at' => null,
                'priority'     => 'Normal',
                'is_sent'      => true,
            ],
            [
                'title'        => 'Fee Payment Reminder',
                'content'      => 'This is a gentle reminder that the tuition fee for the month of May is due on 10th May 2024. Kindly clear dues to avoid late penalty.',
                'message_type' => 'SMS',
                'created_by'   => 1,
                'scheduled_at' => '2024-05-05 09:00:00',
                'priority'     => 'Urgent',
                'is_sent'      => true,
            ],
            [
                'title'        => 'Parent-Teacher Meeting',
                'content'      => 'PTM is scheduled for 15th June 2024 from 10 AM to 1 PM. Your presence is requested.',
                'message_type' => 'Email',
                'created_by'   => 1,
                'scheduled_at' => null,
                'priority'     => 'High',
                'is_sent'      => false,
            ],
            [
                'title'        => 'School Holiday Notice',
                'content'      => 'School will remain closed on 15th August 2024 on account of Independence Day.',
                'message_type' => 'Announcement',
                'created_by'   => 1,
                'scheduled_at' => null,
                'priority'     => 'Normal',
                'is_sent'      => true,
            ],
        ];

        foreach ($messages as $message) {
            DB::table('messages')->insert(array_merge($message, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // Message Recipients (user_id=1 for all sent messages)
        $sentMessageIds = [1, 2, 3, 5];
        foreach ($sentMessageIds as $messageId) {
            DB::table('message_recipients')->insert([
                'message_id'   => $messageId,
                'user_id'      => 1,
                'status'       => 'Read',
                'delivered_at' => $now,
                'read_at'      => $now,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        // Circulars
        $circulars = [
            [
                'title'           => 'Annual Prize Distribution Ceremony',
                'content'         => 'The Annual Prize Distribution Ceremony will be held on 20th December 2024 in the school auditorium. All students and parents are cordially invited.',
                'issued_by'       => 1,
                'issued_date'     => '2024-12-01',
                'target_audience' => 'All',
                'attachment_url'  => 'dummy/dummy-image.jpg',
            ],
            [
                'title'           => 'New Uniform Policy',
                'content'         => 'Starting from the next academic year, all students are required to wear the new school uniform as per the guidelines issued.',
                'issued_by'       => 1,
                'issued_date'     => '2024-10-15',
                'target_audience' => 'Students',
                'attachment_url'  => 'dummy/dummy-image.jpg',
            ],
            [
                'title'           => 'Teacher Training Workshop',
                'content'         => 'A mandatory teacher training workshop on modern pedagogical methods will be held on 5th November 2024.',
                'issued_by'       => 1,
                'issued_date'     => '2024-10-20',
                'target_audience' => 'Teachers',
                'attachment_url'  => null,
            ],
        ];

        foreach ($circulars as $circular) {
            DB::table('circulars')->insert(array_merge($circular, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // Notification Settings (for user_id=1)
        DB::table('notification_settings')->insertOrIgnore([
            'user_id'             => 1,
            'allow_sms'           => true,
            'allow_email'         => true,
            'allow_app'           => true,
            'allow_announcements' => true,
            'allow_circulars'     => true,
            'created_at'          => $now,
            'updated_at'          => $now,
        ]);
    }
}
