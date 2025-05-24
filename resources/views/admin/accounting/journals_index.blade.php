@extends('admin.admin_dashboard')
@section('admin')
    <!-- Content -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="card">
                <div class="pt-0 card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="card-header flex-column flex-md-row">
                            <div class="text-center head-label">
                                <h5 class="mb-0 card-title">Journal List</h5>
                            </div>
                        </div>
                        <div class="card-datatable text-nowrap">
                            <table id="example" class="table datatables-ajax">
                                <thead>
                                    <tr>
                                        <th class="text-center align-content-center text-primary">No</th>
                                        <th class="text-center align-content-center text-primary">Tanggal</th>
                                        <th class="text-center align-content-center text-primary">Description</th>
                                        <th class="text-center align-content-center text-primary">Type</th>
                                        <th class="text-center align-content-center text-primary">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @foreach ($journals as $journal)
                                        <tr>
                                            <td class="text-center align-content-center">{{ $loop->iteration }}</td>
                                            <td class="text-center align-content-center">{{ $journal->date }}</td>
                                            <td class="text-center align-content-center">{{ $journal->description }}</td>
                                            <td class="text-center align-content-center">{{ $journal->journal_type }}</td>
                                            <td class="text-center align-content-center">
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#journalEdit" data-id="{{ $journal->id }}"
                                                    data-date="{{ $journal->date }}"
                                                    data-desc="{{ $journal->description }}"><i class="ti ti-edit"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="journalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="mb-6 text-center">
                        <h4 class="mb-2">Edit Journal</h4>
                    </div>
                    <form id="journalEditForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="mb-4 col-12">
                                <label class="form-label" for="journalDate">Date</label>
                                <input type="date" id="journalDate" name="date" class="form-control" required
                                    value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-12">
                                <label class="form-label" for="journalDescription">Description</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i
                                            class="ti ti-message-dots"></i></span>
                                    <textarea id="journalDescription" class="form-control" name="description" placeholder="Isi Keterangan Journal" aria-label="Isi Keterangan Journal" aria-describedby="Isi Keterangan Journal"></textarea>
                                </div>
                            </div>
                        </div>
                        @if (Auth::check() && Auth::user()->can('accounting.edit'))
                            <div class="gap-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        const journalEdit = document.getElementById('journalEdit');
        journalEdit.addEventListener('show.bs.modal', event => {
            // Button that triggered the modal
            const button = event.relatedTarget;

            // Extract information from data-* attributes
            const journalId = button.getAttribute('data-id');
            const journalDate = button.getAttribute('data-date');
            const journalDesc = button.getAttribute('data-desc');

            // Update modal input fields
            const modalJournalDateInput = journalEdit.querySelector('#journalDate');
            const modalJournalDescInput = journalEdit.querySelector('#journalDescription'); // Perbaikan ID

            // Set input values
            modalJournalDateInput.value = journalDate;
            modalJournalDescInput.value = journalDesc;

            // Set the form action dynamically
            const form = journalEdit.querySelector('#journalEditForm');
            form.action = `/journal/${journalId}`; // Perbaikan action URL sesuai route
        });
    </script>
@endsection
