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
            <h5 class="card-title col">All Posts</h5>
            <div class="col text-end">

            </div>
            </div>
          <!-- Default Table -->
          <table id="category_table" class="mt-3 datatable table table-striped responsive">
              <thead>
                  <tr>
                      <th>Post type</th>
                      <th>Category</th>

                      <th>Posted By</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($forms as $form)

                  @foreach($form->childData as $data)



<tr>
    <td>
        {{ ucfirst($data->post_type) }}

    </td>
    <td>
        {{$data->category_name}}
    </td>
    <td>
        {{$data->first_name . " ". $data->last_name}}
    </td>

    <td>

        {{ $form->created_at }}
    </td>
    <td>
        {{ucfirst($data->status)}}
    </td>
    <td>

        <a href="{{url("/admin/view-post-admin/".$data->post_id."/".$form->table)}}" class="btn btn-sm btn-success">View</a>
        <button type="button" data-id="{{ $data->post_id }}" data-table="{{$form->table}}" onclick="deletePost(this)" class="btn btn-sm btn-danger">Delete</button>

    </td>
</tr>

@endforeach

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

         function deletePost(btn) {
      if (confirm("Are you sure you want to delete this post?")) {
          var id = btn.dataset.id;
          var table = btn.dataset.table;

          var csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

          fetch('/admin/delete_post/' + id +"/"+table, {
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
              toastr.error("An error occurred while deleting the post.");
          });
      }
  }
   </script>
@endsection