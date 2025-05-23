<?php

namespace App\Http\Controllers\Apps;

use Inertia\Inertia;
use App\Models\Transaction;
use App\Exports\SalesExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendSaleInvoice;  // Import mail class

class SaleController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return Inertia::render('Apps/Sales/Index');
    }
    
    /**
     * filter
     *
     * @param  mixed $request
     * @return void
     */
    public function filter(Request $request)
    {
        $request->validate([
            'start_date'  => 'required',
            'end_date'    => 'required',
        ]);

        //get data sales by range date
        $sales = Transaction::with('cashier', 'customer')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->get();

        //get total sales by range date    
        $total = Transaction::whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->sum('grand_total');
        
        return Inertia::render('Apps/Sales/Index', [
            'sales'    => $sales,
            'total'    => (int) $total
        ]);
    }

    /**
     * export
     *
     * @param  mixed $request
     * @return void
     */
    public function export(Request $request)
    {
        return Excel::download(new SalesExport($request->start_date, $request->end_date), 'sales : '.$request->start_date.' — '.$request->end_date.'.xlsx');
    }
    
    /**
     * pdf
     *
     * @param  mixed $request
     * @return void
     */
    public function pdf(Request $request)
    {
        //get sales by range date
        $sales = Transaction::with('cashier', 'customer')->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->get();

        //get total sales by range daate
        $total = Transaction::whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->sum('grand_total');

        //load view PDF with data
        $pdf = PDF::loadView('exports.sales', compact('sales', 'total'));

        //return PDF for preview / download
        return $pdf->download('sales : '.$request->start_date.' — '.$request->end_date.'.pdf');
    }

    /**
     * printNota
     *
     * @param  mixed $id
     * @return void
     */
    public function printNota($id)
    {
        $transaction = Transaction::with(['cashier', 'customer', 'details.product'])->findOrFail($id);

        return view('print.nota', compact('transaction'));
    }

    /**
 * Send Email with Invoice PDF
 *
 * @param Request $request
 * @return \Illuminate\Http\Response
 */
public function sendEmail(Request $request)
{
    // Validate the incoming data
    $request->validate([
        'email' => 'required|email',
        'sale_id' => 'required|exists:transactions,id', // Validate sale_id
    ]);

    // Find the sale transaction
    $sale = Transaction::with('cashier', 'customer')->findOrFail($request->sale_id);

    // Generate PDF for the sale
    $pdf = PDF::loadView('exports.sales_single', ['sale' => $sale]);

    // Send the email with the generated PDF as an attachment
    Mail::to($request->email)->send(new SendSaleInvoice($sale, $pdf));

    // Return a success message to the frontend
    return redirect()->back()->with('success', 'Email successfully sent to ' . $request->email);

}

}
