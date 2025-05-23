<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fdfdfd;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 15px;
        }

        .info p {
            margin: 4px 0;
        }

        hr {
            border: 0;
            border-top: 1px dashed #aaa;
            margin: 20px 0;
        }

        .transaction-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .transaction-table td,
        .transaction-table th {
            padding: 8px;
            text-align: left;
        }

        .transaction-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .total {
            font-weight: bold;
            text-align: right;
            font-size: 16px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>

    <h2>KUITANSI PEMBAYARAN</h2>

    <div class="info">
        <p><strong>Invoice:</strong> {{ $sale->invoice }}</p>
        <p><strong>Tanggal:</strong> {{ $sale->created_at }}</p>
        <p><strong>Kasir:</strong> {{ $sale->cashier->name }}</p>
        <p><strong>Pelanggan:</strong> {{ $sale->customer->name ?? 'Umum' }}</p>
    </div>

    <hr>

    <table class="transaction-table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->details as $item)
            <tr>
                <td>{{ $item->product->title }}</td>
                <td>{{ $item->qty }}</td>
                <td>Rp. {{ number_format($item->price, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($item->qty * $item->price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <div class="total">
        <p><strong>Subtotal:</strong> Rp. {{ number_format($sale->grand_total, 0, ',', '.') }}</p>
        <p><strong>Diskon:</strong> Rp. {{ number_format($sale->discount ?? 0, 0, ',', '.') }}</p>
        <p><strong>Tunai:</strong> Rp. {{ number_format($sale->cash, 0, ',', '.') }}</p>
        <p><strong>Kembali:</strong> Rp. {{ number_format($sale->change, 0, ',', '.') }}</p>
    </div>

    <div class="footer">
        Terima kasih atas pembelian Anda!<br>
        CAFE-MAMI - Tangerang
    </div>

</body>
</html>
