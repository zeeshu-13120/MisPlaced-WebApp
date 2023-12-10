<?php

namespace App\Http\Controllers\UserControllers;

use App\Http\Controllers\Controller;
use App\Mail\TopUsersEmail;
use App\Models\Category;
use App\Models\Form;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class Posts extends Controller
{
    public function index()
    {

        $mainCategories = Category::whereNull('parent_id')->get();

        return view('Post.add-post', compact('mainCategories'));
    }

    public function savePost(Request $request)
    {
        $tableName = 'form' . $request->input('subcategory_id');
        $columns = Schema::getColumnListing($tableName);
        $data = $request->except(['_token', 'map-toption']);

        // Handle file uploads for dynamic file inputs
        foreach ($request->all() as $inputName => $inputValue) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $fileName = time() . '_' . $file->getClientOriginalName();

                // Store the file to the 'uploads' directory
                $filePath = $file->storeAs('uploads', $fileName, 'public');

                // Save the file path in the database for the specific input.
                $data[$inputName] = "/" . "storage/" . $filePath;
            }
        }

        // Serialize array inputs before insertion
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = serialize($value);
            }
        }

        $data['user_id'] = Auth::user()->id;
        $data['category_id'] = $request->input('subcategory_id');
        $data['created_at'] = Carbon::now();
        $data['form_id'] = Form::where('table', $tableName)->first()->id;

        // Insert data into the table
        $insertedId = DB::table($tableName)->insertGetId($data);

        if ($insertedId) {
            $this->findMatch($request, $tableName, $insertedId, "ok");
            return redirect('/')->with('success', "Your Post has been added and we will notify you when we will find the match.");
        } else {
            return redirect()->back()->with('error_msg', "Something went wrong try again");
        }
        // Your code to display a success message or handle errors
    }

    public function myPosts(Request $request)
    {
        $forms = Form::all();
        $userId = Auth::user()->id;
        $posts = [];

        // Loop through the Form rows and fetch data from their respective tables
        foreach ($forms as $form) {
            // Get the table name from the current Form row
            $tableName = $form->table;

            // Fetch data from the specified table where user_id = userId
            $data = DB::table($tableName)->where('user_id', $userId)->get();

            // Fetch category names for each row
            foreach ($data as $row) {
                $category = DB::table('categories')->where('id', $row->category_id)->first();
                $subcategory = DB::table('categories')->where('id', $row->subcategory_id)->first();

                // Add category names to the row data
                $row->category_name = $category->name ?? ' ';
                $row->subcategory_name = $subcategory->name ?? ' ';
                $row->icon = $subcategory->icon ?? $category->icon ?? ' ';
                $row->table = $tableName;
            }

            // Add the fetched data to the combined data array
            $posts[] = $data;
        }

        return view('Post.my-posts', compact('posts'));
    }

    public function findMatch(Request $request, $tableName = null, $id = null, $ok = null)
    {
        if (!$ok) {

            $id = $request->id;
            $tableName = $request->table;
        }

        // Fetch data using raw SQL query
        $data = DB::table($tableName)->where('id', $id)->first();
        //if the post is already recovered no neeed to find match.
        if ($data->status != 'pending') {

            return ['data' => []];
            die;
        }
        if ($data) {
            $postType = $data->post_type;
            $categoryId = $data->category_id;
            $subcategoryId = $data->subcategory_id;

            // Fetch rows based on post_type, category_id, subcategory_id, and status
            $relatedData = DB::table($tableName)
                ->join('categories', 'categories.id', '=', $tableName . '.subcategory_id')
                ->select(
                    $tableName . '.*', // Select all columns from the main table
                    'categories.name as subcategory_name' // Select the subcategory name
                )
                ->where(function ($query) use ($postType, $categoryId, $subcategoryId) {
                    $query->where(function ($q) use ($postType, $categoryId, $subcategoryId) {
                        $q->where('post_type', '!=', $postType)
                            ->where('category_id', '=', $categoryId)
                            ->where('subcategory_id', '=', $subcategoryId);
                    });
                })
                ->where('status', 'pending')
                ->get()->toArray();

// Calculate similarity scores for each row in relatedData
            // Get the column names from the data row
            $columnsToCompare = array_keys((array) $data);
// Calculate similarity scores for each row in relatedData
            foreach ($relatedData as $key => $row) {
                $matchedColumns = 0; // Count of matched columns

                foreach ($columnsToCompare as $column) {

                    // Compare columns if they exist in both data and relatedData rows
                    if ($column != 'id' && $column != 'user_id' && $column != 'status' && $column != 'user_route_cords' && $column != 'post_type' && $column != 'category_id' && $column != 'subcategory_id') {
                        if (isset($data->$column) && isset($row->$column)) {
                            if ($data->$column == $row->$column) {
                                // Increment the count of matched columns
                                $matchedColumns++;
                            }
                        }
                    }
                }

                // Store the count of matched columns in the row
                $relatedData[$key]->matchedColumns = $matchedColumns;
                $relatedData[$key]->totalColumns = count($columnsToCompare) - 7;
                $relatedData[$key]->similarityScore = 0;
            }

// Sort the relatedData array based on matched columns in descending order
            usort($relatedData, function ($a, $b) {
                return $b->matchedColumns - $a->matchedColumns;
            });

// Get the top 10 rows
            $top10Rows = array_slice($relatedData, 0, 10);

            foreach ($top10Rows as $key => $row) {

                $routeCoordinatesString = 0.0;
                $cLat = 0.0;
                $cLng = 0.0;
                if ($data->post_type == 'lost') {
                    $routeCoordinatesString = $data->user_route_cords;
                    $userRouteCoords = preg_split("/[,|]/", $row->user_route_cords);

                    // Set cLat and cLng values
                    $cLat = $userRouteCoords[0]; // Latitude
                    $cLng = $userRouteCoords[1]; // Longitude
                } else {
                    $routeCoordinatesString = $row->user_route_cords;
                    $userRouteCoords = preg_split("/[,|]/", $data->user_route_cords);

                    // Set cLat and cLng values
                    $cLat = $userRouteCoords[0]; // Latitude
                    $cLng = $userRouteCoords[1]; // Longitude
                }
                // Convert route coordinates string to an array of points
                $routeCoordinates = collect(explode('|', $routeCoordinatesString))->map(function ($coordinate) {
                    $parts = explode(',', $coordinate);
                    if (count($parts) === 2 && is_numeric($parts[0]) && is_numeric($parts[1])) {
                        list($latitude, $longitude) = $parts;
                        return ['latitude' => (float) $latitude, 'longitude' => (float) $longitude];
                    } else {
                        // Handle invalid coordinate format, for example, log an error or skip this coordinate
                        return null; // Or any value that indicates an invalid coordinate
                    }
                })->filter()->toArray();

// Iterate through the route coordinates to check if the given point is between any two points
                for ($i = 0; $i < count($routeCoordinates) - 1; $i++) {
                    $aLat = $routeCoordinates[$i]['latitude'];
                    $aLng = $routeCoordinates[$i]['longitude'];

                    $bLat = $routeCoordinates[$i + 1]['latitude'];
                    $bLng = $routeCoordinates[$i + 1]['longitude'];

                    // Check if the given point is between the current and next points
                    if ($this->isBetweenPoints($aLat, $aLng, $bLat, $bLng, $cLat, $cLng)) {
                        $top10Rows[$key]->locationStatus = 'matched';
                        $top10Rows[$key]->matchedColumns + 1;

                    }
                }
                if (empty($top10Rows[$key]->locationStatus)) {
                    $top10Rows[$key]->locationStatus = 'not matched';

                }

                $top10Rows[$key]->similarityScore = number_format(($top10Rows[$key]->matchedColumns / $top10Rows[$key]->totalColumns) * 100, 2);
                $top10Rows[$key]->postid = $id;
                $top10Rows[$key]->table = $tableName;
                usort($top10Rows, function ($a, $b) {
                    return $b->similarityScore <=> $a->similarityScore;
                });

            }
            if ($ok) { //if it is bieng called from save post only then send emails.

                // Extract user IDs
                $userIds = array_column($top10Rows, 'user_id');

                // Get user email addresses
                $userEmails = User::whereIn('id', $userIds)->pluck('email')->toArray();

                // Send emails to users
                foreach ($userEmails as $email) {
                    Mail::to($email)->send(new TopUsersEmail());
                }
                //send mail to current user also.
                Mail::to(Auth::user()->email)->send(new TopUsersEmail());
            }
            return response()->json(['data' => $top10Rows]);

            die;
            // Return the related data
        } else {
            // Data not found, return an error response
            return response()->json(['message' => 'Data not found.'], 404);
        }

        die;
        // Route coordinates string from the database

    }

    public function isBetweenPoints($aLat, $aLng, $bLat, $bLng, $cLat, $cLng)
    {

        $crossProduct = ($cLat - $aLat) * ($bLng - $aLng) - ($cLng - $aLng) * ($bLat - $aLat);
        // echo "cross_product: " . $crossProduct; //debug

        if (abs($crossProduct) > 0.000045) { // checking accuracy upto 50m
            return false;
        }

        $dotProduct = ($cLng - $aLng) * ($bLng - $aLng) + ($cLat - $aLat) * ($bLat - $aLat);
        //  echo "<br>dotProduct: " . $dotProduct;
        if ($dotProduct < 0) {
            return false;
        }

        $squaredLength = ($bLng - $aLng) * ($bLng - $aLng) + ($bLat - $aLat) * ($bLat - $aLat);
        // echo "<br>SquaredLength: " . $squaredLength;
        return !($dotProduct > $squaredLength);
    }

    public function deletePost(Request $request)
    {
        $postId = $request->input('postId');
        $tableName = $request->input('tableName');

        try {
            // Use DB facade to delete the row from the specified table based on postId
            DB::table($tableName)->where('id', $postId)->where('user_id', Auth::User()->id)->delete();

            // Return a success response
            return response()->json(['message' => 'Post deleted successfully'], 200);
        } catch (\Exception $e) {
            // Return an error response if deletion fails
            return response()->json(['error' => 'Failed to delete post'], 500);
        }
    }
    public function recoverPost(Request $request)
    {
        $postId = $request->input('postId');
        $tableName = $request->input('tableName');

        try {
            // Use DB facade to update the status of the row in the specified table based on postId
            DB::table($tableName)
                ->where('id', $postId)
                ->where('user_id', Auth::user()->id)
                ->update(['status' => 'recovered']);

            // Return a success response
            return response()->json(['message' => 'Post status updated to recovered successfully'], 200);
        } catch (\Exception $e) {
            // Return an error response if the update fails
            return response()->json(['error' => 'Failed to update post status'], 500);
        }
    }

}
