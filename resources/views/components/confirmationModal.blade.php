@props(['folders'])

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmationModal" class="fixed inset-0 z-50 hidden px-8">
    <div class="flex items-center justify-center h-full">
        <div class="fixed inset-0 bg-black opacity-30 modal-overlay"></div>
        <div class="bg-white rounded-md shadow-lg z-50 w-96 border border-sgline overflow-hidden">
            <div class="py-4 px-6 border-b border-sgline">
                <h3 class="text-lg font-medium text-mainText">Confirm Deletion</h3>
            </div>
            <div class="py-6 px-6">
                <p class="text-mainText opacity-80 text-sm">Are you sure you want to delete this room? This
                    action cannot be undone.</p>
            </div>
            <div class="py-4 px-6 flex justify-end gap-3">
                <button type="button" id="cancelDeleteBtn"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 sm:text-base text-sm text-mainText rounded-md">Cancel</button>
                <button type="button" id="confirmDeleteBtn"
                    class="px-4 py-2 bg-red-500 hover:bg-red-600 sm:text-base text-sm text-white rounded-md">Delete</button>
            </div>
        </div>
    </div>
</div>
<!-- Move Confirmation Modal -->
<div id="moveConfirmationModal" class="fixed inset-0 z-50 hidden px-8">
    <div class="flex items-center justify-center h-full">
        <div class="fixed inset-0 bg-black opacity-30 modal-overlay"></div>
        <div class="bg-white rounded-md shadow-lg z-50 w-96 border border-sgline overflow-hidden">
            <div class="py-4 px-6 border-b border-sgline">
                <h3 class="text-lg font-medium text-mainText">Move to Folder</h3>
            </div>
            <div class="py-6 px-6">
                <p class="text-mainText opacity-80 text-sm mb-4">Select a destination folder:</p>
                <form id="moveForm" method="post">
                    @csrf
                    <input type="hidden" id="moveItemId" name="item_id" value="">
                    <input type="hidden" id="moveItemType" name="item_type" value="">
                    <select id="folderSelect" name="folder_id"
                        class="w-full appearance-none border border-sgline rounded-md pl-3 pr-10 py-2 focus:outline-none focus:border-mainText cursor-pointer sm:text-base text-sm">
                        <option value="" disabled selected>Select a folder</option>
                        <option value="homepage">Homepage (No folder)</option>
                        @foreach ($folders as $folder)
                            <option value="{{ $folder->id }}">{{ $folder->folder_name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="py-4 px-6 flex justify-end gap-3">
                <button type="button" id="cancelMoveBtn"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-mainText rounded-md sm:text-base text-sm">Cancel</button>
                <button type="button" id="confirmMoveBtn"
                    class="px-4 py-2 bg-mainText hover:bg-opacity-80 text-white rounded-md sm:text-base text-sm">Move</button>
            </div>
        </div>
    </div>
</div>
