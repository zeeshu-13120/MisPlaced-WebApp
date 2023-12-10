@extends('AdminViews.Layout.layout')
@section('title','Dashboard')
@section('style')
<style>

</style>

@endsection

@section('content')


  <main id="main" class="main">


    <section class="section dashboard">
        <div class="row bg-white shadow rounded-3">


            <div class="card-body">
              <div class="row m-3 ">
            <h5 class="card-title col">Forms List</h5>
            <div class="col text-end">

            <a href="{{ route('form.add') }}" class=" btn btn-primary">Build New Form</a>
            </div>
            </div>
          <!-- Default Table -->
          <table id="category_table" class="mt-3 datatable table table-striped responsive">
              <thead>
                  <tr>
                      <th>ID</th>
                      <th>Form</th>
                      <th>Category</th>
                      <th>Date</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($forms as $form)

                  <tr>
                    <td>{{ $form->id }}</td>
                    <td>

                        {{ $form->title }}
                    </td>
                    <td>

                        {{ $form->category->name }}
                    </td>
                    <td>

                        {{ $form->created_at }}
                    </td>
                    <td>

                        <a href="{{ route('form.add', $form->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <button type="button" data-id="{{ $form->id }}" onclick="deleteForm(this)" class="btn btn-sm btn-danger">Delete</button>

                    </td>
                </tr>
                  @endforeach
              </tbody>
          <!-- End Default Table Example -->
          </table>

      </div>
      </div>
      </div>
    </section>

  </main>
@endsection

@section('script')

<script>
    $(document).ready(function() {
           $('.datatable').DataTable({
             "pageLength": 20
           });
         });

         function deleteForm(btn) {
      if (confirm("Are you sure you want to delete this form?")) {
          var id = btn.dataset.id;

          var csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

          fetch('/admin/delete_form/' + id, {
              method: 'GET',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-Token': csrf_token
              },
          })
          .then(response => response.json())
          .then(data => {
            if(data.message){
              toastr.success(data.message);
              $(btn).closest('tr').fadeOut(500, function(){
                  $(this).remove();
              });}
              else{
                toastr.error(data.error);

              }

          })
          .catch(error => {
              console.error(error);
              toastr.error("An error occurred while deleting the form."); // Display an animated error notification
          });
      }
  }
   </script>
@endsection