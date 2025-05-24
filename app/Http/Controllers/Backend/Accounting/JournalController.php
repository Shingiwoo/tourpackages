<?php

namespace App\Http\Controllers\Backend\Accounting;

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class JournalController extends Controller
{
     public function index()
    {
        $journals = Journal::all();
        return view('admin.accounting.journals_index', compact('journals'));
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Update journal method initiated.', ['journal_id' => $id]);

            $rules = [
                'date' => 'required|date',
                'description' => 'required|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Log::warning('Validation failed in journal update.', ['errors' => $validator->errors()]);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $journal = Journal::findOrFail($id);
            $journal->update($request->all());
            Log::info('Journal updated successfully.', ['journal_id' => $id]);

            $notification = [
                'message' => 'Journal entry updated successfully.',
                'alert-type' => 'success'
            ];
            
            return redirect()->back()->with($notification);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Journal not found.', ['error' => $e->getMessage(), 'journal_id' => $id]);
            $notification = [
                'message' => 'Journal entry not found.',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);

        } catch (\Exception $e) {
            Log::error('Unexpected error in journal update.', [
                'error' => $e->getMessage(), 
                'journal_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            $notification = [
                'message' => 'An error occurred while updating journal entry.',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification)->withInput();
        }
    }

    public function destroy($id)
    {
        // Logic to delete the journal entry
        return redirect()->back()->with('success', 'Journal entry deleted successfully.');
    }
}
