<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;

class AdminInvoiceController extends Controller
{

    public function show(Request $request)
    {
        $order = Order::with('items.product', 'user')->findOrFail($request->order_id);

        return view('admin.invoice', compact('order'));
    }
public function downloadPdf(Order $order)
{
    $order->load('items.product', 'user');

    $pdf = Pdf::loadView('admin.invoice_pdf', compact('order'));

    return $pdf->download("bon-de-commande-{$order->id}.pdf");
}

}
