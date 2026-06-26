@extends('layouts.app')

@section('title', 'Users')
@section('header', 'User Management')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h3 class="text-lg font-semibold">Users List</h3>
        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Add New User
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4">1</td>
                    <td class="px-6 py-4">John Doe</td>
                    <td class="px-6 py-4">john@example.com</td>
                    <td class="px-6 py-4">Admin</td>
                    <td class="px-6 py-4">
                        <button class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                        <button class="text-red-600 hover:text-red-900">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection