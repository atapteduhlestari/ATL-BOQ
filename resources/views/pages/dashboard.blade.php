@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Card 1 -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-500 rounded-full text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">Total Users</p>
                <p class="text-2xl font-semibold">1,234</p>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-500 rounded-full text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">Total Products</p>
                <p class="text-2xl font-semibold">567</p>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-500 rounded-full text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">Categories</p>
                <p class="text-2xl font-semibold">24</p>
            </div>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-red-500 rounded-full text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">Reports</p>
                <p class="text-2xl font-semibold">12</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="mt-6 bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <h3 class="text-lg font-semibold">Recent Activities</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Activity</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                </table>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4">John Doe</td>
                    <td class="px-6 py-4">Logged in</td>
                    <td class="px-6 py-4">2024-01-15 10:30</td>
                </tr>
                <tr>
                    <td class="px-6 py-4">Jane Smith</td>
                    <td class="px-6 py-4">Updated product</td>
                    <td class="px-6 py-4">2024-01-15 09:45</td>
                </tr>
            </tbody>
        90>
    </div>
</div>
@endsection