@extends('admin.admin_dashboard')
@section('admin')


<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <form id="mydata" action="{{ route('update.package', $package->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="app-ecommerce">
            <!-- Add Product -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Edit Package Oneday</h4>
                    <p class="mb-0">To Generate a tour package Price</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <a href="{{ route('all.packages') }}">
                        <button type="button" class="btn btn-primary ml-2">Back</button>
                    </a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>

            <div class="row">
                <!-- First column-->
                <div class="col-12 col-lg-6">
                    <!-- Product Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Detail Package</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="name_package">Name Package</label>
                                    <input type="text" class="form-control" id="name_package" placeholder="Name Package"
                                        value="{{$package->name_package}}" name="NamePackage" aria-label="Name Package"
                                        required />
                                </div>
                                <div class="col">
                                    <label class="form-label" for="city_district">City / District</label>
                                    <select required id="city_district" name="cityOrDistrict_id"
                                        class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select City / District</option>
                                        @foreach($regencies as $regency)
                                        <option value="{{ $regency->id }}" {{ $package->regency_id == $regency->id ?
                                            'selected' : '' }}>{{ $regency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="status_package">Status</label>
                                    <select required id="status_package" name="statusPackage"
                                        class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select Status</option>
                                        <option value="1" {{ $package->status == 1 ? 'selected' : '' }}>ACTIVE</option>
                                        <option value="0" {{ $package->status == 0 ? 'selected' : '' }}>INACTIVE
                                        </option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label" for="name_agen">Agen</label>
                                    <select required id="name_agen" name="NameAgen" class="select2 form-select"
                                        data-allow-clear="true">
                                        <option value="">Select Agen</option>
                                        @foreach($agens as $agen)
                                        <option value="{{ $agen->id }}" {{ $package->agen_id == $agen->id ? 'selected' :
                                            '' }}>{{ $agen->username }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                             <!-- Full Editor -->
                            <div class="col-12">
                                <h5 class="card-title mb-1">Information</h5>
                                <div id="quill-editor" class="mb-3" style="height: 80px;"> </div>
                                <textarea rows="3" class="mb-3 d-none" name="information"
                                    id="quill-editor-area">{{ $package->information }}</textarea>
                            </div>
                            <!-- /Full Editor -->
                        </div>
                    </div>
                    <!-- /Product Information -->
                </div>
                <div class="col-12 col-lg-6">
                    <!-- Destination Card -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Destination & Facility</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-6">
                                <label class="form-label" for="destinations">Destination</label>
                                <select id="destinations" name="destinations[]" class="select2 form-select" multiple>
                                    @foreach ($destinations as $destination)
                                    <option value="{{ $destination->id }}" {{ in_array($destination->id,
                                        $selectedDestinations) ? 'selected' : '' }}>
                                        {{ $destination->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="facility">Facility</label>
                                <select id="facility" name="facilities[]" class="select2 form-select" multiple>
                                    @foreach ($facilities as $facility)
                                    <option value="{{ $facility->id }}" {{ in_array($facility->id,
                                        $selectedFacilities) ? 'selected' : '' }}>
                                        {{ $facility->name }} | {{ $facility->type }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                    <!-- /Destination Card -->
                </div>
            </div>
        </div>
    </form>
</div>
<!-- / Content -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
    // Inisialisasi Quill Editor
    var quill = new Quill("#quill-editor", {
        theme: "snow",
        placeholder: "Write something...",
        modules: {
            toolbar: [
                    [
                      {
                        font: []
                      },
                      {
                        size: []
                      }
                    ],
                    ['bold', 'italic', 'underline', 'strike'],
                    [
                      {
                        color: []
                      },
                      {
                        background: []
                      }
                    ],
                    [
                      {
                        script: 'super'
                      },
                      {
                        script: 'sub'
                      }
                    ],
                    [
                      {
                        header: '1'
                      },
                      {
                        header: '2'
                      },
                      'blockquote',
                      'code-block'
                    ],
                    [
                      {
                        list: 'ordered'
                      },
                      {
                        list: 'bullet'
                      },
                      {
                        indent: '-1'
                      },
                      {
                        indent: '+1'
                      }
                    ],
                    [{ direction: 'rtl' }],
                    ['link', 'image', 'video', 'formula'],
                    ['clean']
                  ],
        },
    });

    // Ambil data dari textarea dan masukkan ke Quill
    const quillEditorArea = document.getElementById("quill-editor-area");
    const existingContent = quillEditorArea.value.trim();
    quill.root.innerHTML = existingContent;

    // Sinkronkan kembali ke textarea sebelum submit
    quill.on("text-change", function () {
        quillEditorArea.value = quill.root.innerHTML;
    });
});
</script>
@endsection
