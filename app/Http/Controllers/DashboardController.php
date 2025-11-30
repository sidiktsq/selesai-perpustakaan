<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Display a listing of the dashboards.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dashboards = Dashboard::latest()->paginate(10);
        return view('dashboard', compact('dashboards'));
    }

    /**
     * Show the form for creating a new dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboards.create');
    }

    /**
     * Store a newly created dashboard in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'position' => 'nullable|integer',
        ]);

        $dashboard = Dashboard::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'position' => $validated['position'] ?? 0,
        ]);

        return redirect()->route('dashboards.show', $dashboard->id)
            ->with('success', 'Dashboard created successfully.');
    }

    /**
     * Display the specified dashboard.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function show(Dashboard $dashboard)
    {
        return view('dashboards.show', compact('dashboard'));
    }

    /**
     * Show the form for editing the specified dashboard.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function edit(Dashboard $dashboard)
    {
        return view('dashboards.edit', compact('dashboard'));
    }

    /**
     * Update the specified dashboard in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'position' => 'nullable|integer',
        ]);

        $dashboard->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'position' => $validated['position'] ?? $dashboard->position,
        ]);

        return redirect()->route('dashboards.show', $dashboard->id)
            ->with('success', 'Dashboard updated successfully.');
    }

    /**
     * Remove the specified dashboard from storage.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dashboard $dashboard)
    {
        $dashboard->delete();
        
        return redirect()->route('dashboards.index')
            ->with('success', 'Dashboard deleted successfully.');
    }
    
    /**
     * Toggle the active status of the specified dashboard.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(Dashboard $dashboard)
    {
        $dashboard->update([
            'is_active' => !$dashboard->is_active
        ]);
        
        $status = $dashboard->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Dashboard {$status} successfully.");
    }
}
