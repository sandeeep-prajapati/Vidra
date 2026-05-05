<?php

return [
    // Fine calculation
    'fine_per_day' => 10.0, // Amount in rupees per day of overdue
    'max_issue_days' => 14, // Default number of days allowed for book issuance
    'max_books_per_member' => 3, // Default max books a member can borrow

    // Fine limits
    'max_fine_per_book' => 500.0, // Maximum fine per book
    'fine_calculation_type' => 'daily', // daily, flat, graduated

    // Reservation settings
    'reservation_expiry_days' => 7, // Days before reservation expires
    'reservation_notification_before_days' => 1, // Notify member this many days before expiry

    // Membership
    'membership_validity_years' => 1, // Default membership validity in years

    // Reminder
    'due_date_reminder_days' => 2, // Send reminder this many days before due date
];
