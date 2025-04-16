@extends('admin.admin_dashboard')
@section('admin')


<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <form id="mydata" action="{{ route('import.role') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="app-ecommerce">
            <!-- Add Destination -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Mass add a new Roles</h4>
                    <p class="mb-0">Upload file Excel Or CSV to Import & Export Roles</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <a href="{{ route('all.roles') }}"
                    class="btn btn-secondary buttons-collection btn-label-secondary me-4 waves-effect waves-light border-none"><span><i class="ti ti-caret-left ti-xs me-1"></i>
                        <span class="d-none d-sm-inline-block">Back</span></span></a>
                    <a href="{{ route('export.roles') }}"
                                    class="btn btn-secondary buttons-collection btn-label-danger me-4 waves-effect waves-light border-none"><span><i class="ti ti-file-export ti-xs me-1"></i>
                                        <span class="d-none d-sm-inline-block">Export</span></span></a>
                    <button type="submit" class="btn btn-primary">Publish</button>
                </div>
            </div>

            <div class="row">
                <!-- First column-->
                <div class="col-12 col-lg-12">
                    <!-- Product Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Role File</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="file">Upload File (CSV or Excel):</label>
                                    <input type="file" name="importFile" id="file" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Product Information -->
                </div>
            </div>
        </div>
    </form>
</div>
<!-- / Content -->

@endsection
