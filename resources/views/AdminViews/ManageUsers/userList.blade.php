@extends('AdminViews.Layout.layout')
@section('title','Users List')
@section('style')
<style>
.status-dot {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  vertical-align: middle;
}

</style>

@endsection

@section('content')
  <main id="main" class="main">
    <section class="section dashboard">
      <div class="row bg-white shadow rounded-3">
        <div class="card-body">
            <h5 class="card-title">Users List</h5>
            <!-- Data Table -->
            <div class="table-responsive">
              <table id="users-table" class="datatable table table-striped responsive">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Profile</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {{-- @foreach ($users as $user)
                  <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td> </td>
                    <td>{{ $user->first_name." ".$user->last_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ \Carbon\Carbon::parse($user->last_login)->diffForHumans() }}</td>

                    <td>

                    </td>


                  </tr>
                @endforeach --}}

                </tbody>
              </table>
            </div>
            <!-- End Data Table Example -->
          </div>
      </div>
    </section>
  </main>
<!-- User Details Modal -->
<div class=" modal fade " id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog row" role="document">
    <div class="modal-content " >
      <div class="modal-header">
        <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
        <button type="button" class="close btn text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" style="font-size: 20px">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-2 col-md-3 col-4">
            <img id="user-photo" src="" width="100%">
          </div>
          <div class="col-8">
            <p><strong>ID:</strong> <span id="user-id"></span></p>
            <p><strong>Name:</strong> <span id="user-name"></span></p>
            <p><strong>Email:</strong> <span id="user-email"></span></p>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-6">
            <p><strong>Account Status:</strong> <span id="user-account-status"></span></p>
            <p><strong>Phone:</strong> <span id="user-phone"></span></p>
            <p><strong>Address:</strong> <span id="user-address"></span></p>
            <p><strong>City:</strong> <span id="user-city"></span></p>
            <p><strong>Zip Code:</strong> <span id="user-zip_code"></span></p>
          </div>
          <div class="col-md-6">
            <p><strong>Last Login:</strong> <span id="user-last-login"></span></p>
            <p><strong>Email Verified At:</strong> <span id="user-email_verified_at"></span></p>
            <p><strong>Google ID:</strong> <span id="user-google_id"></span></p>
            <p><strong>Created At:</strong> <span id="user-created_at"></span></p>
            <p><strong>Updated At:</strong> <span id="user-updated_at"></span></p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<!-- User Edit Modal -->

<div class="modal  fade" id="userEditModal" tabindex="-1" role="dialog" aria-labelledby="userEditModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userEditModalLabel">Edit User</h5>
        <button type="button" class="close btn text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" style="font-size: 20px">&times;</span>
        </button>
      </div>
      <form id="user-edit-form" action="" method="POST" enctype='multipart/form-data'
      >
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="row"> <!-- First row -->
            <div class="col-md-6">
              <div class="mb-3">

                <img id="edit-profile" src="" width="100">
                <input class="w-50" type="file" class="form-control" id="edit-photo" name="photo" >

              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit-first-name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="edit-first-name" name="first_name" required>
              </div>
            </div>
          </div>
          <div class="row"> <!-- Second row -->
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit-last-name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="edit-last-name" name="last_name" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit-email" class="form-label">Email</label>
                <input type="email" class="form-control" id="edit-email" name="email" required>
              </div>
            </div>
          </div>
          <div class="row"> <!-- Third row -->
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit-phone" class="form-label">Phone</label>
                <input type="tel" class="form-control" id="edit-phone" name="phone" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit-address" class="form-label">Address</label>
                <input type="text" class="form-control" id="edit-address" name="address">
              </div>
            </div>
          </div>
          <div class="row"> <!-- Fourth row -->
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit-city" class="form-label">City</label>
                <input type="text" class="form-control" id="edit-city" name="city">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit-zip_code" class="form-label">Zip Code</label>
                <input type="text" class="form-control" id="edit-zip_code" name="zip_code">
              </div>
            </div>
          </div>

          <div class="row"> <!-- Fifth row -->
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit-account-status" class="form-label">Account Status</label>
                <select class="form-control" id="edit-account-status" name="banned">
                  <option value="1">Ban Account</option>
                  <option value="0">Unban Account</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <label for="reset-pass" class="form-label">Password Reset Link</label>

              <button class="btn btn-success col-12" id="send-password-reset-link">Send Link</button></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>


    </div>
  </div>
</div>

@endsection





@section('script')
<script>

$(document).on('click', '.show-user-details', function() {
    var userId = $(this).data('id');
console.log("clicked");
$.ajax({
  url: '/admin/get_single_user_data/' + userId,
  method: 'GET',
  dataType: 'json',
  success: function(response) {
    showUserDetails(response);
  }
});
});

$(document).on('click', '.show-user-edit-modal', function() {
  var userId = $(this).data('id');
  console.log("clicked");

    $.ajax({
        url: '/admin/get_single_user_data/'+userId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
          console.log(response);
            showUserEditModal(response);
        },
        error: function (request, status, error) {
        alert(request.responseText);
    }
    });
});

$('#users-table').DataTable({
  responsive: true,
  dom: '<"text-center" B>lfrtip',
    buttons: [
        'copyHtml5',
        'csvHtml5',
        'excelHtml5',
        'pdfHtml5'
    ],
    language: {
        searchPlaceholder: "Search...",
        lengthMenu: "Show _MENU_ entries per page",
        zeroRecords: "No entries found",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        infoEmpty: "Showing 0 to 0 of 0 entries",
        infoFiltered: "(filtered from _MAX_ total entries)"
    },
    serverSide: true,
    processing: true,
    ajax: {
        url: '/admin/get_users_list',
        data: function(d) {
            d.page = Math.ceil(d.start / d.length) + 1;

        }


    },
    columns: [
        { data: 'id' },
        { data: 'photo' },
        { data: 'name' },
        { data: 'email' },
        { data: 'phone' },
        { data: 'status' },
        { data: 'action' }
    ]
});



function ReadableTime(timestamp) {
  const date = new Date(timestamp);
  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  };
  return date.toLocaleString("en-US", options);
}
function showUserDetails(user) {

  var accountStatus="<span class='text-success'>Active</span>";
    if(user.banned==true){
       accountStatus="<span class='text-danger'>Banned</span>";
    }
    if(user.email_verified_at==null){
       accountStatus="<span class='text-warning'>Not Verified</span>";
    }

    document.getElementById('user-id').innerText = user.id;
    document.getElementById('user-photo').src = user.photo;
    document.getElementById('user-name').innerText = user.first_name + ' ' + user.last_name;
    document.getElementById('user-email').innerText = user.email;
    document.getElementById('user-account-status').innerHTML = accountStatus;
    document.getElementById('user-phone').innerText = user.phone;
    document.getElementById('user-address').innerText = user.address;
    document.getElementById('user-city').innerText = user.city;
    document.getElementById('user-zip_code').innerText = user.zip_code;
    document.getElementById('user-email_verified_at').innerText = ReadableTime(user.email_verified_at);
    document.getElementById('user-google_id').innerText = user.google_id;
    document.getElementById('user-created_at').innerText = ReadableTime(user.created_at);
    document.getElementById('user-updated_at').innerText = ReadableTime(user.updated_at);

    $('#userDetailsModal').modal('show');
  }

  function showUserEditModal(user) {
  // Populate input fields with user data
  document.getElementById('edit-profile').src = user.photo;
  document.getElementById('edit-first-name').value = user.first_name;
  document.getElementById('edit-last-name').value = user.last_name;
  document.getElementById('edit-email').value = user.email;
  document.getElementById('edit-phone').value = user.phone;
  document.getElementById('edit-address').value = user.address;
  document.getElementById('edit-city').value = user.city;
  document.getElementById('edit-zip_code').value = user.zip_code;
  document.getElementById('edit-account-status').value = user.account_status;


  // Update the form action with the user ID
  document.getElementById('user-edit-form').action = '/admin/update_user/' + user.id;

  // Show the modal
  $('#userEditModal').modal('show');
}
document.getElementById('edit-account-status').addEventListener('change', function() {
  const status = this.value;
  const message = status === '0' ? 'Are you sure you want to ban this account?' : 'Are you sure you want to unban this account?';

  if (!confirm(message)) {
    this.value = status === '0' ? '1' : '0'; // Revert to the previous value if not confirmed
  }
});


/// Add event listener to the "Send Link" button
$('#send-password-reset-link').on('click', function(event) {
  // Prevent default form submission
  event.preventDefault();

  // Ask for user confirmation
  if (confirm('Are you sure you want to send a password reset link to this user?')) {
    // Get the user ID from the form action URL
    var userID = $('#user-edit-form').attr('action').split('/').pop();

    // Get the email input value
    var email = $('input[name="email"]').val();

    // Get the CSRF token from the meta tag
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send an AJAX request to the server to trigger the password reset email
    $.ajax({
      url: '/admin/reset_user_password/' + userID,
      type: 'POST',
      data: { email: email }, // Include the email in the request data
      beforeSend: function(xhr) {
        if (csrfToken) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        }
      },
      success: function(data) {
        console.log(data);
        alert('Password reset link sent successfully.');
      },
      error: function(data) {
        console.log(data);
        alert('Error sending password reset link. Please try again later.');
      }
    });
  }
});

</script>
@endsection

