<?php

namespace App\Http\Controllers\Backend\Accounting;

use App\Models\Account;
use App\Models\Booking;
use App\Models\Journal;
use App\Models\BookingCost;
use Illuminate\Http\Request;
use App\Helpers\FinanceHelper;
use App\Services\JournalService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ExpenseController extends Controller
{
    public function index()
    {
        $bookings = Booking::whereIn('status', ['booked', 'paid'])
            ->orderBy('created_at', 'desc')->get();
        $expense = BookingCost::all();
        $accounts = Account::all();
        return view('admin.accounting.index', compact('bookings','expense', 'accounts'));
    }

    public function create($id)
    {
        $booking = Booking::findOrFail($id);
        $accounts = Account::all();
        return view('admin.accounting.create', compact('booking', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'expenses' => 'required|array|min:1',
            'expenses.*.BookingId' => 'required|integer|exists:bookings,id',
            'expenses.*.AccountId' => 'required|integer|exists:accounts,id',
            'expenses.*.ExpenDescript' => 'required|string|max:255',
            'expenses.*.Amount' => 'required|string',
        ]);

        try {
            $savedItems = [];

            // Inisialisasi JournalService
            $journalService = new JournalService();

            foreach ($validatedData['expenses'] as $index => $expense) {
                $amount = $expense['Amount'];
                $amount = str_replace(',', '', $amount);

                if ($amount <= 0) {
                    throw new \Exception("Amount must be greater than 0 for item " . ($index + 1));
                }

                $expenseData = [
                    'booking_id' => $expense['BookingId'],
                    'account_id' => $expense['AccountId'],
                    'description' => $expense['ExpenDescript'],
                    'amount' => $amount,
                ];

                try {
                    Log::info('Pre Create Check', [
                        'booking_exists' => \App\Models\Booking::find($expense['BookingId']) !== null,
                        'account_exists' => \App\Models\Account::find($expense['AccountId']) !== null,
                    ]);

                    $savedItem = BookingCost::create($expenseData);

                    if ($savedItem) {
                        $journalService->createExpenseJournal($savedItem);
                    }

                    // Log the successful save
                    Log::info('Berhasil simpan:', $savedItem->toArray());

                } catch (\Exception $e) {
                    Log::error('Gagal simpan BookingCost:', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'data' => $expenseData,
                    ]);
                }

                if (!$savedItem) {
                    throw new \Exception("Failed to save expense item " . ($index + 1));
                }

                $savedItems[] = $savedItem;
            }

            return redirect()->route('all.expenses')->with([
                'message' => count($savedItems) > 1
                    ? 'All expense items have been saved successfully'
                    : 'Expense item has been saved successfully',
                'alert-type' => 'success',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Expense validation failed', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
                'request' => $request->except('_token'),
                'user' => auth()->user() ? auth()->user()->id : 'guest',
            ]);
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Expense saving failed', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
                'request' => $request->except('_token'),
                'user' => auth()->user() ? auth()->user()->id : 'guest',
            ]);

            return back()->withInput()->with([
                'message' => 'Failed to save expenses: ' . $e->getMessage(),
                'alert-type' => 'error',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id (ini adalah Booking ID)
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            // Ambil data booking berdasarkan ID, Eager load package
            $booking = Booking::findOrFail($id);
            $accountId = $booking->account_id;

            // Ambil data pengeluaran (BookingCost) berdasarkan booking_id
            $expenses = BookingCost::where('booking_id', $id)
            ->orderBy('created_at', 'desc')
            ->with('account')
            ->get();

            // Hitung total biaya
            $total_cost = $expenses->sum('amount');

            // Log data yang diambil
            Log::info('Show Expense Data', [
                'booking' => $booking->toArray(),
                'expenses' => $expenses->toArray(),
                'total_cost' => $total_cost,
            ]);

            // Return view dengan data
            return view('admin.accounting.show', compact('booking', 'expenses', 'total_cost'));

        } catch (\Exception $e) {
            // Log error
            Log::error('Error in show method', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Redirect dengan pesan error
            return redirect()->back()->with('error', 'Failed to retrieve data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expense = BookingCost::findOrFail($id);
        $bookings = Booking::whereIn('status', ['booked', 'paid'])
            ->orderBy('created_at', 'desc')->get();
        $accounts = Account::all();
        return view('admin.accounting.edit', compact('expense', 'bookings', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'expenses' => 'required|array|min:1',
            'expenses.*.BookingId' => 'required|integer|exists:bookings,id',
            'expenses.*.AccountId' => 'required|integer|exists:accounts,id',
            'expenses.*.ExpenDescript' => 'required|string|max:255',
            'expenses.*.Amount' => 'required|string',
        ]);

        try {
            $savedItems = [];
            foreach ($validatedData['expenses'] as $index => $expenseData) {
                $amount = str_replace(',', '', $expenseData['Amount']);

                if ($amount <= 0) {
                    throw new \Exception("Amount must be greater than 0 for item " . ($index + 1));
                }

                // Find the expense item by its ID
                // Assuming the ID is passed in the request as part of the expense data
                // If the ID is not part of the expense data, you need to adjust this line
                // to get the correct ID from the request
                // For example, if the ID is passed as 'expenses.*.id', you can access it like this:
                // $expenseItemId = $expenseData['id'];
                $expenseItem = BookingCost::findOrFail($id);

                $expenseDataToUpdate = [ // Array untuk update
                    'booking_id' => $expenseData['BookingId'],
                    'account_id' => $expenseData['AccountId'],
                    'description' => $expenseData['ExpenDescript'],
                    'amount' => $amount,
                ];

                $expenseItem->update($expenseDataToUpdate);

                // Update the journal entry
                $journalService = new JournalService();
                $journalService->updateExpenseJournal($expenseItem);

                $savedItems[] = $expenseItem;

                Log::info('Berhasil update:', $expenseItem->toArray());

            }
             return redirect()->route('all.expenses')->with([
                'message' =>  'Expense item has been updated successfully',
                'alert-type' => 'success',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Expense validation failed', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
                'request' => $request->except('_token'),
                'user' => auth()->user() ? auth()->user()->id : 'guest',
            ]);
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Expense saving failed', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
                'request' => $request->except('_token'),
                'user' => auth()->user() ? auth()->user()->id : 'guest',
            ]);

            return back()->withInput()->with([
                'message' => 'Failed to update expenses: ' . $e->getMessage(),
                'alert-type' => 'error',
            ]);
        }
    }


    public function showJournals($id)
    {
        $booking = Booking::with('journalEntries.account')->findOrFail($id);

        $finance = FinanceHelper::calculateBookingHpp($booking);

        return view('admin.accounting.journals', [
            'booking' => $booking,
            'journals' => $booking->journals,
            'finance' => $finance
        ]);
    }



    public function fixJournalEntriesBookingId($id)
    {
        Artisan::call('fix:booking-journal-entries');

        return redirect()->route('booking.journals', ['id' => $id])->with([
            'message' => 'Booking Journal Entries successfully Fixed for Booking ID ' . $id,
            'alert-type' => 'success',
        ]);
    }

}
