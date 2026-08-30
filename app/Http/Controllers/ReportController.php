<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reported_user_id' => 'required|exists:users,id',
            'order_id' => 'nullable|exists:orders,id',
            'reporter_role' => 'required|in:buyer,seller',
            'category' => 'required|string|max:255',
            'reason' => 'required|string|min:10|max:500',
        ]);

        if ($validated['reported_user_id'] == auth()->id()) {
            abort(403, 'Anda tidak bisa melaporkan diri sendiri.');
        }

        $validated['reporter_id'] = auth()->id();
        $validated['status'] = 'open';

        Report::create($validated);

        return back()->with('success', 'Laporan terkirim, tim admin akan meninjau.');
    }
    
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Report::with(['reporter', 'reportedUser', 'order.service']);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $reports = $query->latest()->paginate(15);
        
        // Statistics
        $stats = [
            'total' => Report::count(),
            'open' => Report::where('status', 'open')->count(),
            'reviewed' => Report::where('status', 'reviewed')->count(),
            'closed' => Report::where('status', 'closed')->count(),
        ];
        
        // Category breakdown
        $categoryStats = Report::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();
        
        // Monthly trend (last 6 months)
        $monthlyTrend = Report::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.reports.index', compact('reports', 'stats', 'categoryStats', 'monthlyTrend', 'status'));
    }

    public function resolve(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:reviewed,closed',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $report->update($validated);
        
        // Send notification to reporter
        NotificationService::createAndDispatch(
            userId: $report->reporter_id,
            type: 'report_update',
            title: 'Laporan Anda telah ditinjau',
            message: "Laporan Anda tentang '{$report->category}' telah diperbarui menjadi status '{$validated['status']}'. " . 
                        ($validated['admin_notes'] ? "Catatan admin: {$validated['admin_notes']}" : '')
        );

        return back()->with('success', 'Laporan berhasil diperbarui dan notifikasi telah dikirim ke pelapor.');
    }
}
