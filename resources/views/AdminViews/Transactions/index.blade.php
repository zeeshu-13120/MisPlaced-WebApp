@extends('AdminViews.Layout.layout')
@section('title', 'Transactions')
@section('style')
  <style>
    /* Add your custom styles here if needed */
  </style>
@endsection

@section('content')
  <main id="main" class="main">
    <section class="section dashboard">
      <div class="row bg-white shadow rounded-3">
        <div class="col-md-12 py-4">
          <h2>Transactions</h2>
          <table id="transaction-table" class="table">
            <thead>
              <tr>
                <th>Paid By.</th>
                <th>Email.</th>
                <th>Transaction ID</th>
                <th>Paid On</th>
                <th>Amount</th>

              </tr>
            </thead>
            <tbody>
              @foreach($transactions as $transaction)
                <tr>
                  <td>{{ $transaction->user->first_name. " ". $transaction->user->last_name}}</td>
                  <td>{{ $transaction->user->email}}</td>

                  <td>{{ $transaction['transaction_id'] }}</td>
                  <td>{{ $transaction['created_at'] }}</td>
                  <td>{{ $transaction['amount'] }}</td>

                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>
@endsection

@section('script')
<script>
$('#transaction-table').DataTable({
  responsive: true,
})
    </script>

@endsection
