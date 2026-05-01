<?php

namespace App\Packages\HostelTransportManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HostelTransportManagementSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateTimeString();

        // Hostels
        DB::table('hostels')->insert([
            [
                'hostel_name'        => 'Sunrise Boys Hostel',
                'hostel_type'        => 'Boys',
                'total_capacity'     => 100,
                'available_capacity' => 80,
                'location'           => 'North Wing, School Campus',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                'hostel_name'        => 'Moonlight Girls Hostel',
                'hostel_type'        => 'Girls',
                'total_capacity'     => 80,
                'available_capacity' => 60,
                'location'           => 'South Wing, School Campus',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
        ]);

        // Hostel Rooms
        DB::table('hostel_rooms')->insert([
            ['hostel_id' => 1, 'room_number' => 'B-101', 'room_type' => 'Double', 'capacity' => 2, 'occupied' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['hostel_id' => 1, 'room_number' => 'B-102', 'room_type' => 'Triple', 'capacity' => 3, 'occupied' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['hostel_id' => 1, 'room_number' => 'B-103', 'room_type' => 'Quad',   'capacity' => 4, 'occupied' => 0, 'created_at' => $now, 'updated_at' => $now],
            ['hostel_id' => 2, 'room_number' => 'G-101', 'room_type' => 'Double', 'capacity' => 2, 'occupied' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['hostel_id' => 2, 'room_number' => 'G-102', 'room_type' => 'Triple', 'capacity' => 3, 'occupied' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['hostel_id' => 2, 'room_number' => 'G-103', 'room_type' => 'Single', 'capacity' => 1, 'occupied' => 0, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Students Hostel Assignments
        // Boys: students 4,8 in room B-101 (hostel_room_id=1); student 6 in B-102 (id=2)
        // Girls: students 5,7 in G-101 (id=4); student 9 in G-102 (id=5)
        DB::table('students_hostel')->insert([
            ['student_id' => 4, 'hostel_id' => 1, 'room_id' => 1, 'assigned_date' => '2024-04-01', 'checkout_date' => null, 'created_at' => $now, 'updated_at' => $now],
            ['student_id' => 8, 'hostel_id' => 1, 'room_id' => 1, 'assigned_date' => '2024-04-01', 'checkout_date' => null, 'created_at' => $now, 'updated_at' => $now],
            ['student_id' => 6, 'hostel_id' => 1, 'room_id' => 2, 'assigned_date' => '2024-04-01', 'checkout_date' => null, 'created_at' => $now, 'updated_at' => $now],
            ['student_id' => 5, 'hostel_id' => 2, 'room_id' => 4, 'assigned_date' => '2024-04-01', 'checkout_date' => null, 'created_at' => $now, 'updated_at' => $now],
            ['student_id' => 7, 'hostel_id' => 2, 'room_id' => 4, 'assigned_date' => '2024-04-01', 'checkout_date' => null, 'created_at' => $now, 'updated_at' => $now],
            ['student_id' => 9, 'hostel_id' => 2, 'room_id' => 5, 'assigned_date' => '2024-04-01', 'checkout_date' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Transportation
        DB::table('transportation')->insert([
            [
                'transport_name' => 'School Bus Route 1',
                'transport_type' => 'Bus',
                'capacity'       => 40,
                'route'          => 'Main Market → Station Road → Sector 5 → School',
                'departure_time' => '07:00:00',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'transport_name' => 'School Bus Route 2',
                'transport_type' => 'Bus',
                'capacity'       => 40,
                'route'          => 'Green Park → Civil Lines → Model Town → School',
                'departure_time' => '07:15:00',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'transport_name' => 'School Van — Area 3',
                'transport_type' => 'Van',
                'capacity'       => 12,
                'route'          => 'Shastri Nagar → Railway Colony → School',
                'departure_time' => '07:30:00',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ]);

        // Students Transport Assignments
        $transportAssignments = [
            [1,  1, 'Main Market Stop',  'School Gate', '2024-04-01'],
            [2,  1, 'Station Road Stop', 'School Gate', '2024-04-01'],
            [3,  2, 'Green Park Stop',   'School Gate', '2024-04-01'],
            [10, 2, 'Civil Lines Stop',  'School Gate', '2024-04-01'],
            [11, 3, 'Shastri Nagar',     'School Gate', '2024-04-01'],
            [12, 3, 'Railway Colony',    'School Gate', '2024-04-01'],
        ];

        foreach ($transportAssignments as [$studentId, $transportId, $pickup, $drop, $assignedDate]) {
            DB::table('students_transport')->insert([
                'student_id'      => $studentId,
                'transport_id'    => $transportId,
                'pickup_location' => $pickup,
                'drop_location'   => $drop,
                'assigned_date'   => $assignedDate,
                'leave_date'      => null,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }

        // Facility Management
        DB::table('facility_management')->insert([
            [
                'facility_name'      => 'School Library',
                'facility_type'      => 'Library',
                'location'           => 'Block A, Ground Floor',
                'capacity'           => 50,
                'available_capacity' => 50,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                'facility_name'      => 'Cricket Ground',
                'facility_type'      => 'Sports',
                'location'           => 'East Playground',
                'capacity'           => 100,
                'available_capacity' => 100,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                'facility_name'      => 'School Cafeteria',
                'facility_type'      => 'Cafeteria',
                'location'           => 'Block B, Ground Floor',
                'capacity'           => 80,
                'available_capacity' => 80,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                'facility_name'      => 'Physics Lab',
                'facility_type'      => 'Lab',
                'location'           => 'Block C, First Floor',
                'capacity'           => 30,
                'available_capacity' => 30,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
        ]);

        // Facility Bookings
        $bookings = [
            [1, 1, '2024-04-10', '09:00:00', '11:00:00'],
            [2, 1, '2024-04-10', '14:00:00', '15:00:00'],
            [3, 2, '2024-04-12', '07:00:00', '09:00:00'],
            [4, 3, '2024-04-15', '10:00:00', '11:00:00'],
            [5, 1, '2024-04-17', '09:00:00', '10:00:00'],
        ];

        foreach ($bookings as [$studentId, $facilityId, $date, $start, $end]) {
            DB::table('facility_bookings')->insert([
                'student_id'   => $studentId,
                'facility_id'  => $facilityId,
                'booking_date' => $date,
                'start_time'   => $start,
                'end_time'     => $end,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }
    }
}
