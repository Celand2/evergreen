@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-500 rounded-full text-white text-2xl">
                👥
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Total Users</p>
                <p class="text-2xl font-semibold">{{ number_format($stats['total_users']) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-500 rounded-full text-white text-2xl">
                ⭐
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Active VIPs</p>
                <p class="text-2xl font-semibold">{{ number_format($stats['active_vips']) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-500 rounded-full text-white text-2xl">
                💰
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Pending Deposits</p>
                <p class="text-2xl font-semibold">{{ number_format($stats['pending_deposits']) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-red-500 rounded-full text-white text-2xl">
                💸
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Pending Withdrawals</p>
                <p class="text-2xl font-semibold">{{ number_format($stats['pending_withdrawals']) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-purple-500 rounded-full text-white text-2xl">
                💼
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Total Retirable Balance</p>
                <p class="text-2xl font-semibold">${{ number_format($stats['total_balance_retirable'], 2) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-indigo-500 rounded-full text-white text-2xl">
                📈
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Total Invested Balance</p>
                <p class="text-2xl font-semibold">${{ number_format($stats['total_balance_investissable'], 2) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection