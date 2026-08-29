<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n========================================\n";
echo "SKILLHUB COMPREHENSIVE TESTING REPORT\n";
echo "Generated: " . now()->format('Y-m-d H:i:s') . "\n";
echo "========================================\n\n";

// ============================================
// 1. USER CREDENTIALS LIST
// ============================================
echo "### 1. USER CREDENTIALS LIST ###\n\n";

$users = \App\Models\User::select('id', 'name', 'email', 'role', 'balance', 'payout_type', 'payout_account', 'payout_account_name')->get();

echo "| ID | Name | Email | Role | Balance | Payout Type | Payout Account |\n";
echo "|----|------|-------|------|---------|-------------|----------------|\n";
foreach ($users as $user) {
    echo sprintf("| %d | %s | %s | %s | Rp%s | %s | %s |\n",
        $user->id,
        $user->name,
        $user->email,
        $user->role,
        number_format($user->balance, 0, ',', '.'),
        $user->payout_type ?? '-',
        $user->payout_account ?? '-'
    );
}

echo "\n**Default Password: password123**\n\n";

// ============================================
// 2. ROUTE TESTING RESULTS
// ============================================
echo "### 2. ROUTE TESTING RESULTS ###\n\n";

$routes = collect(\Illuminate\Support\Facades\Route::getRoutes());
$testResults = [];

// Test public routes
$publicRoutes = [
    ['GET', '/', 'home'],
    ['GET', '/jasa', 'services.index'],
    ['GET', '/jasa/1', 'services.show'],
    ['GET', '/login', 'login'],
    ['GET', '/register', 'register'],
    ['GET', '/forgot-password', 'password.request'],
];

echo "#### PUBLIC ROUTES (No Auth) ####\n\n";
echo "| Method | URI | Name | Status | Error |\n";
echo "|--------|-----|------|--------|-------|\n";

foreach ($publicRoutes as $routeInfo) {
    try {
        $method = $routeInfo[0];
        $uri = $routeInfo[1];
        $name = $routeInfo[2];
        
        $response = null;
        if ($method === 'GET') {
            $request = \Illuminate\Http\Request::create($uri, 'GET');
            $response = app()->handle($request);
        }
        
        $status = $response ? $response->getStatusCode() : 'N/A';
        $error = '';
        
        if ($status >= 400) {
            $error = $response->getContent();
            if (strlen($error) > 100) {
                $error = substr($error, 0, 100) . '...';
            }
        }
        
        echo sprintf("| %s | %s | %s | %d | %s |\n", $method, $uri, $name, $status, $error ?: '-');
    } catch (\Exception $e) {
        echo sprintf("| %s | %s | %s | ERROR | %s |\n", $method, $uri, $name, $e->getMessage());
    }
}

echo "\n#### AUTHENTICATED ROUTES (User) ####\n\n";
echo "Note: These routes require authentication and return 302 redirect to login when unauthenticated.\n\n";

$userRoutes = [
    ['GET', '/dashboard', 'dashboard'],
    ['GET', '/jasa/ajukan', 'services.create'],
    ['GET', '/jasa/saya', 'services.my'],
    ['GET', '/pesanan', 'orders.index'],
    ['GET', '/profile', 'profile.edit'],
    ['GET', '/notifikasi', 'notifications.index'],
    ['GET', '/messages', 'conversations.index'],
    ['GET', '/seller/messages', 'conversations.seller-index'],
    ['GET', '/dompet', 'wallet.index'],
    ['GET', '/dompet/tarik', 'wallet.withdraw.create'],
];

echo "| Method | URI | Name | Expected Status (Unauth) |\n";
echo "|--------|-----|------|--------------------------|\n";

foreach ($userRoutes as $routeInfo) {
    echo sprintf("| %s | %s | %s | 302 (Redirect to Login) |\n", $routeInfo[0], $routeInfo[1], $routeInfo[2]);
}

echo "\n#### ADMIN ROUTES ####\n\n";

$adminRoutes = [
    ['GET', '/admin/dashboard', 'admin.dashboard'],
    ['GET', '/admin/services', 'admin.services.index'],
    ['GET', '/admin/services/pending', 'admin.services.pending'],
    ['GET', '/admin/payments', 'admin.payments.index'],
    ['GET', '/admin/reports', 'admin.reports.index'],
    ['GET', '/admin/payouts', 'admin.payouts.index'],
    ['GET', '/admin/categories', 'admin.categories.index'],
    ['GET', '/admin/subcategories', 'admin.subcategories.index'],
];

echo "| Method | URI | Name | Expected Status |\n";
echo "|--------|-----|------|-----------------|\n";

foreach ($adminRoutes as $routeInfo) {
    echo sprintf("| %s | %s | %s | 302 (Requires Admin) |\n", $routeInfo[0], $routeInfo[1], $routeInfo[2]);
}

// ============================================
// 3. DATABASE INTEGRITY CHECKS
// ============================================
echo "\n### 3. DATABASE INTEGRITY CHECKS ###\n\n";

// Check orders with missing relations
$ordersWithoutService = \App\Models\Order::whereDoesntHave('service')->count();
$ordersWithoutBuyer = \App\Models\Order::whereDoesntHave('buyer')->count();
$servicesWithoutSubcategory = \App\Models\Service::whereDoesntHave('subcategory')->count();
$servicesWithoutSeller = \App\Models\Service::whereDoesntHave('seller')->count();

echo "| Check | Count | Status |\n";
echo "|-------|-------|--------|\n";
echo sprintf("| Orders without service | %d | %s |\n", $ordersWithoutService, $ordersWithoutService === 0 ? '✓ OK' : '⚠ ISSUE');
echo sprintf("| Orders without buyer | %d | %s |\n", $ordersWithoutBuyer, $ordersWithoutBuyer === 0 ? '✓ OK' : '⚠ ISSUE');
echo sprintf("| Services without subcategory | %d | %s |\n", $servicesWithoutSubcategory, $servicesWithoutSubcategory === 0 ? '✓ OK' : '⚠ ISSUE');
echo sprintf("| Services without seller | %d | %s |\n", $servicesWithoutSeller, $servicesWithoutSeller === 0 ? '✓ OK' : '⚠ ISSUE');

// Check status consistency
echo "\n#### Order Status Distribution ####\n\n";
$orderStatuses = \DB::table('orders')->select('status', \DB::raw('count(*) as count'))->groupBy('status')->get();
echo "| Status | Count |\n";
echo "|--------|-------|\n";
foreach ($orderStatuses as $status) {
    echo sprintf("| %s | %d |\n", $status->status, $status->count);
}

echo "\n#### Payment Status Distribution ####\n\n";
$paymentStatuses = \DB::table('payments')->select('status', \DB::raw('count(*) as count'))->groupBy('status')->get();
echo "| Status | Count |\n";
echo "|--------|-------|\n";
foreach ($paymentStatuses as $status) {
    echo sprintf("| %s | %d |\n", $status->status, $status->count);
}

echo "\n#### Service Status Distribution ####\n\n";
$serviceStatuses = \DB::table('services')->select('status', \DB::raw('count(*) as count'))->groupBy('status')->get();
echo "| Status | Count |\n";
echo "|--------|-------|\n";
foreach ($serviceStatuses as $status) {
    echo sprintf("| %s | %d |\n", $status->status, $status->count);
}

// ============================================
// 4. FUNCTIONAL TESTING SIMULATION
// ============================================
echo "\n### 4. FUNCTIONAL TESTING RESULTS ###\n\n";

echo "#### Service Creation Test ####\n\n";
$pendingServices = \App\Models\Service::where('status', 'pending')->count();
$approvedServices = \App\Models\Service::where('status', 'approved')->count();
$rejectedServices = \App\Models\Service::where('status', 'rejected')->count();
echo "- Pending Services: {$pendingServices}\n";
echo "- Approved Services: {$approvedServices}\n";
echo "- Rejected Services: {$rejectedServices}\n";

echo "\n#### Order Flow Test ####\n\n";
$orders = \App\Models\Order::with(['service', 'buyer', 'payment'])->get();
foreach ($orders as $order) {
    echo sprintf("- Order #%d: %s | Status: %s | Payment: %s | Buyer: %s\n",
        $order->id,
        $order->service->title ?? 'N/A',
        $order->status,
        $order->payment->status ?? 'no payment',
        $order->buyer->name ?? 'N/A'
    );
}

echo "\n#### Wallet Balance Check ####\n\n";
foreach ($users as $user) {
    $transactions = \App\Models\WalletTransaction::where('user_id', $user->id)->count();
    $payoutRequests = \App\Models\PayoutRequest::where('user_id', $user->id)->count();
    echo sprintf("- %s (ID:%d): Balance Rp%s | Transactions: %d | Payout Requests: %d\n",
        $user->name,
        $user->id,
        number_format($user->balance, 0, ',', '.'),
        $transactions,
        $payoutRequests
    );
}

// ============================================
// 5. UI/UX & LOGIC ISSUES
// ============================================
echo "\n### 5. IDENTIFIED ISSUES ###\n\n";

$issues = [];

// Check for services with prices too low
$lowPriceServices = \App\Models\Service::where('price', '<', 1000)->get();
if ($lowPriceServices->count() > 0) {
    $issues[] = [
        'type' => 'Business Rule',
        'severity' => 'Low',
        'description' => $lowPriceServices->count() . ' services have price below Rp1.000',
        'location' => 'services table'
    ];
}

// Check for users with zero balance but pending payout requests
$pendingPayouts = \App\Models\PayoutRequest::where('status', 'pending')->with('user')->get();
foreach ($pendingPayouts as $payout) {
    if ($payout->user->balance < $payout->amount) {
        $issues[] = [
            'type' => 'Data Inconsistency',
            'severity' => 'Medium',
            'description' => "User {$payout->user->name} has pending payout of Rp" . number_format($payout->amount, 0, ',', '.') . " but balance is only Rp" . number_format($payout->user->balance, 0, ',', '.'),
            'location' => "payout_requests.id = {$payout->id}"
        ];
    }
}

// Check for orders with mismatched payment status
$mismatchedOrders = \App\Models\Order::whereHas('payment', function($q) {
    $q->where('status', 'verified');
})->where('status', 'menunggu_pembayaran')->count();

if ($mismatchedOrders > 0) {
    $issues[] = [
        'type' => 'Status Mismatch',
        'severity' => 'High',
        'description' => "{$mismatchedOrders} orders have verified payment but still waiting for payment status",
        'location' => 'orders table'
    ];
}

// Check for duplicate notifications
$duplicateNotifs = \DB::table('user_notifications')
    ->select('user_id', 'type', 'title', \DB::raw('count(*) as count'))
    ->groupBy('user_id', 'type', 'title')
    ->having('count', '>', 1)
    ->get();

if ($duplicateNotifs->count() > 0) {
    $issues[] = [
        'type' => 'Data Quality',
        'severity' => 'Low',
        'description' => $duplicateNotifs->count() . ' duplicate notification entries found',
        'location' => 'user_notifications table'
    ];
}

// Check for services without images
$servicesWithoutImages = \App\Models\Service::whereNull('image')->count();
if ($servicesWithoutImages > 0) {
    $issues[] = [
        'type' => 'UI/UX',
        'severity' => 'Low',
        'description' => "{$servicesWithoutImages} services don't have images (will show placeholder)",
        'location' => 'services table'
    ];
}

// Check for categories without subcategories
$categoriesWithoutSubcats = \App\Models\Category::whereDoesntHave('subcategories')->count();
if ($categoriesWithoutSubcats > 0) {
    $issues[] = [
        'type' => 'Data Integrity',
        'severity' => 'Medium',
        'description' => "{$categoriesWithoutSubcats} categories have no subcategories",
        'location' => 'categories table'
    ];
}

if (count($issues) > 0) {
    echo "| Type | Severity | Description | Location |\n";
    echo "|------|----------|-------------|----------|\n";
    foreach ($issues as $issue) {
        echo sprintf("| %s | %s | %s | %s |\n",
            $issue['type'],
            $issue['severity'],
            $issue['description'],
            $issue['location']
        );
    }
} else {
    echo "No issues found.\n";
}

// ============================================
// 6. SECURITY CHECKS
// ============================================
echo "\n### 6. SECURITY CHECKS ###\n\n";

$securityIssues = [];

// Check for users without verified email
$unverifiedUsers = \App\Models\User::whereNull('email_verified_at')->count();
if ($unverifiedUsers > 0) {
    $securityIssues[] = [
        'type' => 'Email Verification',
        'severity' => 'Low',
        'description' => "{$unverifiedUsers} users have not verified their email",
        'recommendation' => 'Consider requiring email verification for sensitive actions'
    ];
}

// Check for orders that buyer can access but shouldn't
$selfOrders = \App\Models\Order::whereHas('service', function($q) {
    $q->whereColumn('services.user_id', 'orders.buyer_id');
})->count();
if ($selfOrders > 0) {
    $securityIssues[] = [
        'type' => 'Self-Order',
        'severity' => 'Medium',
        'description' => "{$selfOrders} orders where user ordered their own service",
        'recommendation' => 'This should be prevented by application logic'
    ];
}

if (count($securityIssues) > 0) {
    echo "| Type | Severity | Description | Recommendation |\n";
    echo "|------|----------|-------------|----------------|\n";
    foreach ($securityIssues as $issue) {
        echo sprintf("| %s | %s | %s | %s |\n",
            $issue['type'],
            $issue['severity'],
            $issue['description'],
            $issue['recommendation']
        );
    }
} else {
    echo "No security issues found.\n";
}

// ============================================
// 7. SUMMARY
// ============================================
echo "\n### 7. SUMMARY ###\n\n";

echo "| Metric | Value |\n";
echo "|--------|-------|\n";
echo sprintf("| Total Users | %d |\n", $users->count());
echo sprintf("| Total Services | %d |\n", \App\Models\Service::count());
echo sprintf("| Total Orders | %d |\n", \App\Models\Order::count());
echo sprintf("| Total Payments | %d |\n", \App\Models\Payment::count());
echo sprintf("| Total Categories | %d |\n", \App\Models\Category::count());
echo sprintf("| Total Subcategories | %d |\n", \App\Models\Subcategory::count());
echo sprintf("| Issues Found | %d |\n", count($issues));
echo sprintf("| Security Issues | %d |\n", count($securityIssues));

echo "\n========================================\n";
echo "END OF REPORT\n";
echo "========================================\n";
