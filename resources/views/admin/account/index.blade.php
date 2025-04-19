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
                                <h5 class="mb-0 card-title">Chart of Accounts List</h5>
                            </div>
                            <div class="pt-6 dt-action-buttons text-end pt-md-0">
                                <div class="btn-group">
                                </div>
                                @if (Auth::user()->can('accounting.add'))
                                    <button type="button"
                                        class="btn btn-secondary create-new btn-primary waves-effect waves-light"
                                        data-bs-toggle="modal" data-bs-target="#addAccountModal">
                                        <span><i class="ti ti-plus me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Add Account</span>
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-datatable text-nowrap">
                            <table id="example" class="table datatables-ajax">
                                <thead>
                                    <tr>
                                        <th class="text-center align-content-center text-primary">No</th>
                                        <th class="text-center align-content-center text-primary">Code</th>
                                        <th class="text-center align-content-center text-primary">Name</th>
                                        <th class="text-center align-content-center text-primary">Type</th>
                                        <th class="text-center align-content-center text-primary">Categoty</th>
                                        <th class="text-center align-content-center text-primary">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @foreach ($accounts as $index => $account)
                                        <tr>
                                            <td class="text-center align-content-center">{{ $index + 1 }}</td>
                                            <td class="text-center align-content-center text-uppercase">
                                                {{ $account->code }}</td>
                                            <td class="text-center align-content-center text-uppercase">
                                                {{ $account->name }}</td>
                                            <td class="text-center align-content-center text-uppercase">
                                                {{ $account->type }}</td>
                                            <td class="text-center align-content-center text-uppercase">
                                                {{ $account->category }}</td>
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
                                                                    data-bs-target="#accountEdit"
                                                                    data-id="{{ $account->id }}"
                                                                    data-code="{{ $account->code }}"
                                                                    data-name="{{ $account->name }}"
                                                                    data-type="{{ $account->type }}"
                                                                    data-category="{{ $account->category }}">
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
    <!--/ Content End -->

    <!-- Add Account Modal -->
    <div class="modal fade" id="addAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="mb-6 text-center">
                        <h4 class="mb-2">Add New Account</h4>
                    </div>
                    <form id="addAccountForm" method="POST" action="{{ route('account.store') }}">
                        @csrf
                        <div class="row">
                            <div class="mb-4 col-12 col-md-6">
                                <label class="form-label" for="modalAccountCode">Code</label>
                                <input type="text" id="modalAccountCode" name="AccountCode" class="form-control"
                                    placeholder="Account Code" required />
                            </div>
                            <div class="mb-4 col-12 col-md-6">
                                <label class="form-label" for="modalAccountName">Name</label>
                                <input type="text" id="modalAccountName" name="AccountName" class="form-control"
                                    placeholder="Account Name" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-12 col-md-6">
                                <label class="form-label" for="modalAccountType">Type</label>
                                <input type="text" id="modalAccountType" name="AccountType" class="form-control"
                                    placeholder="Account Type" required />
                            </div>
                            <div class="mb-4 col-12 col-md-6">
                                <label class="form-label" for="modalAccountCategory">Category</label>
                                <input type="text" id="modalAccountCategory" name="AccountCategory" class="form-control"
                                    placeholder="Account Category" required />
                            </div>
                        </div>
                        <div class="text-center col-12 demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-4">Create Account</button>
                            <button type="button" class="btn btn-label-secondary"
                                data-bs-dismiss="modal">Discard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--/ Add Account Modal -->


    <!-- Edit Account Modal -->
    <div class="modal fade" id="accountEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="mb-6 text-center">
                        <h4 class="mb-2">Edit Account</h4>
                    </div>
                    <form id="accountEditForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="mb-4 col-12 col-md-6">
                                <label class="form-label" for="modalAccountCode">Code</label>
                                <input type="text" id="modalAccountCode" name="AccountCode" class="form-control"
                                    placeholder="Account Code" required />
                            </div>
                            <div class="mb-4 col-12 col-md-6">
                                <label class="form-label" for="modalAccountName">Name</label>
                                <input type="text" id="modalAccountName" name="AccountName" class="form-control"
                                    placeholder="Account Name" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-12 col-md-6">
                                <label class="form-label" for="modalAccountType">Type</label>
                                <input type="text" id="modalAccountType" name="AccountType" class="form-control"
                                    placeholder="Account Type" required />
                            </div>
                            <div class="mb-4 col-12 col-md-6">
                                <label class="form-label" for="modalAccountCategory">Category</label>
                                <input type="text" id="modalAccountCategory" name="AccountCategory"
                                    class="form-control" placeholder="Account Category" required />
                            </div>
                        </div>
                        @if (Auth::user()->can('accounting.edit'))
                            <div class="gap-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!--/ Edit Account Modal -->


    <script>
        const accountEdit = document.getElementById('accountEdit');
        accountEdit.addEventListener('show.bs.modal', event => {
            // Button that triggered the modal
            const button = event.relatedTarget;

            // Extract information from data-* attributes
            const accountId = button.getAttribute('data-id');
            const accountCode = button.getAttribute('data-code');
            const accountName = button.getAttribute('data-name');
            const accountType = button.getAttribute('data-type');
            const accountCategory = button.getAttribute('data-category');

            // Update modal input fields
            const modalAccountCodeInput = accountEdit.querySelector('#modalAccountCode');
            const modalAccountNameInput = accountEdit.querySelector('#modalAccountName');
            const modalAccountTypeInput = accountEdit.querySelector('#modalAccountType');
            const modalAccountCategoryInput = accountEdit.querySelector('#modalAccountCategory');

            // Set input values
            modalAccountCodeInput.value = accountCode;
            modalAccountNameInput.value = accountName;
            modalAccountTypeInput.value = accountType;
            modalAccountCategoryInput.value = accountCategory;

            // Set the form action dynamically
            const form = accountEdit.querySelector('#accountEditForm');
            form.action = `/update/accounts/${accountId}`;
        });


        document.addEventListener('DOMContentLoaded', function() {
            // Tangkap tombol yang memicu modal
            const addAccountButton = document.querySelector('[data-bs-target="#addAccountModal"]');

            addAccountButton.addEventListener('click', function() {
                // Reset form modal setiap kali tombol di-klik
                const form = document.getElementById('addAccountForm');
                form.reset();
            });
        });
    </script>
@endsection
