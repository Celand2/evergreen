@extends('layouts.client')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Notifications</h2>
    
    <div class="space-y-3">
        @foreach($notifications as $notification)
                <div class="border-l-4 {{ $notification->is_read ? 'border-gray-300 bg-gray-50' : 'border-blue-500 bg-blue-50' }} p-4 rounded">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold {{ $notification->is_read ? 'text-gray-600' : 'text-gray-900' }}">
                                {{ $notification->is_read ? '📧' : '🔔' }} {{ $notification->title }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $notification->body }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!$notification->is_read)
                            <form action="{{ route('client.notifications.markRead', $notification) }}" method="POST" class="ml-4">
                                @csrf
                                <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">Mark as read</button>
                            </form>
                        @endif
                    </div>
                </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection