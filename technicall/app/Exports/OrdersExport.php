<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromQuery, WithHeadings, WithMapping
{
    protected $schoolId;
    protected $gradeId;
    protected $status;

    public function __construct($schoolId = null, $gradeId = null, $status = null)
    {
        $this->schoolId = $schoolId;
        $this->gradeId = $gradeId;
        $this->status = $status;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        $query = Order::query()
            ->with(['student.grade.school', 'orderItems.product', 'orderItems.photo']);

        // Filter by school
        if ($this->schoolId) {
            $query->whereHas('student.grade.school', function ($q) {
                $q->where('id', $this->schoolId);
            });
        }

        // Filter by grade
        if ($this->gradeId) {
            $query->whereHas('student.grade', function ($q) {
                $q->where('id', $this->gradeId);
            });
        }

        // Filter by status
        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Order ID',
            'Order Date',
            'Student Name',
            'Grade',
            'School',
            'Parent Name',
            'Parent Email',
            'Parent Phone',
            'Total Price',
            'Status',
            'Items Count',
        ];
    }

    /**
     * Map the order data for export
     *
     * @param Order $order
     * @return array
     */
    public function map($order): array
    {
        return [
            $order->id,
            $order->created_at->format('Y-m-d H:i:s'),
            $order->student->name ?? 'N/A',
            $order->student->grade->name ?? 'N/A',
            $order->student->grade->school->name ?? 'N/A',
            $order->parent_name,
            $order->parent_email,
            $order->parent_phone_number,
            number_format($order->total_price, 2),
            ucfirst($order->status),
            $order->orderItems->count(),
        ];
    }
}
