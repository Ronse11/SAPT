<?php
namespace App\DataTransferObjects;

class TeacherHomeData
{
    public $rooms;
    public $roomsWithUrls;
    public $folders;
    public $folder;
    public $folderRooms;
    public $allRooms;
    public $classCount;
    public $folderCount;

    public function __construct($rooms, $roomsWithUrls, $folders, $folder, $folderRooms, $allRooms, $classCount, $folderCount)
    {
        $this->rooms = $rooms;
        $this->roomsWithUrls = $roomsWithUrls;
        $this->folders = $folders;
        $this->folder = $folder;
        $this->folderRooms = $folderRooms;
        $this->allRooms = $allRooms;
        $this->classCount = $classCount;
        $this->folderCount = $folderCount;
    }
}