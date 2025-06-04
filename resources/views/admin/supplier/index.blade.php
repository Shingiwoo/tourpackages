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
                                <h5 class="mb-0 card-title">Supplier List</h5>
                            </div>
                            <div class="pt-6 text-center dt-action-buttons pt-md-0">
                                <div class="btn-group">
                                </div>
                                @if (Auth::user()->can('accounting.add'))
                                    <button type="button"
                                        class="btn btn-secondary create-new btn-primary waves-effect waves-light"
                                        data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                                        <span><i class="ti ti-plus me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Add Supplier</span>
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-datatable text-nowrap">
                            <div class="table-responsive">
                                <table id="example" class="table datatables-ajax">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-content-center text-primary">No</th>
                                            <th class="text-center align-content-center text-primary">Name</th>
                                            <th class="text-center align-content-center text-primary">Contact</th>
                                            <th class="text-center align-content-center text-primary">Bank</th>
                                            <th class="text-center align-content-center text-primary">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($suppliers as $index => $supplier)
                                            <tr>
                                                <td class="text-center align-content-center">{{ $index + 1 }}</td>
                                                <td class="text-center align-content-center text-uppercase">
                                                    <span style="16px" class="text-bold">{{ $supplier->name }}</span><br><span
                                                        style="font-size: 12px">{{ Str::limit($supplier->address, 30, '...') }}</span><br><span
                                                        style="font-size: 12px">{{ $supplier->phone }} |
                                                        {{ $supplier->email }}</span>
                                                </td>
                                                <td class="text-center align-content-center text-uppercase">
                                                    {{ $supplier->contact_person }}
                                                    <br> {{ $supplier->contact_phone }} <br>
                                                    {{ $supplier->contact_email }}
                                                </td>
                                                <td class="text-center align-content-center text-uppercase">
                                                    {{ $supplier->bank_name }}
                                                    <br> {{ $supplier->account_name }} <br>
                                                    {{ Str::limit($supplier->bank_account, 10, '...') }}
                                                </td>
                                                @if (Auth::user()->can('accounting.action'))
                                                    <td class="text-center align-content-center">
                                                        <div class="dropdown">
                                                            <button type="button" class="p-0 btn dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                @if (Auth::user()->can('accounting.add'))
                                                                    <a class="dropdown-item button" data-bs-toggle="modal"
                                                                        data-bs-target="#editSupplierModal"
                                                                        data-id="{{ $supplier->id }}"
                                                                        data-address="{{ $supplier->address }}"
                                                                        data-name="{{ $supplier->name }}"
                                                                        data-phone="{{ $supplier->phone }}"
                                                                        data-emailSupplier="{{ $supplier->email }}"
                                                                        data-accountName="{{ $supplier->account_name }}"
                                                                        data-accountNumber="{{ $supplier->bank_account }}"
                                                                        data-bankName="{{ $supplier->bank_name }}"
                                                                        data-contactPerson="{{ $supplier->contact_person }}"
                                                                        data-contactPhone="{{ $supplier->contact_phone }}"
                                                                        data-contactEmail="{{ $supplier->contact_email }}"
                                                                        data-notes="{{ $supplier->notes }}">
                                                                        <i class="ti ti-pencil me-1"></i> Edit
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                @endif
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
    </div>
    </div>
    <!--/ Content End -->


    <!-- Add Supplier Modal -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="mb-6 text-center">
                        <h4 class="mb-2">Add New Supplier</h4>
                    </div>
                    <form id="addSupplierForm" method="POST" action="{{ route('supplier.store') }}">
                        @csrf
                        <div class="row">
                            <div class="mb-4 col-12">
                                <label class="form-label" for="modalSupplierName">Name</label>
                                <input type="text" id="modalSupplierName" name="SupplierName" class="form-control"
                                    placeholder="Supplier Name" required />
                            </div>
                            <div class="mb-4 col-12 col-md-6">

                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-12 col-md-6">
                                <label class="form-label" for="modalSupplierPhone">Phone</label>
                                <input type="number" id="modalSupplierPhone" name="SupplierPhone" class="form-control"
                                    placeholder="08123331232" required />
                            </div>
                            <div class="mb-4 col-12 col-md-6">
                                <label class="form-label " for="modalSupplierEmail">Email</label>
                                <input type="email" id="modalSupplierEmail" name="SupplierEmail" class="form-control"
                                    placeholder="emailsupplier@gmail.com" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-12 col-md-4">
                                <label class="form-label" for="modalSupplierBank">Bank Name</label>
                                <select required id="modalSupplierBank" name="SupplierBank" class="select2 form-select">
                                    <option value="">-- Pilih Bank --</option>
                                    @php
                                        $banks = json_decode(file_get_contents(public_path('assets/json/bank.json')));
                                    @endphp
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->name }}">{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4 col-12 col-md-4">
                                <label class="form-label" for="modalSupplierAccountName">Account Name</label>
                                <input type="text" id="modalSupplierAccountName" name="SupplierAccountName"
                                    class="form-control" placeholder="Yudi Bagus" required />
                            </div>
                            <div class="mb-4 col-12 col-md-4">
                                <label class="form-label" for="modalSupplierNoRek">No Rekening</label>
                                <input type="number" id="modalSupplierNoRek" name="SupplierNoRek" class="form-control"
                                    placeholder="088834234" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-2 col-12">
                                <label class="form-label" for="modalSupplierAddress">Address</label>
                                <textarea class="form-control" id="modalSupplierAddress" name="SupplierAddress" rows="2" required></textarea>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="row">
                            <div class="mb-4 col-12 col-md-4">
                                <label class="form-label" for="supplierContactPerson">Contact Person</label>
                                <input type="text" id="supplierContactPerson" name="ContactPerson"
                                    class="form-control" placeholder="Tiara Iskandar" />
                            </div>
                            <div class="mb-4 col-12 col-md-4">
                                <label class="form-label" for="supplierContactPhone">Contact Phone</label>
                                <input type="number" id="supplierContactPhone" name="ContactPhone" class="form-control"
                                    placeholder="081232341233" />
                            </div>
                            <div class="mb-4 col-12 col-md-4">
                                <label class="form-label " for="supplierContactEmail">Contact Email</label>
                                <input type="email" id="supplierContactEmail" name="ContactEmail" class="form-control"
                                    placeholder="contactemail@gmail.com" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-2 col-12">
                                <label class="form-label" for="modalSupplierNote">Note</label>
                                <textarea class="form-control" id="modalSupplierNote" name="SupplierNote" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="mt-4 text-center col-12 demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-4">Create</button>
                            <button type="button" class="btn btn-label-secondary"
                                data-bs-dismiss="modal">Discard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/ Add Supplier Modal End-->

    <!-- Edit Supplier Modal -->
    <div class="modal fade" id="editSupplierModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                    <div class="mb-6 text-center">
                        <h4 class="mb-2">Update Supplier</h4>
                    </div>
                    <form id="editSupplierForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="mb-4 col-12">
                                <label class="form-label" for="modalSupplierName">Name</label>
                                <input type="text" id="modalSupplierName" name="SupplierName" class="form-control"
                                    required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-12 col-md-6">
                                <label class="form-label" for="modalSupplierPhone">Phone</label>
                                <input type="number" id="modalSupplierPhone" name="SupplierPhone" class="form-control"
                                    required />
                            </div>
                            <div class="mb-4 col-12 col-md-6">
                                <label class="form-label " for="modalSupplierEmail">Email</label>
                                <input type="email" id="modalSupplierEmail" name="SupplierEmail"
                                    class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-12 col-md-4">
                                <label class="form-label" for="modalSupplierBank">Bank Name</label>
                                <select required id="modalSupplierBank" name="SupplierBank" class="select2 form-select">
                                    <option value="">-- Pilih Bank --</option>
                                    @php
                                        $banks = json_decode(file_get_contents(public_path('assets/json/bank.json')));
                                    @endphp
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->name }}">{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4 col-12 col-md-4">
                                <label class="form-label" for="modalSupplierAccountName">Account Name</label>
                                <input type="text" id="modalSupplierAccountName" name="SupplierAccountName"
                                    class="form-control" required />
                            </div>
                            <div class="mb-4 col-12 col-md-4">
                                <label class="form-label" for="modalSupplierNoRek">No Rekening</label>
                                <input type="number" id="modalSupplierNoRek" name="SupplierNoRek" class="form-control"
                                    required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-2 col-12">
                                <label class="form-label" for="modalSupplierAddress">Address</label>
                                <textarea class="form-control" id="modalSupplierAddress" name="SupplierAddress" rows="2" required></textarea>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="row">
                            <div class="mb-4 col-12 col-md-4">
                                <label class="form-label" for="supplierContactPerson">Contact Person</label>
                                <input type="text" id="supplierContactPerson" name="ContactPerson"
                                    class="form-control" />
                            </div>
                            <div class="mb-4 col-12 col-md-4">
                                <label class="form-label" for="supplierContactPhone">Contact Phone</label>
                                <input type="number" id="supplierContactPhone" name="ContactPhone"
                                    class="form-control" />
                            </div>
                            <div class="mb-4 col-12 col-md-4">
                                <label class="form-label " for="supplierContactEmail">Contact Email</label>
                                <input type="email" id="supplierContactEmail" name="ContactEmail"
                                    class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-2 col-12">
                                <label class="form-label" for="modalSupplierNote">Note</label>
                                <textarea class="form-control" id="modalSupplierNote" name="SupplierNote" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="mt-4 text-center col-12 demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-4">Update</button>
                            <button type="button" class="btn btn-label-secondary"
                                data-bs-dismiss="modal">Discard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/ Edit Supplier Modal End-->

    <script>
        const supplierEdit = document.getElementById('editSupplierModal');
        supplierEdit.addEventListener('show.bs.modal', event => {
            // Button that triggered the modal
            const button = event.relatedTarget;

            // Extract information from data-* attributes
            const supplierId = button.getAttribute('data-id');
            const supplierName = button.getAttribute('data-name');
            const supplierPhone = button.getAttribute('data-phone');
            const supplierEmail = button.getAttribute('data-emailSupplier');
            const supplierAddress = button.getAttribute('data-address');
            const supplierAccountName = button.getAttribute('data-accountName');
            const supplierAccountNumber = button.getAttribute('data-accountNumber');
            const supplierBankName = button.getAttribute('data-bankName');
            const supplierContactPerson = button.getAttribute('data-contactPerson');
            const supplierContactPhone = button.getAttribute('data-contactPhone');
            const supplierContactEmail = button.getAttribute('data-contactEmail');
            const supplierNotes = button.getAttribute('data-notes');

            // Update modal input fields
            const inputSupplierName = supplierEdit.querySelector('#modalSupplierName');
            const inputSupplierPhone = supplierEdit.querySelector('#modalSupplierPhone');
            const inputSupplierEmail = supplierEdit.querySelector('#modalSupplierEmail');
            const inputSupplierBankName = supplierEdit.querySelector('#modalSupplierBank');
            const inputSupplierAccountName = supplierEdit.querySelector('#modalSupplierAccountName');
            const inputSupplierAccountNumber = supplierEdit.querySelector('#modalSupplierNoRek');
            const inputSupplierAddress = supplierEdit.querySelector('#modalSupplierAddress');
            const inputContactPerson = supplierEdit.querySelector('#supplierContactPerson');
            const inputContactPhone = supplierEdit.querySelector('#supplierContactPhone');
            const inputContactEmail = supplierEdit.querySelector('#supplierContactEmail');
            const inputSupplierNotes = supplierEdit.querySelector('#modalSupplierNote');

            // Set input values
            inputSupplierName.value = supplierName;
            inputSupplierPhone.value = supplierPhone;
            inputSupplierEmail.value = supplierEmail;
            inputSupplierAccountName.value = supplierAccountName;
            inputSupplierAccountNumber.value = supplierAccountNumber;
            inputSupplierAddress.value = supplierAddress;
            inputContactPerson.value = supplierContactPerson;
            inputContactPhone.value = supplierContactPhone;
            inputContactEmail.value = supplierContactEmail;
            inputSupplierNotes.value = supplierNotes;

            // Set the selected option for the bank name
            const bankSelect = supplierEdit.querySelector('#modalSupplierBank');
            const bankOptions = bankSelect.querySelectorAll('option');
            bankOptions.forEach(option => {
                option.selected = option.value === supplierBankName;
            });
            $(bankSelect).trigger('change');

            // Set the form action dynamically
            const form = supplierEdit.querySelector('#editSupplierForm');
            form.action = `/update/supplier/${supplierId}`;
        });
    </script>
@endsection
