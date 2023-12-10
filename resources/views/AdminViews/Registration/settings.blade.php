@extends('AdminViews.Layout.layout')
@section('title','Account Settings')
@section('style')
<style>

</style>

@endsection

@section('content')

  <main id="main" class="main">

    <section class="section dashboard">
      <div class="row bg-white shadow rounded-3">

      <div class="col-md-10 m-auto">
            <div class="card-body  ">
               <h5 class="card-title w-100 text-center display-3">Account Settings</h5>

           <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $admin->name }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $admin->email }}" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ $admin->phone }}" required>
            </div>
            <div class="form-group my-3">
                <label for="profile_photo">Profile Photo</label>
                <input type="file" name="profile_photo" id="profile_photo" class="form-control-file">
            </div>
            <div class="form-group">
                <button type="submit" class="btn my-2 btn-primary">Update Account</button>
            </div>
        </form>
        <hr>
<h3 class="my-3">Update Password</h3>
<form method="POST" action="{{ route('admin.settings.update.password') }}">
    @csrf
    <div class="form-group">
        <label for="current_password">Current Password</label>
        <input type="password" name="current_password" id="current_password" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="new_password">New Password</label>
        <input type="password" name="password" id="new_password" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="new_password_confirmation">Confirm New Password</label>
        <input type="password" name="password_confirmation" id="new_password_confirmation" class="form-control" required>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary my-3">Update Password</button>
    </div>
</form>

            </div>
         </div>
      </div>
    </section>

  </main>
@endsection

@section('script')

<script>

</script>
@endsection
