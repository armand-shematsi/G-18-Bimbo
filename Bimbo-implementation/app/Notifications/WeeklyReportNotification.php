<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class WeeklyReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reportData;
    protected $role;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
        $this->role = $reportData['report_type'] ?? 'general';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $roleName = $this->getRoleDisplayName($this->role);
        $period = $this->reportData['period'] ?? 'This Week';
        
        // Generate PDF report
        $pdfPath = $this->generatePDFReport($notifiable);
        
        $mailMessage = (new MailMessage)
            ->subject("Weekly Report - {$roleName} - {$period}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Here is your weekly report for {$period}.")
            ->line($this->getReportSummary())
            ->action('View Full Report', url('/dashboard'))
            ->line('Thank you for using our platform!');

        // Attach PDF if generated successfully
        if ($pdfPath && Storage::exists($pdfPath)) {
            $mailMessage->attach(Storage::path($pdfPath), [
                'as' => "weekly_report_{$period}.pdf",
                'mime' => 'application/pdf',
            ]);
        }

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'report_type' => $this->reportData['report_type'],
            'period' => $this->reportData['period'],
            'summary' => $this->reportData['summary'] ?? [],
        ];
    }

    /**
     * Generate PDF report
     */
    private function generatePDFReport($notifiable): ?string
    {
        try {
            $roleName = $this->getRoleDisplayName($this->role);
            $period = $this->reportData['period'] ?? 'This Week';
            
            $pdf = PDF::loadView('reports.weekly', [
                'reportData' => $this->reportData,
                'user' => $notifiable,
                'roleName' => $roleName,
                'period' => $period,
            ]);

            $filename = "weekly_report_{$notifiable->id}_" . now()->format('Y-m-d') . ".pdf";
            $path = "reports/weekly/{$filename}";
            
            Storage::put($path, $pdf->output());
            
            return $path;
        } catch (\Exception $e) {
            \Log::error('Failed to generate weekly PDF report: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get role display name
     */
    private function getRoleDisplayName(string $role): string
    {
        $roleNames = [
            'admin_weekly' => 'Administrator',
            'supplier_weekly' => 'Supplier',
            'bakery_manager_weekly' => 'Bakery Manager',
            'distributor_weekly' => 'Distributor',
            'retail_manager_weekly' => 'Retail Manager',
            'customer_weekly' => 'Customer',
        ];

        return $roleNames[$role] ?? 'User';
    }

    /**
     * Get report summary for email
     */
    private function getReportSummary(): string
    {
        $summary = $this->reportData['summary'] ?? [];
        
        switch ($this->role) {
            case 'admin_weekly':
                $growthRate = $summary['growth_rate'] ?? 0;
                return "Weekly Summary: {$summary['total_orders']} orders, \${$summary['total_revenue']} revenue, {$growthRate}% growth rate.";
            
            case 'supplier_weekly':
                $turnover = $summary['inventory_turnover'] ?? 0;
                return "Weekly Summary: {$summary['orders_fulfilled']} orders fulfilled, \${$summary['total_order_value']} total value, {$turnover} inventory turnover.";
            
            case 'bakery_manager_weekly':
                $efficiency = $summary['production_efficiency'] ?? 0;
                return "Weekly Summary: {$summary['batches_completed']} batches completed, {$efficiency}% production efficiency.";
            
            case 'distributor_weekly':
                $efficiency = $summary['delivery_efficiency'] ?? 0;
                return "Weekly Summary: {$summary['deliveries_completed']} deliveries completed, {$efficiency}% delivery efficiency.";
            
            case 'retail_manager_weekly':
                $growthRate = $summary['growth_rate'] ?? 0;
                return "Weekly Summary: \${$summary['total_sales']} total sales, {$summary['total_orders']} orders, {$growthRate}% growth rate.";
            
            case 'customer_weekly':
                return "Weekly Summary: {$summary['orders_placed']} orders placed, \${$summary['total_spent']} total spent.";
            
            default:
                return "Weekly report summary available in the attached PDF.";
        }
    }
} 