<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DRL-Photo || Employee Registration Form</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --premium-blue: #1a73e8;
            --premium-blue-hover: #1557b0;
            --background-light-blue: #ebf3ff;
            --text-gray: #6c757d;
            --border-blue: #a3c9f7;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f9fc;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px 0;
        }

        .form-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 30px;
            border: none;
            width: 100%;
        }

        .form-logo-container {
            text-align: center;
            margin-bottom: 15px;
        }
        .form-logo-container img {
            max-height: 60px;
            object-fit: contain;
        }

        .form-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ced4da;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.15);
            border-color: var(--border-blue);
        }

        .upload-photo-label {
            text-transform: uppercase;
            font-size: 14px;
            color: var(--text-gray);
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .photo-upload-container {
            border: 2px dashed var(--border-blue);
            background-color: var(--background-light-blue);
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.2s, border-color 0.2s;
            position: relative;
            overflow: hidden;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .photo-upload-container:hover {
            background-color: #dbeaff;
            border-color: var(--premium-blue);
        }

        .photo-upload-container.is-invalid-box {
            border-color: #dc3545;
            background-color: #f8d7da;
        }

        .upload-icon {
            font-size: 40px;
            color: var(--premium-blue);
            margin-bottom: 15px;
            display: block;
        }

        .is-invalid-box .upload-icon, .is-invalid-box .main-upload-text {
            color: #dc3545;
        }

        .main-upload-text {
            color: var(--premium-blue);
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 16px;
        }

        .supports-text {
            color: var(--text-gray);
            font-size: 13px;
            margin-bottom: 0;
        }

        .photo-preview-image {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            display: none; /* Hidden by default */
            object-fit: contain;
            padding: 10px;
            background-color: #ffffff; /* White bg behind photo */
        }

        .file-name-display {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            font-size: 13px;
            padding: 6px 10px;
            text-align: center;
            display: none; /* Hidden by default */
            z-index: 5;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn-discard-photo {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: background 0.2s, transform 0.2s;
        }

        .btn-discard-photo:hover {
            background-color: #c82333;
            transform: scale(1.1);
        }

        .hide-on-preview {
            display: block;
        }

        .photo-upload-container.has-photo .hide-on-preview {
            display: none;
        }
        .photo-upload-container.has-photo .photo-preview-image {
            display: block;
        }
        .photo-upload-container.has-photo .file-name-display {
            display: block;
        }
        .photo-upload-container.has-photo .btn-discard-photo {
            display: flex;
        }


        .btn-submit {
            background-color: var(--premium-blue);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            letter-spacing: 0.5px;
            color: white;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: var(--premium-blue-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 115, 232, 0.3);
        }

        label.error {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.4rem;
            display: block;
            text-align: center;
        }
        .form-control.is-invalid + label.error, .form-select.is-invalid + label.error {
            text-align: left;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="card form-card">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <div class="form-logo-container">
                    <img src="{{asset('DrR_Logo_Secondary_RGB.png')}}" alt="Company Logo">
                </div>


                <form id="employeeForm" method="POST" action="{{ route('doctor.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3 position-relative">
                        <label for="empName" class="form-label"> Name</label>
                        <input type="text" class="form-control" id="empName" name="name" placeholder="Enter employee name">
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="empId" class="form-label"> ID</label>
                        <input type="text" class="form-control" id="empId" name="emp_id" placeholder="Enter Emp Id"        oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                    </div>

                    <div class="mb-3 position-relative">
                        <label for="empHQ" class="form-label">HQ </label>
                        <input type="text" class="form-control" id="empHQ" name="hq" placeholder="Enter Headquarters">
                    </div>

                    <div class="mb-4 position-relative">
                        <label class="upload-photo-label">UPLOAD PHOTO <span class="text-danger">*</span></label>

                        <label for="empPhoto" class="photo-upload-container" id="previewContainer">
                            <i class="fas fa-cloud-upload-alt upload-icon hide-on-preview"></i>
                            <p class="main-upload-text hide-on-preview">Tap to Upload Photo</p>
                            <p class="supports-text hide-on-preview">Supports: JPG, PNG</p>

                            <img id="photoPreview" class="photo-preview-image" src="" alt="Employee Photo">
                            <div class="file-name-display" id="fileNameDisplay">filename.jpg</div>

                            <button type="button" class="btn-discard-photo" id="discardPhotoBtn" title="Remove Photo">
                                <i class="fas fa-times"></i>
                            </button>
                        </label>

                        <input class="d-none" type="file" id="empPhoto" name="photo" accept="image/jpeg, image/png" required>
                        <div class="form-text text-center mt-2">Max file size: 2MB.</div>
                    </div>

                    <button type="submit" class="btn-submit w-100">Submit Details</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>

<script>
    $(document).ready(function() {
        const $empPhotoInput = $('#empPhoto');
        const $previewContainer = $('#previewContainer');
        const $previewImage = $('#photoPreview');
        const $fileNameDisplay = $('#fileNameDisplay');

        $empPhotoInput.on('change', function() {
            const file = this.files[0];

            if (file) {
                let reader = new FileReader();

                reader.onload = function(event) {
                    $previewImage.attr('src', event.target.result);
                    $fileNameDisplay.text(file.name); // Set actual file name
                    $previewContainer.addClass('has-photo');

                    $previewContainer.removeClass('is-invalid-box');
                    $empPhotoInput.removeClass('is-invalid').addClass('is-valid');
                    $('#empPhoto-error').hide();
                }
                reader.readAsDataURL(file);
            } else {
                resetPhotoUploader();
            }
        });

        $('#discardPhotoBtn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            resetPhotoUploader();

            $('#employeeForm').validate().element("#empPhoto");
        });

        function resetPhotoUploader() {
            $empPhotoInput.val('');
            $previewContainer.removeClass('has-photo');
            $previewImage.attr('src', '');
            $fileNameDisplay.text('');
            $empPhotoInput.removeClass('is-valid');
        }

        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param * 1024 * 1024);
        }, 'File size must be less than {0} MB');

        $('#employeeForm').validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                emp_id: {
                    required: true,
                    digits: true
                },
                hq: {
                    required: true,
                    minlength: 2
                },
                photo: {
                    required: true,
                    extension: "jpg|jpeg|png",
                    filesize: 2
                }
            },
            messages: {
                name: {
                    required: "Please enter employee name",
                    minlength: "Name must be at least 3 characters"
                },
                emp_id: {
                    required: "Please enter employee ID",
                    digits: "Only numbers allowed"
                },
                hq: {
                    required: "Please enter headquarters",
                    minlength: "HQ must be at least 2 characters"
                },
                photo: {
                    required: "Please upload a profile photo",
                    extension: "Only JPG or PNG allowed",
                    filesize: "Photo must be less than 2MB"
                }
            },


            errorElement: "label",
            errorPlacement: function (error, element) {
                error.addClass("error invalid-feedback d-block");

                if (element.attr("type") === "file") {
                    error.insertAfter($('#previewContainer'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass("is-invalid").removeClass("is-valid");

                if ($(element).attr("type") === "file") {
                    $('#previewContainer').addClass('is-invalid-box');
                }
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).addClass("is-valid").removeClass("is-invalid");

                if ($(element).attr("type") === "file") {
                    $('#previewContainer').removeClass('is-invalid-box');
                }
            },

            submitHandler: function(form) {
                let name = $('#empName').val();
                form.submit();
            }
        });
    });
</script>
</body>
</html>
