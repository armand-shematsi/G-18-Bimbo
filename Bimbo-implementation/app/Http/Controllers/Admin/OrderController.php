<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display all orders in the system with filtering and search.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items', 'payment', 'vendor']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(20);

        // Get statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        // Get vendors for filter
        $vendors = Vendor::all();

        return view('admin.orders.index', compact(
            'orders', 
            'totalOrders', 
            'pendingOrders', 
            'processingOrders', 
            'shippedOrders', 
            'deliveredOrders', 
            'cancelledOrders',
            'vendors'
        ));
    }

    /**
     * Show a specific order with full details.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items', 'payment', 'vendor']);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing an order.
     */
    public function edit(Order $order)
    {
        $order->load(['user', 'items', 'payment', 'vendor']);
        $vendors = Vendor::all();
        
        return view('admin.orders.edit', compact('order', 'vendors'));
    }

    /**
     * Update the specified order.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'total' => 'required|numeric|min:0',
            'payment_status' => 'required|in:unpaid,paid,refunded',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'fulfillment_type' => 'nullable|string',
            'tracking_number' => 'nullable|string',
            'delivery_option' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Get order analytics and statistics.
     */
    public function analytics()
    {
        // Daily order trends (last 30 days)
        $dailyOrders = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $count = Order::whereDate('created_at', $date)->count();
            $dailyOrders->push([
                'date' => $date,
                'count' => $count
            ]);
        }

        // Order status distribution
        $statusDistribution = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Vendor performance
        $vendorPerformance = Order::with('vendor')
            ->select('vendor_id', DB::raw('count(*) as order_count'), DB::raw('sum(total) as total_revenue'))
            ->groupBy('vendor_id')
            ->get();

        // Monthly revenue (last 12 months)
        $monthlyRevenue = collect();
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total');
            $monthlyRevenue->push([
                'month' => $month->format('M Y'),
                'revenue' => $revenue
            ]);
        }

        return view('admin.orders.analytics', compact(
            'dailyOrders',
            'statusDistribution',
            'vendorPerformance',
            'monthlyRevenue'
        ));
    }

    /**
     * Get orders API for real-time updates.
     */
    public function apiOrders(Request $request)
    {
        $query = Order::with(['user', 'vendor']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->take(10)->get();

        return response()->json($orders);
    }

    /**
     * Get order statistics API.
     */
    public function apiStats()
    {
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'today' => Order::whereDate('created_at', today())->count(),
            'this_week' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Order::whereMonth('created_at', now()->month)->count(),
        ];

        return response()->json($stats);
    }
} 