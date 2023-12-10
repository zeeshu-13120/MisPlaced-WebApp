<?php

namespace App\Http\Controllers\AdminControllers\Forms;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Form;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FormController extends Controller
{
    public function addForm(Request $request)
    {
        $mainCategories = Category::whereNull('parent_id')->get();
        $forms = Form::all();
        $formData = [];
        if ($request->id) {
            $formData = Form::find($request->id);
            $subcategory = Category::find($formData->category_id);

            $selectedcategory = Category::find($subcategory->parent_id)->subcategories;
            return view('AdminViews.Forms.addform', compact(['mainCategories', 'forms', 'formData', 'selectedcategory',
                'subcategory']));
        }
        return view('AdminViews.Forms.addform', compact(['mainCategories', 'forms', 'formData']));

    }
    public function buildForm(Request $request)
    {
        $request->validate([
            'form_title' => 'required|string|max:255',
            'subcategory_id' => 'required',
            'form_data' => 'required|json',
        ]);

        $formTitle = $request->input('form_title');
        $subcategoryId = $request->input('subcategory_id');
        $formData = $request->input('form_data');

        $this->createTable($formData, 'form' . $subcategoryId);
        if ($request->input('formId')) {
            // Update the existing form
            Form::where('id', $request->input('formId'))->update([
                'title' => $formTitle,
                'category_id' => $subcategoryId,
                'formdata' => $formData,
            ]);
        } else {
            Form::create([
                'title' => $formTitle,
                'category_id' => $subcategoryId,
                'formdata' => $formData,
                'table' => 'form' . $subcategoryId,
            ]);

        }

        return response()->json(['message' => 'Form data saved successfully']);
    }
    public function formsList()
    {

        $forms = Form::all();

        return view('AdminViews.Forms.formsList', compact('forms'));
    }
    public function getForms(Request $request)
    {

        $form = Form::where('category_id', $request->id)->get();

        if ($form) {
            // Form found, send it as JSON response
            return response()->json(['form' => $form]);
        } else {
            // Form not found, send an error response (you can customize the error message)
            return response()->json(['error' => 'Form not found'], 404);
        }
    }

    public function createTable($formData, $category_id)
    {
        // Define the table name
        $tableName = $category_id;
        $formData = json_decode($formData, true);
        // Check if the table exists
        if (Schema::hasTable($tableName)) {
            // Table exists, alter it
            foreach ($formData as $item) {
                $escapedColumnName = preg_replace('/[^a-zA-Z0-9]/', '', $item['name']);
                if (!empty($escapedColumnName) && !Schema::hasColumn($tableName, $escapedColumnName)) {
                    // Add the column if it does not exist
                    DB::statement("ALTER TABLE $tableName ADD COLUMN $escapedColumnName VARCHAR(255)");
                }
            }
        } else {
            // Table does not exist, create it
            Schema::create($tableName, function (Blueprint $table) use ($formData) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('category_id');
                $table->unsignedBigInteger('subcategory_id');
                $table->string('post_type');
                $table->string('status')->default('pending');
                $table->text('user_route_cords')->nullable();
                $table->unsignedBigInteger('form_id');

                foreach ($formData as $item) {
                    if(!empty($item['name']))
                    {
                    $escapedColumnName = preg_replace('/[^a-zA-Z0-9]/', '', $item['name']);
                    if (!empty($escapedColumnName)) {
                        // Use backticks to handle special characters in column names
                        $escapedColumnName = $escapedColumnName;
                        $table->string($escapedColumnName)->nullable();
                    }
                }
                }
                $table->foreign('form_id')->references('id')->on('forms');
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('category_id')->references('id')->on('categories');
                $table->foreign('subcategory_id')->references('id')->on('categories');
                $table->timestamps();
            });
        }
    }

    public function deleteForm($id)
    {
        try {
            // Find the form based on the provided id
            $form = Form::find($id);

            if (!$form) {
                return response()->json(['message' => 'Form not found or already deleted'], 404);
            }

            // Get the table name associated with the form
            $tableName = $form->table;

            // Dynamically delete the associated child table
            $childTableName = $tableName;

            // Check if the child table exists
            if (Schema::hasTable($childTableName)) {
                // Drop the child table
                Schema::dropIfExists($childTableName);
            }

            // Delete the form
            $form->delete();

            return response()->json(['message' => 'Form deleted successfully'], 200);
        } catch (\Exception $e) {
            // Handle exceptions if any
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllposts(Request $request)
    {
        try {
            // Retrieve all forms with their associated child data, user information, and category/subcategory names
            $forms = DB::table('forms')
                ->select('forms.*') // Add the form columns you need
                ->get();

            // Loop through each form and dynamically join its associated child table
            foreach ($forms as $form) {
                $tableName = $form->table;

                // Check if the child table exists
                if (Schema::hasTable($tableName)) {
                    // Join with the users table based on user_id
                    // Join with the categories table based on category_id and subcategory_id
                    $childData = DB::table($tableName)
                        ->rightjoin('users', 'users.id', '=', $tableName . '.user_id')
                        ->rightJoin('categories', 'categories.id', '=', $tableName . '.subcategory_id')
                        ->where($tableName . '.form_id', $form->id)
                        ->select($tableName . '.id as post_id', $tableName . '.*', 'users.*', 'categories.name AS category_name')
                        ->get();

                    // Add the child data to the form object
                    $form->childData = $childData;
                }
            }

            return view('Adminviews.Forms.allposts', compact('forms'));
        } catch (\Exception $e) {
            // Handle exceptions if any
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deletePost(Request $request)
    {
        $postId = $request->id;
        $tableName = $request->table;

        try {
            // Use DB facade to delete the row from the specified table based on postId
            DB::table($tableName)->where('id', $postId)->delete();

            // Return a success response
            return response()->json(['message' => 'Post deleted successfully'], 200);
        } catch (\Exception $e) {
            echo $e;
            // Return an error response if deletion fails
            return response()->json(['error' => 'Failed to delete post'], 500);
        }
    }

    public function viewPost(Request $request)
    {

        $id = $request->id;
        $tableName = $request->table;

        // Check if the table exists
        if (Schema::hasTable($tableName)) {
            // The table exists, proceed with the query
            $post = DB::table($tableName)
                ->join('users', 'users.id', '=', $tableName . '.user_id')
                ->join('categories', 'categories.id', '=', $tableName . '.subcategory_id')
                ->join('forms', 'forms.id', '=', $tableName . '.form_id')
                ->where($tableName . '.id', $id)
                ->select($tableName . '.*', 'users.*', 'forms.*', 'categories.name AS category_name')
                ->first();

            if ($post->user_id == Auth::user()->id) {
                return view('Post.viewPost', compact('post'));
            }
            return redirect()->back();

        } else {
            return redirect()->back();
        }

    }

    public function viewPostAdmin(Request $request)
    {

        $id = $request->id;
        $tableName = $request->table;

        // Check if the table exists
        if (Schema::hasTable($tableName)) {
            // The table exists, proceed with the query
            $post = DB::table($tableName)
                ->join('users', 'users.id', '=', $tableName . '.user_id')
                ->join('categories', 'categories.id', '=', $tableName . '.subcategory_id')
                ->join('forms', 'forms.id', '=', $tableName . '.form_id')
                ->where($tableName . '.id', $id)
                ->select($tableName . '.*', 'users.*', 'forms.*', 'categories.name AS category_name')
                ->first();
            if ($post) {

                return view('Post.viewPost', compact('post'));
            } else {
                return redirect()->back()->with('error_msg', "not able to view this post.");
            }

        } else {
            return ":hello";
            return redirect()->back();
        }

    }
}
