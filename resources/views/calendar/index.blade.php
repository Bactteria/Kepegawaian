@extends('adminlte::page')

@section('title', 'Calendar')

@section('content_header')
    <h1>Calendar</h1>
@endsection

@section('content')

<style>
    .calendar-container {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .calendar-nav {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .calendar-nav a {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 8px 12px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
        text-decoration: none;
        color: #111827;
        display: inline-block;
    }
    
    .calendar-nav a:hover {
        background: #e5e7eb;
        text-decoration: none;
        color: #111827;
    }
    
    .calendar-month {
        font-size: 20px;
        font-weight: 600;
        color: #111827;
    }
    
    .add-event-btn {
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .add-event-btn:hover {
        background: #2563eb;
    }
    
    .view-toggle {
        display: flex;
        gap: 5px;
        background: #f3f4f6;
        border-radius: 6px;
        padding: 4px;
    }
    
    .view-toggle button {
        background: transparent;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
        color: #6b7280;
        transition: all 0.2s;
    }
    
    .view-toggle button.active {
        background: white;
        color: #111827;
        font-weight: 500;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background: #e5e7eb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .calendar-day-header {
        background: #f9fafb;
        padding: 12px;
        text-align: center;
        font-weight: 600;
        font-size: 12px;
        color: #6b7280;
        text-transform: uppercase;
    }
    
    .calendar-day {
        background: white;
        min-height: 100px;
        padding: 8px;
        position: relative;
    }
    
    .calendar-day.other-month {
        background: #f9fafb;
        color: #9ca3af;
    }
    
    .day-number {
        font-size: 14px;
        font-weight: 500;
        color: #111827;
        margin-bottom: 4px;
    }
    
    .calendar-day.other-month .day-number {
        color: #9ca3af;
    }
    
    .calendar-day.today .day-number {
        background: #3b82f6;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .event-item {
        font-size: 11px;
        padding: 4px 6px;
        margin-bottom: 2px;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .event-color-bar {
        width: 3px;
        height: 100%;
        border-radius: 2px;
        flex-shrink: 0;
    }
    
    .event-title {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Week View Styles */
    .calendar-week-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background: #e5e7eb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
    }

    .calendar-week-day {
        background: white;
        min-height: 400px;
        padding: 12px;
        position: relative;
    }

    .calendar-week-day.today {
        background: #eff6ff;
    }

    .week-day-header {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e5e7eb;
    }

    .week-day-number {
        font-size: 18px;
        color: #111827;
    }

    .week-day-name {
        font-size: 12px;
        color: #6b7280;
        text-transform: uppercase;
    }

    /* Day View Styles */
    .calendar-day-view {
        background: white;
        border-radius: 8px;
        padding: 20px;
        min-height: 500px;
    }

    .day-view-header {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e5e7eb;
    }

    .day-view-events {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .day-event-item {
        padding: 15px;
        border-left: 4px solid;
        border-radius: 6px;
        background: #f9fafb;
        transition: all 0.2s;
    }

    .day-event-item:hover {
        background: #f3f4f6;
        transform: translateX(4px);
    }

    .day-event-time {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .day-event-title {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
    }
</style>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="calendar-container">
    <div class="calendar-header">
        <div class="calendar-nav">
            @if($view == 'month')
                <a href="{{ route('calendar.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month, 'view' => $view]) }}" class="btn btn-sm btn-light">
                    &lt;
                </a>
                <span class="calendar-month">{{ $startDate->format('F Y') }}</span>
                <a href="{{ route('calendar.index', ['year' => $startDate->copy()->addMonth()->year, 'month' => $startDate->copy()->addMonth()->month, 'view' => $view]) }}" class="btn btn-sm btn-light">
                    &gt;
                </a>
            @elseif($view == 'week')
                <a href="{{ route('calendar.index', ['date' => $prevMonth->format('Y-m-d'), 'view' => $view]) }}" class="btn btn-sm btn-light">
                    &lt;
                </a>
                <span class="calendar-month">{{ $startDate->format('M d') }} - {{ $endDate->format('M d, Y') }}</span>
                <a href="{{ route('calendar.index', ['date' => $startDate->copy()->addWeek()->format('Y-m-d'), 'view' => $view]) }}" class="btn btn-sm btn-light">
                    &gt;
                </a>
            @elseif($view == 'day')
                <a href="{{ route('calendar.index', ['date' => $prevMonth->format('Y-m-d'), 'view' => $view]) }}" class="btn btn-sm btn-light">
                    &lt;
                </a>
                <span class="calendar-month">{{ $dayDate->format('l, F d, Y') }}</span>
                <a href="{{ route('calendar.index', ['date' => $dayDate->copy()->addDay()->format('Y-m-d'), 'view' => $view]) }}" class="btn btn-sm btn-light">
                    &gt;
                </a>
            @endif
        </div>
        
        <button type="button" class="add-event-btn" data-toggle="modal" data-target="#addEventModal">
            Add Event +
        </button>
        
        <div class="view-toggle">
            @if($view == 'month')
                <a href="{{ route('calendar.index', ['year' => $year, 'month' => $month, 'view' => 'month']) }}" 
                   class="btn {{ $view == 'month' ? 'active' : '' }}" style="text-decoration: none;">month</a>
                <a href="{{ route('calendar.index', ['date' => $startDate->format('Y-m-d'), 'view' => 'week']) }}" 
                   class="btn {{ $view == 'week' ? 'active' : '' }}" style="text-decoration: none;">week</a>
                <a href="{{ route('calendar.index', ['date' => $startDate->format('Y-m-d'), 'view' => 'day']) }}" 
                   class="btn {{ $view == 'day' ? 'active' : '' }}" style="text-decoration: none;">day</a>
            @else
                <a href="{{ route('calendar.index', ['year' => $currentDate->year, 'month' => $currentDate->month, 'view' => 'month']) }}" 
                   class="btn {{ $view == 'month' ? 'active' : '' }}" style="text-decoration: none;">month</a>
                <a href="{{ route('calendar.index', ['date' => $currentDate->format('Y-m-d'), 'view' => 'week']) }}" 
                   class="btn {{ $view == 'week' ? 'active' : '' }}" style="text-decoration: none;">week</a>
                <a href="{{ route('calendar.index', ['date' => $currentDate->format('Y-m-d'), 'view' => 'day']) }}" 
                   class="btn {{ $view == 'day' ? 'active' : '' }}" style="text-decoration: none;">day</a>
            @endif
        </div>
    </div>
    
    @if($view == 'month')
        <div class="calendar-grid">
            <!-- Day Headers -->
            <div class="calendar-day-header">SUN</div>
            <div class="calendar-day-header">MON</div>
            <div class="calendar-day-header">TUE</div>
            <div class="calendar-day-header">WED</div>
            <div class="calendar-day-header">THU</div>
            <div class="calendar-day-header">FRI</div>
            <div class="calendar-day-header">SAT</div>
            
            <!-- Previous Month Days -->
            @foreach($trailingDays as $day)
                <div class="calendar-day other-month">
                    <div class="day-number">{{ $day }}</div>
                </div>
            @endforeach
            
            <!-- Current Month Days -->
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $currentDate = Carbon\Carbon::create($year, $month, $day);
                    $isToday = $currentDate->isToday();
                    $dayEvents = $events->filter(function($event) use ($currentDate) {
                        return $currentDate->between($event->start_date, $event->end_date ?? $event->start_date);
                    });
                @endphp
                <div class="calendar-day {{ $isToday ? 'today' : '' }}">
                    <div class="day-number">{{ $day }}</div>
                    @foreach($dayEvents as $event)
                        <div class="event-item" style="background: {{ $event->color }}20;">
                            <div class="event-color-bar" style="background: {{ $event->color }};"></div>
                            <span class="event-title" style="color: {{ $event->color }};">{{ $event->title }}</span>
                        </div>
                    @endforeach
                </div>
            @endfor
            
            <!-- Next Month Days -->
            @foreach($leadingDays as $day)
                <div class="calendar-day other-month">
                    <div class="day-number">{{ $day }}</div>
                </div>
            @endforeach
        </div>
    @elseif($view == 'week')
        <div class="calendar-week-grid">
            <!-- Day Headers -->
            <div class="calendar-day-header">SUN</div>
            <div class="calendar-day-header">MON</div>
            <div class="calendar-day-header">TUE</div>
            <div class="calendar-day-header">WED</div>
            <div class="calendar-day-header">THU</div>
            <div class="calendar-day-header">FRI</div>
            <div class="calendar-day-header">SAT</div>
            
            <!-- Week Days -->
            @foreach($weekDays as $weekDay)
                @php
                    $isToday = $weekDay->isToday();
                    $dayEvents = $events->filter(function($event) use ($weekDay) {
                        return $weekDay->between($event->start_date, $event->end_date ?? $event->start_date);
                    });
                @endphp
                <div class="calendar-week-day {{ $isToday ? 'today' : '' }}">
                    <div class="week-day-header">
                        <div class="week-day-name">{{ $weekDay->format('D') }}</div>
                        <div class="week-day-number">{{ $weekDay->format('d') }}</div>
                    </div>
                    @foreach($dayEvents as $event)
                        <div class="event-item" style="background: {{ $event->color }}20;">
                            <div class="event-color-bar" style="background: {{ $event->color }};"></div>
                            <span class="event-title" style="color: {{ $event->color }};">{{ $event->title }}</span>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @elseif($view == 'day')
        <div class="calendar-day-view">
            <div class="day-view-header">
                {{ $dayDate->format('l, F d, Y') }}
            </div>
            <div class="day-view-events">
                @forelse($events as $event)
                    <div class="day-event-item" style="border-left-color: {{ $event->color }};">
                        <div class="day-event-time">
                            {{ \Carbon\Carbon::parse($event->start_date)->format('g:i A') }}
                            @if($event->end_date && $event->end_date != $event->start_date)
                                - {{ \Carbon\Carbon::parse($event->end_date)->format('g:i A') }}
                            @endif
                        </div>
                        <div class="day-event-title" style="color: {{ $event->color }};">
                            {{ $event->title }}
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px; color: #6b7280;">
                        Tidak ada event pada hari ini
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Event</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('calendar.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Event Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>End Date (Optional)</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Color</label>
                        <select name="color" class="form-control">
                            <option value="#ef4444">Red</option>
                            <option value="#10b981" selected>Green</option>
                            <option value="#3b82f6">Blue</option>
                            <option value="#f59e0b">Orange</option>
                            <option value="#8b5cf6">Purple</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

