@extends('layouts.admin')

@section('content')
<div class="bg-white shadow rounded-lg mb-6">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}" class="text-brown-600 hover:text-brown-900 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="text-xl sm:text-2xl font-semibold text-brown-800">User Management</h2>
            </div>
            <button onclick="showCreateModal()" 
                class="w-full sm:w-auto px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700 focus:outline-none focus:ring-2 focus:ring-brown-500 focus:ring-offset-2 transition-colors duration-200 ease-in-out inline-flex items-center justify-center space-x-2">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Add New User</span>
            </button>
        </div>

        <!-- Search and Filter -->
        <div class="mb-6 flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="searchInput" class="block text-sm font-medium text-brown-700 mb-1">Search Users</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-brown-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="Search by name or email..." 
                        class="pl-10 w-full rounded-md border-brown-300 shadow-sm focus:border-brown-500 focus:ring-brown-500 sm:text-sm transition-colors duration-200"
                        aria-label="Search users">
                </div>
            </div>
            <div class="sm:w-48">
                <label for="roleFilter" class="block text-sm font-medium text-brown-700 mb-1">Filter by Role</label>
                <select id="roleFilter" 
                    class="w-full rounded-md border-brown-300 shadow-sm focus:border-brown-500 focus:ring-brown-500 sm:text-sm transition-colors duration-200"
                    aria-label="Filter by role">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <!-- Users Table -->
        <div class="flex flex-col">
            <div class="-my-2 -mx-4 sm:-mx-6 lg:-mx-8 overflow-x-auto">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-brown-200">
                            <thead class="bg-brown-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-brown-900 sm:pl-6 cursor-pointer select-none" onclick="sortTable('name')">
                                        <div class="group inline-flex items-center">
                                            Name
                                            <span class="ml-2 flex-none rounded text-brown-400 group-hover:visible group-focus:visible">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                </svg>
                                            </span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-brown-900 cursor-pointer select-none" onclick="sortTable('email')">
                                        <div class="group inline-flex items-center">
                                            Email
                                            <span class="ml-2 flex-none rounded text-brown-400 group-hover:visible group-focus:visible">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                </svg>
                                            </span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-brown-900 cursor-pointer select-none" onclick="sortTable('role')">
                                        <div class="group inline-flex items-center">
                                            Role
                                            <span class="ml-2 flex-none rounded text-brown-400 group-hover:visible group-focus:visible">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                </svg>
                                            </span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-brown-900 cursor-pointer select-none" onclick="sortTable('created_at')">
                                        <div class="group inline-flex items-center">
                                            Joined
                                            <span class="ml-2 flex-none rounded text-brown-400 group-hover:visible group-focus:visible">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                </svg>
                                            </span>
                                        </div>
                                    </th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-brown-200 bg-white" id="usersTableBody">
                                @foreach($users as $user)
                                <tr id="user-row-{{ $user->id }}" data-role="{{ $user->role_id }}" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-brown-900 sm:pl-6">
                                        {{ $user->name }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-brown-900">
                                        {{ $user->email }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role->name === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ ucfirst($user->role->name) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-brown-700">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <div class="flex justify-end gap-3">
                                            <button onclick="editUser({{ $user->id }})" 
                                                class="text-brown-600 hover:text-brown-900 focus:outline-none focus:ring-2 focus:ring-brown-500 focus:ring-offset-2 rounded-md transition-colors duration-200"
                                                aria-label="Edit user">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            @if($user->id !== auth()->id())
                                                <button onclick="deleteUser({{ $user->id }})" 
                                                    class="text-red-600 hover:text-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded-md transition-colors duration-200"
                                                    aria-label="Delete user">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div id="createUserModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                    <button type="button" onclick="closeCreateModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-brown-500 focus:ring-offset-2">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-brown-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-brown-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-semibold leading-6 text-brown-900" id="modal-title">Create New User</h3>
                        <div class="mt-4">
                            <form id="createUserForm" class="space-y-4">
                                <div>
                                    <label for="createName" class="block text-sm font-medium text-brown-700">Name</label>
                                    <input type="text" name="name" id="createName" required 
                                        class="mt-1 block w-full rounded-md border-brown-300 shadow-sm focus:border-brown-500 focus:ring-brown-500 sm:text-sm transition-colors duration-200">
                                </div>
                                <div>
                                    <label for="createEmail" class="block text-sm font-medium text-brown-700">Email</label>
                                    <input type="email" name="email" id="createEmail" required 
                                        class="mt-1 block w-full rounded-md border-brown-300 shadow-sm focus:border-brown-500 focus:ring-brown-500 sm:text-sm transition-colors duration-200">
                                </div>
                                <div>
                                    <label for="createRole" class="block text-sm font-medium text-brown-700">Role</label>
                                    <select name="role_id" id="createRole" required 
                                        class="mt-1 block w-full rounded-md border-brown-300 shadow-sm focus:border-brown-500 focus:ring-brown-500 sm:text-sm transition-colors duration-200">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="createPassword" class="block text-sm font-medium text-brown-700">Password</label>
                                    <input type="password" name="password" id="createPassword" required 
                                        class="mt-1 block w-full rounded-md border-brown-300 shadow-sm focus:border-brown-500 focus:ring-brown-500 sm:text-sm transition-colors duration-200">
                                </div>
                                <div>
                                    <label for="createPasswordConfirmation" class="block text-sm font-medium text-brown-700">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="createPasswordConfirmation" required 
                                        class="mt-1 block w-full rounded-md border-brown-300 shadow-sm focus:border-brown-500 focus:ring-brown-500 sm:text-sm transition-colors duration-200">
                                </div>
                                <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                                    <button type="submit" 
                                        class="inline-flex w-full justify-center rounded-md bg-brown-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brown-700 focus:outline-none focus:ring-2 focus:ring-brown-500 focus:ring-offset-2 sm:col-start-2 transition-colors duration-200">
                                        Create User
                                    </button>
                                    <button type="button" onclick="closeCreateModal()" 
                                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0 focus:outline-none focus:ring-2 focus:ring-brown-500 focus:ring-offset-2 transition-colors duration-200">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" role="dialog" aria-modal="true" aria-labelledby="edit-modal-title">
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                    <button type="button" onclick="closeEditModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-brown-500 focus:ring-offset-2">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-brown-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-brown-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-semibold leading-6 text-brown-900" id="edit-modal-title">Edit User</h3>
                        <div class="mt-4">
                            <form id="editUserForm" class="space-y-4">
                                <input type="hidden" id="editUserId">
                                <div>
                                    <label for="editName" class="block text-sm font-medium text-brown-700">Name</label>
                                    <input type="text" name="name" id="editName" required 
                                        class="mt-1 block w-full rounded-md border-brown-300 shadow-sm focus:border-brown-500 focus:ring-brown-500 sm:text-sm transition-colors duration-200">
                                </div>
                                <div>
                                    <label for="editEmail" class="block text-sm font-medium text-brown-700">Email</label>
                                    <input type="email" name="email" id="editEmail" required 
                                        class="mt-1 block w-full rounded-md border-brown-300 shadow-sm focus:border-brown-500 focus:ring-brown-500 sm:text-sm transition-colors duration-200">
                                </div>
                                <div>
                                    <label for="editRole" class="block text-sm font-medium text-brown-700">Role</label>
                                    <select name="role_id" id="editRole" required 
                                        class="mt-1 block w-full rounded-md border-brown-300 shadow-sm focus:border-brown-500 focus:ring-brown-500 sm:text-sm transition-colors duration-200">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="editPassword" class="block text-sm font-medium text-brown-700">New Password (optional)</label>
                                    <input type="password" name="password" id="editPassword" 
                                        class="mt-1 block w-full rounded-md border-brown-300 shadow-sm focus:border-brown-500 focus:ring-brown-500 sm:text-sm transition-colors duration-200">
                                </div>
                                <div>
                                    <label for="editPasswordConfirmation" class="block text-sm font-medium text-brown-700">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="editPasswordConfirmation" 
                                        class="mt-1 block w-full rounded-md border-brown-300 shadow-sm focus:border-brown-500 focus:ring-brown-500 sm:text-sm transition-colors duration-200">
                                </div>
                                <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                                    <button type="submit" 
                                        class="inline-flex w-full justify-center rounded-md bg-brown-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brown-700 focus:outline-none focus:ring-2 focus:ring-brown-500 focus:ring-offset-2 sm:col-start-2 transition-colors duration-200">
                                        Save Changes
                                    </button>
                                    <button type="button" onclick="closeEditModal()" 
                                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0 focus:outline-none focus:ring-2 focus:ring-brown-500 focus:ring-offset-2 transition-colors duration-200">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function editUser(userId) {
    const user = @json($users->keyBy('id'));
    const userData = user[userId];
    
    $('#editUserId').val(userId);
    $('#editName').val(userData.name);
    $('#editEmail').val(userData.email);
    $('#editRole').val(userData.role_id);
    $('#editPassword').val('');
    $('#editPasswordConfirmation').val('');
    
    $('#editUserModal').removeClass('hidden').attr('aria-hidden', 'false');
    $('#editName').focus();
    
    // Trap focus in modal
    trapFocus('#editUserModal');
}

function closeEditModal() {
    $('#editUserModal').addClass('hidden').attr('aria-hidden', 'true');
    // Remove focus trap
    removeFocusTrap();
}

$('#editUserForm').on('submit', function(e) {
    e.preventDefault();
    const userId = $('#editUserId').val();
    const submitButton = $(this).find('button[type="submit"]');
    const originalText = submitButton.text();
    
    // Disable form submission
    submitButton.prop('disabled', true).html(`
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Saving...
    `);
    
    $.ajax({
        url: `/admin/users/${userId}`,
        method: 'PUT',
        data: {
            name: $('#editName').val(),
            email: $('#editEmail').val(),
            role_id: $('#editRole').val(),
            password: $('#editPassword').val(),
            password_confirmation: $('#editPasswordConfirmation').val()
        },
        success: function(response) {
            showMessage(response.message);
            closeEditModal();
            
            // Update the user row in the table
            const user = response.user;
            const roleClass = user.role.name === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
            
            $(`#user-row-${userId}`).html(`
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-brown-900 sm:pl-6">
                    ${user.name}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-brown-900">
                    ${user.email}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${roleClass}">
                        ${user.role.name.charAt(0).toUpperCase() + user.role.name.slice(1)}
                    </span>
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-brown-700">
                    ${new Date(user.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                </td>
                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                    <div class="flex justify-end gap-3">
                        <button onclick="editUser(${user.id})" 
                            class="text-brown-600 hover:text-brown-900 focus:outline-none focus:ring-2 focus:ring-brown-500 focus:ring-offset-2 rounded-md transition-colors duration-200"
                            aria-label="Edit user">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        ${user.id !== {{ auth()->id() }} ? `
                            <button onclick="deleteUser(${user.id})" 
                                class="text-red-600 hover:text-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded-md transition-colors duration-200"
                                aria-label="Delete user">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        ` : ''}
                    </div>
                </td>
            `);
        },
        error: function(xhr) {
            showMessage(xhr.responseJSON?.error || 'Error updating user', true);
        },
        complete: function() {
            // Re-enable form submission
            submitButton.prop('disabled', false).html(originalText);
        }
    });
});

function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        return;
    }
    
    const row = $(`#user-row-${userId}`);
    row.addClass('opacity-50 pointer-events-none');
    
    $.ajax({
        url: `/admin/users/${userId}`,
        method: 'DELETE',
        success: function(response) {
            showMessage(response.message);
            row.fadeOut(300, function() {
                $(this).remove();
            });
        },
        error: function(xhr) {
            showMessage(xhr.responseJSON?.error || 'Error deleting user', true);
            row.removeClass('opacity-50 pointer-events-none');
        }
    });
}

function showCreateModal() {
    $('#createUserModal').removeClass('hidden').attr('aria-hidden', 'false');
    $('#createName').focus();
    
    // Trap focus in modal
    trapFocus('#createUserModal');
}

function closeCreateModal() {
    $('#createUserModal').addClass('hidden').attr('aria-hidden', 'true');
    $('#createUserForm')[0].reset();
    // Remove focus trap
    removeFocusTrap();
}

$('#createUserForm').on('submit', function(e) {
    e.preventDefault();
    const submitButton = $(this).find('button[type="submit"]');
    const originalText = submitButton.text();
    
    // Disable form submission
    submitButton.prop('disabled', true).html(`
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Creating...
    `);
    
    $.ajax({
        url: '/admin/users',
        method: 'POST',
        data: {
            name: $('#createName').val(),
            email: $('#createEmail').val(),
            role_id: $('#createRole').val(),
            password: $('#createPassword').val(),
            password_confirmation: $('#createPasswordConfirmation').val()
        },
        success: function(response) {
            showMessage(response.message);
            closeCreateModal();
            window.location.reload(); // Reload to show new user
        },
        error: function(xhr) {
            showMessage(xhr.responseJSON?.error || 'Error creating user', true);
        },
        complete: function() {
            // Re-enable form submission
            submitButton.prop('disabled', false).html(originalText);
        }
    });
});

// Search and filter functionality with debounce
let searchTimeout;
$('#searchInput').on('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(filterTable, 300);
});

$('#roleFilter').on('change', filterTable);

function filterTable() {
    const searchTerm = $('#searchInput').val().toLowerCase();
    const roleFilter = $('#roleFilter').val();
    
    $('#usersTableBody tr').each(function() {
        const $row = $(this);
        const name = $row.data('name');
        const email = $row.data('email');
        const role = $row.data('role');
        
        const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
        const matchesRole = !roleFilter || role == roleFilter;
        
        $row.toggle(matchesSearch && matchesRole);
    });
}

// Sorting functionality with improved UI feedback
let currentSort = { column: '', direction: 'asc' };

function sortTable(column) {
    const $tbody = $('#usersTableBody');
    const $rows = $tbody.find('tr').toArray();
    const $currentHeader = $(`th[onclick="sortTable('${column}')"]`);
    
    // Update sort direction
    if (currentSort.column === column) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort = { column: column, direction: 'asc' };
    }
    
    // Update sort icons
    $('th svg').removeClass('text-brown-600').addClass('text-brown-400');
    const $icon = $currentHeader.find('svg');
    $icon.removeClass('text-brown-400').addClass('text-brown-600');
    
    // Sort rows
    $rows.sort((a, b) => {
        let aValue = '';
        let bValue = '';
        
        switch(column) {
            case 'name':
            case 'email':
                aValue = $(a).data(column);
                bValue = $(b).data(column);
                break;
            case 'role':
                aValue = $(a).find('td:eq(2)').text().trim();
                bValue = $(b).find('td:eq(2)').text().trim();
                break;
            case 'created_at':
                aValue = new Date($(a).find('td:eq(3)').text()).getTime();
                bValue = new Date($(b).find('td:eq(3)').text()).getTime();
                break;
        }
        
        if (currentSort.direction === 'asc') {
            return aValue > bValue ? 1 : -1;
        } else {
            return aValue < bValue ? 1 : -1;
        }
    });
    
    // Reorder table with animation
    $tbody.empty();
    $rows.forEach((row, index) => {
        $(row).css('opacity', 0)
            .appendTo($tbody)
            .delay(index * 50)
            .animate({ opacity: 1 }, 200);
    });
}

// Focus trap functionality
function trapFocus(modalSelector) {
    const modal = document.querySelector(modalSelector);
    const focusableElements = modal.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    const firstFocusable = focusableElements[0];
    const lastFocusable = focusableElements[focusableElements.length - 1];
    
    function handleTabKey(e) {
        if (e.key === 'Tab') {
            if (e.shiftKey) {
                if (document.activeElement === firstFocusable) {
                    e.preventDefault();
                    lastFocusable.focus();
                }
            } else {
                if (document.activeElement === lastFocusable) {
                    e.preventDefault();
                    firstFocusable.focus();
                }
            }
        }
        
        if (e.key === 'Escape') {
            if (modalSelector === '#createUserModal') {
                closeCreateModal();
            } else {
                closeEditModal();
            }
        }
    }
    
    modal.addEventListener('keydown', handleTabKey);
}

function removeFocusTrap() {
    document.removeEventListener('keydown', handleTabKey);
}

// Initialize tooltips for action buttons
$('[aria-label]').each(function() {
    const $el = $(this);
    const tooltip = $('<div>')
        .addClass('absolute hidden bg-gray-900 text-white text-xs rounded py-1 px-2 -top-8 left-1/2 transform -translate-x-1/2')
        .text($el.attr('aria-label'));
    
    $el.on('mouseenter focus', function() {
        tooltip.removeClass('hidden');
    }).on('mouseleave blur', function() {
        tooltip.addClass('hidden');
    });
    
    $el.append(tooltip);
});
</script>
@endpush 