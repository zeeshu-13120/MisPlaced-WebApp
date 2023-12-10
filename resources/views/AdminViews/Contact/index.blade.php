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
            <h5 class="card-title col">All Messages</h5>
            <div class="col text-end">

            </div>
            </div>
          <!-- Default Table -->
          <table id="category_table" class="mt-3 datatable table table-striped responsive">
              <thead>
                  <tr>
                      <th>Name</th>
                      <th>Email</th>

                      <th>Subject</th>
                      <th>Date</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($contacts as $contact)




<tr>
    <td>
        {{ ucfirst($contact->name) }}

    </td>
    <td>
        {{$contact->email}}
    </td>
    <td>
        {{$contact->subject}}
    </td>

    <td>

        {{ $contact->created_at }}
    </td>

    <td>
        <button type="button" data-contact='@json($contact)' onclick="viewMessage(this)" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewMessageModal">
            View
        </button>
        <button type="button" data-id="{{ $contact->id }}"  onclick="deletePost(this)" class="btn btn-sm btn-danger">Delete</button>

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
<!-- Modal -->
<div class="modal fade" id="viewMessageModal" tabindex="-1" aria-labelledby="viewMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewMessageModalLabel">View Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewMessageModalBody">
                <!-- Message details will be loaded here -->
            </div>
        </div>
    </div>
</div>

  </main>
@endsection

@section('script')

<script>
    $(document).ready(function() {

           $('.datatable').DataTable({
             "pageLength": 20
           });
         });
// Define viewMessage in the global scope
window.viewMessage = function(btn) {
            var contactData = JSON.parse(btn.dataset.contact);

            // Update the modal body with the contact details
            document.getElementById('viewMessageModalBody').innerHTML =
                '<p><strong>Name:</strong> ' + contactData.name + '</p>' +
                '<p><strong>Email:</strong> ' + contactData.email + '</p>' +
                '<p><strong>Subject:</strong> ' + contactData.subject + '</p>' +
                '<p><strong>Message:</strong> ' + contactData.message + '</p>';
        };
        function deletePost(btn) {
    if (confirm("Are you sure you want to delete this message?")) {
        var id = btn.dataset.id;

        var csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/admin/delete_message/' + id, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrf_token
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                toastr.success(data.message);
                $(btn).closest('tr').fadeOut(500, function() {
                    $(this).remove();
                });
            } else {
                toastr.error(data.error);
            }
        })
        .catch(error => {
            console.error(error);
            toastr.error("An error occurred while deleting the message.");
        });
    }
}

   </script>
@endsection