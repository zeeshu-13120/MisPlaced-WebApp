    @extends('AdminViews.Layout.layout')
    @section('title','Build Form')
    @section('style')
    <style>
    .btn-default{
        background-color:rgba(0, 0, 0, 0.493) !important;
    }
    .form-builder-dialog{
        z-index: 99999 !important;
    }
    .form-builder-overlay{
        z-index: 999 !important;
    }
    </style>

    @endsection

    @section('content')


    <main id="main" class="main">


        <section class="section dashboard">
        <div class="row bg-white shadow rounded-3">

            <div class="col-12 my-4 d-flex flex-wrap">
                <div class="col-12 mb-4">
                    <label for="">Form Title</label>
                    <input value="{{ $formData ? $formData->title : '' }}" type="text" class="form-control" id="formTitle">

                    <input  hidden type="text" class="form-control" value="{{ $formData ? $formData->id : '' }}"
                    id="formId">
                </div>
    <div class="col-md-6">
        <label for="">Category</label>
        <select class="col-11 mx-auto" name="category_id" id="mainCategorySelect">
            <option value="">Select Main Category</option>
            @foreach($mainCategories as $category)
            <option {{ !empty($subcategory) && $subcategory->parent_id==$category->id?"selected":""}}  value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    {{-- {{print_r($selectedcategory)}} --}}
    <div class="col-md-6">
        <label for="">Sub Category</label>
        <select class="col-11 mx-auto" name="subcategory_id" id="subCategorySelect">
            <option value="">Select Subcategory</option>
           @if(!empty($selectedcategory))
            @foreach($selectedcategory as $category)

            @if (!empty($subcategory) &&$subcategory->id == $category->id)
                <option selected value="{{ $category->id }}">{{ $category->name }}</option>
                @break
            @endif
            @endforeach
            @endif
        </select>
    </div>

    <div class="col-12 my-4">
        <label class="col-12" value="">Use Existing Form as Template</label>
        <select class="col-12 mx-auto" name="template" id="loadTemplate">
            <option value="" disabled selected>Select Forms</option>
            @foreach($forms as $form)
            <option value="{{ $form->formdata }}">{{ $form->title }}</option>
            @endforeach
        </select>
    </div>
            </div>







            <div class="build-wrap"></div>

        </div>
        </section>

    </main>
    @endsection

    @section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{asset('admin/js/form-builder.min.js')}}" integrity="sha512-xIZ+JfoiUE8a3iXKQxuJg1kMeHdq+n82p46PcYJ+nrUUnAsdSNNE6Eq+omaGcvJEKTuTC48cymPg7wDUuA7fWQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var encodedFormData = '{{$formData ? $formData->formdata : ""}}'; // Assuming this contains your JSON string with HTML entities
var formDataString = decodeURIComponent(encodedFormData.replace(/&quot;/g, '"'));
var defaultFields = formDataString ? JSON.parse(formDataString) : [];

        var options = {
            disabledAttrs: ["access"],
            defaultFields:defaultFields,
                  onSave: function(evt, formData){
        saveForm(evt,formData);
      }
    };

var formBuilder="";
        jQuery($ => {
            formBuilder= $('.build-wrap').formBuilder(options);
        });


        $('#loadTemplate').on('change',function(){

formBuilder.actions.setData($('#loadTemplate').val());
});
    });




    function saveForm(evt,formData) {

        var categoryId = $('#mainCategorySelect').val();
        var subcategoryId = $('#subCategorySelect').val();
        var formTitle = $('#formTitle').val();
        var formId = $('#formId').val();




        // Now you have formData object containing form data along with category and subcategory IDs

        // Send formData to the server using AJAX
        $.ajax({
            url: '/admin/build_form',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                subcategory_id:subcategoryId,
                form_title:formTitle,
                formId:formId,
                form_data: formData
            },
            success: function(response) {
                console.log('Form data saved successfully:', response);
                toastr.success('Form Saved.');
            },
            error: function(xhr, status, error) {
    if (xhr.status === 422) {
        var errors = xhr.responseJSON.errors;

        // Loop through the validation errors and show them using toast.error
        for (var key in errors) {
            if (errors.hasOwnProperty(key)) {
                var errorMessage = errors[key][0]; // Assuming you want to show the first error message for each field
                toastr.error(errorMessage);
            }
        }
    } else {
        // Handle other types of errors (e.g., 500 Internal Server Error)
        console.error('Error saving form data:', error);
        toastr.error('An error occurred while saving the form data.');
    }
}

        });
    }


    $(document).ready(function() {
        $('#mainCategorySelect').change(function() {
            var categoryId = $(this).val();
            $('#subCategorySelect').empty();

            $.ajax({
                url: '/admin/get-subcategories/' + categoryId,
                method: 'GET',
                success: function(response) {

                    // Recursive function to populate subcategories
                    function populateSubcategories(subcategories, level) {
                        $.each(subcategories, function(index, subcategory) {
                            var indentation = Array(level + 1).join('&nbsp;&nbsp;&nbsp;');
                            $('#subCategorySelect').append('<option value="' + subcategory.id + '">' + indentation + subcategory.name + '</option>');
                            if (subcategory.subcategories && subcategory.subcategories.length > 0) {
                                // If subcategories exist, call the function recursively
                                populateSubcategories(subcategory.subcategories, level + 1);
                            }
                        });
                    }

                    // Start populating subcategories recursively
                    populateSubcategories(response, 0);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + ' - ' + error);
                    // Handle the error (show a message, log it, etc.)
                },
                complete: function(xhr, status) {
                    console.log('AJAX Request completed with status: ' + status);
                    // This block will run regardless of whether the request was successful or not
                }
            });
        });
    });





    </script>

    @endsection