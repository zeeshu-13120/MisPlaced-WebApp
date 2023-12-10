@extends('Layout.layout')
@section('title','Profile Settings')
@section('style')
<style>




.c-details span {
    font-weight: 300;
    font-size: 13px
}

.icon {
    width: 50px;
    height: 50px;
    background-color: #eee;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 39px
}

.badge {
    width: 60px;
    height: 25px;
    padding-bottom: 3px;
    border-radius: 5px;
    display: flex;
    justify-content: center;
    align-items: center
}

.progress {
    height: 10px;
    border-radius: 10px
}



.text1,.text3 {
    font-size: 14px;
    font-weight: 600
}

.text2 {
    color: #a5aec0
}

table {
  border: 1px solid #ccc;
  border-collapse: collapse;
  margin: 0;
  padding: 0;
  width: 100%;
  table-layout: fixed;
}

table caption {
  font-size: 1.5em;
  margin: .5em 0 .75em;
}

table tr {
  background-color: #f8f8f8;
  border: 1px solid #ddd;
  padding: .35em;
}

table th,
table td {
  padding: .625em;
  text-align: center;
}

table th {
  font-size: .85em;
  letter-spacing: .1em;
  text-transform: uppercase;
}

@media screen and (max-width: 600px) {
  table {
    border: 0;
  }

  table caption {
    font-size: 1.3em;
  }

  table thead {
    border: none;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }

  table tr {
    border-bottom: 3px solid #ddd;
    display: block;
    margin-bottom: .625em;
  }

  table td {
    border-bottom: 1px solid #ddd;
    display: block;
    font-size: .8em;
    text-align: right;
  }

  table td::before {

    content: attr(data-label);
    float: left;
    font-weight: bold;
    text-transform: uppercase;
  }

  table td:last-child {
    border-bottom: 0;
  }
}
    </style>
@endsection
@section('content')
   <div class="container py-4">
    <section class="row mx-auto  rounded-3 align-self-stretch px-4 py-4 mb-3" >

        @foreach($posts as $key=> $post)

@foreach($post as $index =>$row)
<div class="col-lg-4 col-md-6 col-12 my-3" data-post-id={{$row->id}} >
    <div class="card justify-content-between p-3 mb-2"  style="height:100%" data-table={{$row->table}} data-id={{$row->id}}>
        <div class="d-flex justify-content-between" style="height:100%">
            <div class="d-flex flex-row  align-items-start  ">
                <div class="icon"> <iconify-icon icon="{{$row->icon}}"></iconify-icon> </div>
                <div class="ms-2 c-details">
                    <h6 class="mb-0">You {{$row->post_type}}: {{$row->subcategory_name}} </h6> <span>{{ Carbon\Carbon::parse($row->created_at)->diffForHumans() }}</span>
                </div>
            </div>
            <div class="d-flex">

                <span class="mb-auto px-2 text-white small rounded-pill  {{$row->status=='pending'?'bg-danger':'bg-success'}}" > {{" ".$row->status." "}} </span>       <div class="dropdown">
                <iconify-icon icon="pepicons-pop:dots-y"  class="ms-2" height="24px" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    </iconify-icon>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li>
                      <a href={{url("/view-post/".$row->id."/".$row->table)}}>
                        <span
                        role="button"
                            class="dropdown-item text-muted"
                        >
                            <iconify-icon class="me-2" icon="carbon:view"></iconify-icon>View
                        </span>
                      </a>
                    </li>
                    <li>
                        <span
                        role="button"
                            class="dropdown-item text-danger"

                            onclick="deletePost(this)"
                            data-id="{{$row->id}}"
                            data-tablename="{{$row->table}}"
                        >
                            <iconify-icon class="me-2" icon="material-symbols:delete"></iconify-icon>Delete
                        </span>
                    </li>
                    <li>
                        <span
                        role="button"
                            class="dropdown-item text-success"

                            onclick="markAsRecovered(this)"
                            data-id="{{$row->id}}"
                            data-tablename="{{$row->table}}"
                        >
                            <iconify-icon class="me-2" icon="simple-line-icons:check"></iconify-icon> Mark as Recovered
                        </span>
                    </li>
                        </ul>
            </div></div>
        </div>
        <div class="mt-5">
            <h3 class="heading match-counter text-center"><div class="spinner-border text-primary" role="status">
                <span class="sr-only"></span>
              </div></h3>
            <div class="mt-5">
                <span class="same small"></span>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="mt-3"> <span class="text1"> <span class="text2"></span></span> </div>
                <div> <span class="text3"> <span class="text"></span></span> </div>
            </div>
        </div>

    </div>
</div>
@endforeach
        @endforeach
    </section>
   </div>

<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailsModalLabel">Top <span id="match-c"></span> Matches</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modalBody">
            <table>
                <thead>
                  <tr>
                    <th scope="col">Category</th>
                    <th scope="col">Post Date</th>
                    <th scope="col">Similarity</th>
                    <th scope="col">Location</th>
                    <th scope="col">Same Features</th>
                    <th scope="col">Contact</th>
                  </tr>
                </thead>
                <tbody>


                </tbody>
              </table>
</tr>
</table>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('script')
<script>

var resData=[];
    $(document).ready(function() {
        // Loop through each row and make an AJAX request
        $('.card').each(function(index, element) {
            var rowId = $(element).data('id'); // Get the row id from data-id attribute
            var tableName = $(element).data('table'); // Get the table name from data-table attribute
            $.ajax({
                type: 'GET',
                url: '/find-match/' + rowId + '/' + tableName, // Use the row id and table name in the URL
                success: function(response) {
                    var index = resData.push(response.data) - 1;
                    console.log(response);
                    $(element).find('.match-counter').html(response.data.length + ("<br> Match" + (response.data.length > 1 ? "es" : "") + " Found.<br>") + (response.data.length > 0 ? "<button data-matches="+index+" class='btn mt-2 view-details btn-primary rounded-pill'>Details</button>" : ""));
                    $(element).find('.progress-bar').css('width', response?.data[0]?.similarityScore +'%');
                    $(element).find('.same').html((response?.data[0]?.similarityScore || 0) + "% similarity with the best match.");
                  if(response?.data[0]?.matchedColumns>0){

                      $(element).find('.text1').html("<iconify-icon icon='simple-line-icons:check' class='text-success me-1 small'></iconify-icon>"+(response?.data[0]?.matchedColumns || 0)+" <span class='text2'>Same features</span>");
                      $(element).find('.text3').html("<iconify-icon icon='carbon:location-filled' class=' me-1 small'></iconify-icon> Location: "+(response?.data[0]?.locationStatus));
                    }
                    else{

                        $(element).find('.text2').html(`No common features found.`);
                  }
                },
                error: function(error) {
                    $(element).find('.match-counter').html("<span class='text-danger'>Error.Try again</span>");

                    // Handle errors if the request fails
                    console.error(error);
                }
            });
        });
    });

    function deletePost(element) {
    var postId = element.getAttribute("data-id");
    var tableName = element.getAttribute("data-tablename");
      var csrfToken = '{{ csrf_token() }}';

    // Send AJAX request to delete the post
    $.ajax({
        url: '/delete-post',
        method: 'POST',
        data: {
            postId: postId,
            tableName: tableName,
            _token: csrfToken
        },
        success: function(response) {
            toastr.success("Post deleted successfuly")
            // Remove the post card with fade-out animation
            $('[data-post-id="' + postId + '"]').fadeOut('slow', function() {
                $(this).remove();
            });
        },
        error: function(error) {
            toastr.error("Error deleting post")

            console.error('Error deleting post:', error);
        }
    });
}




function markAsRecovered(element) {
    var postId = element.getAttribute("data-id");
    var tableName = element.getAttribute("data-tablename");
    var csrfToken = '{{ csrf_token() }}';

    // Send AJAX request to delete the post
    $.ajax({
        url: '/mark-as-recover',
        method: 'POST',
        data: {
            postId: postId,
            tableName: tableName,
            _token: csrfToken
        },
        success: function(response) {
            toastr.success("Congratulations")

            window.location.reload();
            // $('[data-post-id="' + postId + '"]').fadeOut('slow', function() {
            //     $(this).remove();
            // });
        },
        error: function(error) {
            toastr.error("Error! Try again.")

            console.error('Error :', error);
        }
    });
}
$(document).on('click', '.view-details', function() {
  var index = $(this).data('matches');
  console.log('Data Matches (String):', resData[index]);

  try {
    // Get the modal body
    var modalBody = $('#modalBody tbody');

    $('#match-c').html(resData[index]?.length || 0)
    // Clear existing content
    modalBody.empty();

    // Define the headings you want to display
    var headings = ['Category', 'Post Date', 'Similarity', 'Location', 'Same Features', 'Contact'];

    // Create a mapping object to associate headings with their corresponding keys
    var headingToKeyMap = {
      'Category': 'subcategory_name',
      'Post Date': 'created_at',
      'Similarity': 'similarityScore',
      'Location': 'locationStatus',
      'Same Features': 'matchedColumns',
      'Contact': 'user_id', // Assuming 'user_id' is the key for user ID in your data
    };

    // Iterate through each object in resData[index]
    resData[index]?.forEach(function(match) {
      // Create a new row for each object
      var row = $('<tr>');

      // Iterate through each heading
      headings.forEach(function(heading) {
        // Get the key associated with the heading from the mapping object
        var key = headingToKeyMap[heading];

        // Create a new cell for each heading, using the key to access the data in match
        var cell = $('<td>', {
          'data-label': heading,
          html: heading === 'Contact' ?
            // If heading is 'Contact', create a "Chat Now" button with a link to /chat/user-id
            `<a href="/create-chat/${match['postid']}/${match['id']}/${match['table']}" class="btn text-white btn-primary">Chat Now</a>` :
            match[key], // Otherwise, display the data
        });

        // Append the cell to the row
        row.append(cell);
      });

      // Append the row to the table body
      modalBody.append(row);
    });

    // Show the modal
    $('#detailsModal').modal('show');
  } catch (error) {
    // Handle parsing error
    console.error('Error parsing matches:', error);
  }
});






</script>
@endsection
