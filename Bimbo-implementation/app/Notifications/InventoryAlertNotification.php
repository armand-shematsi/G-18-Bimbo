<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $lowStockItems;

    /**
     * Create a new notification instance.
     */
    public function __construct($lowStockItems)
    {
        $this->lowStockItems = $lowStockItems;
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
        $itemCount = $this->lowStockItems->count();
        $date = now()->format('Y-m-d H:i');
        
        // Generate PDF alert report
        $pdfPath = $this->generatePDFAlert($notifiable);
        
        $mailMessage = (new MailMessage)
            ->subject("🚨 Inventory Alert - {$itemCount} Low Stock Items")
            ->greeting("Hello {$notifiable->name}!")
            ->line("This is an urgent inventory alert for {$date}.")
            ->line("We have identified {$itemCount} items that are running low on stock.")
            ->line($this->getAlertSummary())
            ->action('View Inventory Dashboard', url('/dashboard'))
            ->line('Please take immediate action to restock these items.');

        // Attach PDF if generated successfully
        if ($pdfPath && Storage::exists($pdfPath)) {
            $mailMessage->attach(Storage::path($pdfPath), [
                'as' => "inventory_alert_{$date}.pdf",
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
            'alert_type' => 'inventory_low_stock',
            'items_count' => $this->lowStockItems->count(),
            'items' => $this->lowStockItems->toArray(),
        ];
    }

    /**
     * Generate PDF alert report
     */
    private function generatePDFAlert($notifiable): ?string
    {
        try {
            $date = now()->format('Y-m-d H:i');
            
            $pdf = PDF::loadView('reports.inventory-alert', [
                'lowStockItems' => $this->lowStockItems,
                'user' => $notifiable,
                'date' => $date,
            ]);

            $filename = "inventory_alert_{$notifiable->id}_" . now()->format('Y-m-d_H-i') . ".pdf";
            $path = "reports/alerts/{$filename}";
            
            Storage::put($path, $pdf->output());
            
            return $path;
        } catch (\Exception $e) {
            \Log::error('Failed to generate inventory alert PDF: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get alert summary for email
     */
    private function getAlertSummary(): string
    {
        $criticalItems = $this->lowStockItems->where('quantity', '<=', 5);
        $lowItems = $this->lowStockItems->where('quantity', '>', 5);
        
        $summary = "Critical items (≤5 units): {$criticalItems->count()}\n";
        $summary .= "Low stock items (6-10 units): {$lowItems->count()}";
        
        if ($criticalItems->count() > 0) {
            $summary .= "\n\nCritical items that need immediate attention:\n";
            foreach ($criticalItems->take(3) as $item) {
                $productName = $item->product->name ?? 'Unknown Product';
                $summary .= "• {$productName}: {$item->quantity} units remaining\n";
            }
        }
        
        return $summary;
    }
} 