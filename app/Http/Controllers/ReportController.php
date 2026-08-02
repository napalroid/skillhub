<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'reported_user_id' => 'required|exists:users,id',
        'order_id' => 'nullable|exists:orders,id',
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
public function index()
{
    $reports = Report::where('status', 'open')
        ->with(['reporter', 'reportedUser', 'order'])
        ->latest()
        ->paginate(15);

    return view('admin.reports.index', compact('reports'));
}

public function resolve(Request $request, Report $report)
{
    $validated = $request->validate([
        'status' => 'required|in:reviewed,closed',
    ]);

    $report->update($validated);

    return back()->with('success', 'Laporan berhasil diperbarui.');
}

}
