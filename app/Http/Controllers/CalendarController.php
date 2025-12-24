<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->get('view', 'month');
        
        // For month view, prefer year and month parameters; otherwise use date
        if ($view === 'month' && $request->has('year') && $request->has('month')) {
            $year = $request->get('year');
            $month = $request->get('month');
            $currentDate = Carbon::create($year, $month, 1);
            $date = $currentDate->format('Y-m-d'); // Ensure $date is always defined
        } else {
            $date = $request->get('date', date('Y-m-d'));
            $currentDate = Carbon::parse($date);
            $year = $currentDate->year;
            $month = $currentDate->month;
        }
        
        $day = $currentDate->day;

        // Initialize variables
        $startDate = null;
        $endDate = null;
        $events = collect();
        $firstDayOfWeek = 0;
        $daysInMonth = 0;
        $trailingDays = [];
        $leadingDays = [];
        $prevMonth = null;
        $weekDays = [];
        $dayDate = null;

        if ($view === 'month') {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Get events for the month
            $events = Event::whereBetween('start_date', [$startDate, $endDate])
                ->orWhere(function($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                })
                ->get();

            // Get first day of month and what day of week it falls on
            $firstDayOfWeek = $startDate->dayOfWeek; // 0 = Sunday, 6 = Saturday
            $daysInMonth = $startDate->daysInMonth;
            
            // Get previous month's trailing days
            $prevMonth = $startDate->copy()->subMonth();
            $daysInPrevMonth = $prevMonth->daysInMonth;
            $trailingDays = [];

            for ($i = $firstDayOfWeek - 1; $i >= 0; $i--) {
                $trailingDays[] = $daysInPrevMonth - $i;
            }

            // Get next month's leading days
            $totalCells = count($trailingDays) + $daysInMonth;
            $remainingCells = 42 - $totalCells; // 6 rows x 7 days
            $leadingDays = [];
            for ($i = 1; $i <= $remainingCells; $i++) {
                $leadingDays[] = $i;
            }
        } elseif ($view === 'week') {
            // Week view: start from Sunday of the week
            $startDate = $currentDate->copy()->startOfWeek(Carbon::SUNDAY);
            $endDate = $startDate->copy()->endOfWeek(Carbon::SATURDAY);

            // Get events for the week
            $events = Event::where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })->get();

            // Generate week days
            for ($i = 0; $i < 7; $i++) {
                $weekDays[] = $startDate->copy()->addDays($i);
            }

            $prevMonth = $startDate->copy()->subWeek();
        } elseif ($view === 'day') {
            // Day view: single day
            $startDate = $currentDate->copy()->startOfDay();
            $endDate = $currentDate->copy()->endOfDay();
            $dayDate = $currentDate;

            // Get events for the day
            $events = Event::where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })->get();

            $prevMonth = $currentDate->copy()->subDay();
        }

        return view('calendar.index', compact(
            'year',
            'month',
            'day',
            'view',
            'date',
            'currentDate',
            'startDate',
            'endDate',
            'events',
            'firstDayOfWeek',
            'daysInMonth',
            'trailingDays',
            'leadingDays',
            'prevMonth',
            'weekDays',
            'dayDate'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'color' => 'nullable|string',
        ]);

        Event::create([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date ?? $request->start_date,
            'color' => $request->color ?? '#3b82f6',
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Event berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Check if user owns the event
        if ($event->user_id !== Auth::id() && Auth::user()->role !== 'superadmin') {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'color' => 'nullable|string',
        ]);

        $event->update([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date ?? $request->start_date,
            'color' => $request->color ?? $event->color,
        ]);

        return redirect()->back()->with('success', 'Event berhasil diperbarui');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Check if user owns the event
        if ($event->user_id !== Auth::id() && Auth::user()->role !== 'superadmin') {
            abort(403);
        }

        $event->delete();

        return redirect()->back()->with('success', 'Event berhasil dihapus');
    }
}
