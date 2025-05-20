@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Уведомления</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('notifications.readAll') }}" style="display:inline;">
        @csrf
        <button class="btn btn-sm btn-success mb-3" @if($notifications->where('read_at', null)->count() == 0) disabled @endif>
            Отметить все как прочитанные
        </button>
    </form>
    <ul class="list-group mb-4">
        @forelse($notifications as $notification)
            <li class="list-group-item d-flex justify-content-between align-items-center @if($notification->unread()) bg-light @endif">
                <div>
                    <a href="{{ $notification->data['link'] ?? '#' }}" @if($notification->unread()) style="font-weight:bold" @endif>
                        {!! preg_replace_callback('/\b(delivered|shipped|confirmed|pending|approved|rejected|completed|archived|unarchived)\b/', function($m) {
                            $map = [
                                'delivered' => __('messages.status_delivered'),
                                'shipped' => __('messages.status_shipped'),
                                'confirmed' => __('messages.status_confirmed'),
                                'pending' => __('messages.status_pending'),
                                'approved' => __('messages.status_approved'),
                                'rejected' => __('messages.status_rejected'),
                                'completed' => __('messages.status_completed'),
                                'archived' => __('messages.status_archived'),
                                'unarchived' => __('messages.status_unarchived'),
                            ];
                            return $map[$m[0]] ?? $m[0];
                        }, e($notification->data['message'])) !!}
                    </a>
                    <br>
                    <small class="text-muted">{{ $notification->created_at->format('d.m.Y H:i') }}</small>
                </div>
                @if($notification->unread())
                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-primary">Прочитано</button>
                </form>
                @endif
            </li>
        @empty
            <li class="list-group-item">Нет уведомлений</li>
        @endforelse
    </ul>
    <div class="d-flex justify-content-center">
        @if ($notifications->hasPages())
            <nav>
                <ul class="pagination" style="display: flex; gap: 0.3rem; list-style: none; padding: 0; align-items: center;">
                    {{-- Previous Page Link --}}
                    @if ($notifications->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="Назад">
                            <span class="page-link" aria-hidden="true" style="background: none; color: #a1a1aa; border: none; font-size: 1.5em; padding: 0 0.7em;">
                                <i class="fa-solid fa-chevron-left"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $notifications->previousPageUrl() }}" rel="prev" aria-label="Назад" style="background: none; color: #2563eb; border: none; font-size: 1.5em; padding: 0 0.7em; text-decoration: none;">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
                        @if ($page == $notifications->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link" style="background: none; color: #2563eb; font-weight: bold; border-bottom: 2px solid #2563eb; border-radius: 0; padding: 0 0.7em;">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}" style="background: none; color: #2563eb; border: none; padding: 0 0.7em; text-decoration: none;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($notifications->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $notifications->nextPageUrl() }}" rel="next" aria-label="Вперёд" style="background: none; color: #2563eb; border: none; font-size: 1.5em; padding: 0 0.7em; text-decoration: none;">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="Вперёд">
                            <span class="page-link" aria-hidden="true" style="background: none; color: #a1a1aa; border: none; font-size: 1.5em; padding: 0 0.7em;">
                                <i class="fa-solid fa-chevron-right"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif
    </div>
</div>
@endsection 