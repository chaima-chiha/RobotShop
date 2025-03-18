@extends('layouts.app')

@section('title', 'Facturation')

@section('content')
<div class="container mt-5">
    <div class="invoice-container" id="invoice-container">
        <div class="invoice-header">
            <h2>Invoice</h2>
            <p><strong>Invoice #:</strong> INV-12345</p>
            <p><strong>Date:</strong>{{ now()->format('Y-m-d') }}</p>
        </div>
        <div class="company-details">
            <p><strong>Your Company Name</strong></p>
            <p>Your Company Address</p>
            <p>City, Country</p>
            <p>Email: your-email@example.com</p>
            <p>Phone: +123 456 7890</p>
        </div>
        <div class="client-details">
            <p><strong>Billed To:</strong></p>
            <p>Client Name</p>
            <p>Client Address</p>
            <p>City, Country</p>
        </div>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="cart-table-body">
                <!-- Cart items will be dynamically loaded here -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
                    <td id="cart-subtotal">$0.00</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>Tax (10%):</strong></td>
                    <td id="cart-tax">$0.00</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                    <td id="cart-total">$0.00</td>
                </tr>
            </tfoot>
        </table>
        <div class="payment-terms mt-3">
            <p><strong>Payment Terms:</strong> Please pay within 30 days of the invoice date.</p>
            <p><strong>Payment Methods:</strong> Bank Transfer, Credit Card, PayPal</p>
        </div>
    </div>
    <div id="loading" style="display: none;">Loading...</div>
    <div class="no-print">
        <button id="pay-order-btn" class="btn btn-primary">Payer Facture</button>
        <button id="print-order-btn" class="btn btn-primary">Imprimer Facture</button>
    </div>
</div>

@include('cart.checkout_js')

@endsection

<style>
    @media print {
        .no-print {
            display: none;
        }
        .invoice-container {
            width: 100%;
            margin: 0;
            padding: 0;
        }
    }
    .invoice-header, .company-details, .client-details, .payment-terms {
        margin-bottom: 20px;
    }
</style>
