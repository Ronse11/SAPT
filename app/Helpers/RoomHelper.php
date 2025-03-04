<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelperFunctions;


class RoomHelper
{
    public static function generateRoomUrl($room)
    {
        $encodedId = HelperFunctions::base64_url_encode($room->id);
        return route('teacher.room', ['id' => $encodedId]);
    }

    public static function generateRoomStudentUrl($room)
    {
        $encodedId = HelperFunctions::base64_url_encode($room->room_id);
        // return route('student.room', ['id' => $encodedId]);
        return route('studentRecord.room', [ 'id' => $encodedId, 'key' => 'main-table']);
    }
}