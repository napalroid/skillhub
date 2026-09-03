<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryRequest;
use App\Services\NotificationService;

class CategoryRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_type' => 'required|in:category_request,subcategory_request',
            'requested_category_name' => 'required_if:request_type,category_request|nullable|string|max:255',
            'existing_category_id' => 'required_if:request_type,subcategory_request|nullable|exists:categories,id',
            'requested_subcategory_name' => 'required_if:request_type,subcategory_request|nullable|string|max:255',
            'reason_for_request' => 'required|string|min:10|max:1000',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        CategoryRequest::create($validated);

        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationService::createAndDispatch(
                userId: $admin->id,
                type: 'category_request',
                title: 'Request Kategori/Subkategori Baru',
                message: auth()->user()->name . ' mengajukan ' . 
                    ($validated['request_type'] === 'category_request' ? 'kategori baru' : 'subkategori baru')
            );
        }

        return back()->with('success', 'Request kategori/subkategori berhasil dikirim. Admin akan meninjau permintaan Anda.');
    }
}
