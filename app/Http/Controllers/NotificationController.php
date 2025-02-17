<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function markAsRead($id)
    {
        $decodeId = HelperFunctions::base64_url_decode($id);

        $notification = Notification::where('room_id', $decodeId)->get();
        foreach($notification as $notif) 
        {
            $notif->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function deleteNotifs($id)
    {
        $decodeId = HelperFunctions::base64_url_decode($id);

        $notification = Notification::where('room_id', $decodeId)->get();
        foreach($notification as $notif) 
        {
            $notif->delete();
        }

        return response()->json(['success' => true]);

    }

}
