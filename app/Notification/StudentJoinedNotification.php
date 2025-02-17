<?php

namespace App\Notification;

use App\Models\Notification;
use App\Models\User;
use App\Models\Rooms;

class StudentJoinedNotification
{
    protected $user;
    protected $room;

    public function __construct(User $user, Rooms $room)
    {
        $this->user = $user;
        $this->room = $room;
    }

    public function send(User $teacher)
    {
        return Notification::create([
            'teacher_id' => $teacher->id,
            'user_id' => $this->user->id,
            'room_id' => $this->room->id,
            'message' => "{$this->user->school_name} has joined the class.",
            'is_read' => false,
        ]);
    }
}
