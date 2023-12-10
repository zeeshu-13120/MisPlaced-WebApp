<?php

use App\Http\Controllers\AdminControllers\Category\CategoryController;
use App\Http\Controllers\AdminControllers\Dashboard\Dashboard;
use App\Http\Controllers\AdminControllers\Forms\FormController;
use App\Http\Controllers\AdminControllers\ManageUsers\Users;
use App\Http\Controllers\AdminControllers\Registration\AdminLogin;
use App\Http\Controllers\AdminControllers\Registration\Settings;
use App\Http\Controllers\AdminControllers\Transaction\TransactionController;
use App\Http\Controllers\UserControllers\Chat;
use App\Http\Controllers\UserControllers\ContactController;
use App\Http\Controllers\UserControllers\Firebase\FCMController;
use App\Http\Controllers\UserControllers\PaymentController;
use App\Http\Controllers\UserControllers\Posts;
use App\Http\Controllers\UserControllers\Registration\ForgotPassword;
use App\Http\Controllers\UserControllers\Registration\GoogleLogin;
use App\Http\Controllers\UserControllers\Registration\Login;
use App\Http\Controllers\UserControllers\Registration\Logout;
use App\Http\Controllers\UserControllers\Registration\Singup;
use App\Http\Controllers\UserControllers\Registration\VerifyEmail;
use App\Http\Controllers\UserControllers\UserSettings;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('guest')
    ->group(function () {

        Route::controller(Singup::class)->group(function () {
            Route::get('/signup', 'signup');
            Route::post('/signup', 'store');
        });
        Route::controller(Login::class)->group(function () {

            Route::get('/login', 'login')->name('login');
            Route::post('/login', 'dologin');

        });
        Route::controller(GoogleLogin::class)->group(function () {

            Route::get('login/google', 'googleLogin')->name('login.google');
            Route::get('login/google/callback', 'googleLoginCallBack')->name('login.googleCallback');

        });

        Route::controller(ForgotPassword::class)->group(function () {
            Route::get('/forgot_password', 'forgot_password')->name('password.forgot');
            Route::POST('/forgot_password', 'sendPasswordResetLinkEmail')->name('password.send');
            Route::POST('/set_password', 'setPassword')->name('password.set');
            Route::get('/reset_password', 'resetPassword')->name('password.reset');
        });

        Route::controller(VerifyEmail::class)->group(function () {
            Route::get('/email/verify/{id}/{hash}', 'verifyEmail')->name('verification.verify');
            Route::get('/email_verification', 'email_verification')->name('verification.send');
            // Route::post('/resend-email', 'resendVerificationCode')->name('verification.send');
        });
    });
Route::controller(Logout::class)->group(function () {
    Route::get('/logout', 'logout')->name('logout');
});

Route::middleware('auth')
    ->group(function () {

        Route::controller(UserSettings::class)->group(function () {
            Route::get('/profile', 'showProfile')->name('profile.show');
            Route::get('/profile_settings', 'showProfileSettings')->name('profile_settings.show');
            Route::POST('/profile_settings', 'updateInfo')->name('profile_settings.update');
            Route::POST('/update_password', 'updatePassword')->name('profile_settings.password.update');
            Route::POST('/update_address', 'updateAddress')->name('profile_settings.address');
        });
        Route::controller(Posts::class)->group(function () {
            Route::GET('/add-post', 'index')->name('post.index');
            Route::POST('/save-post', 'savePost')->name('post.save');
            Route::POST('/delete-post', 'deletePost')->name('post.delete');
            Route::POST('/mark-as-recover', 'recoverPost')->name('post.recover');
            Route::GET('/find-match/{id}/{table}', 'findMatch')->name('post.match');
            Route::GET('/my-posts', 'myPosts')->name('post.list');

        });
        Route::controller(FormController::class)->group(function () {
            Route::GET('/getform/{id}', 'getForms')->name('form.get');
            Route::GET('/view-post/{id}/{table}', 'viewPost')->name('form.viewPost');

        });

        Route::controller(Chat::class)->group(function () {
            Route::GET('/chat', 'getChats')->name('chat.get');
            Route::POST('/upload-file', 'uploadFile')->name('chat.uploadFile');
            Route::GET('/create-chat/{post1}/{post2}/{table}', 'createChat')->name('chat.createChat');

        });
        Route::controller(PaymentController::class)->group(function () {
            Route::POST('/charge', 'charge')->name('charge.now');

        });

        Route::controller(FCMController::class)->group(function () {
            Route::post('/save-token', 'index')->name('token.save');
            Route::post('/send-notification', 'sendNotification')->name('token.send');

        });

    });

/////Admin Routes////

Route::controller(AdminLogin::class)->group(function () {
    Route::get('admin/logout', 'logout')->name('admin.logout');
});
Route::middleware('redirect_if_admin_authenticated')
    ->group(function () {

        Route::controller(AdminLogin::class)->group(function () {
            // Route::get('/profile', );
            // Route::get('/wishlist', );
            Route::get('admin', 'login')->name('amdin');
            Route::get('admin/login', 'login')->name('admin.login.view');
            Route::POST('admin/login', 'doLogin')->name('admin.login');
            //  Route::POST('admin/add_admin', 'store')->name('admin.create');
        });
    });

Route::controller(CategoryController::class)->group(function () {
    Route::GET('/category_list', 'categoryList')->name('category.list');
    Route::GET('/get-subcategories/{id?}', 'getSubcategories')->name('category.subcategory');
});
Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');

Route::middleware('admin_auth')
    ->prefix('admin')
    ->group(function () {
        Route::get('/contact/messages', [ContactController::class, 'showAllMessages'])->name('contact.messages');
        Route::get('/delete_message/{id}', [ContactController::class, 'deleteMessage'])->name('contact.delete');

        Route::controller(AdminLogin::class)->group(function () {

        });

        Route::controller(Dashboard::class)->group(function () {
            Route::get('/dashboard', 'dashboard')->name('dashboard.view');
        });
        Route::controller(Users::class)->group(function () {
            Route::get('/users_list', 'userList')->name('users.list');
            Route::get('/get_users_list', 'getUsers')->name('users.getall');
            Route::get('/get_single_user_data/{id}', 'getUserData')->name('users.getone');

            Route::get('/users_report', 'usersReport')->name('users.report');
            Route::put('/update_user/{id}', 'updateUser')->name('users.update');
            Route::POST('/reset_user_password/{id}', 'sendPasswordResetLink')->name('users.password');
        });

        Route::controller(Settings::class)->group(function () {

            Route::get('/profile_settings', 'settings')->name('admin.settings');
            Route::post('/profile_settings', 'updateAccount')->name('admin.settings.update');
            Route::post('/update_password', 'updatePassword')->name('admin.settings.update.password');

        });

        Route::controller(CategoryController::class)->group(function () {
            Route::GET('/add_category/{id?}', 'addCategory')->name('category.add');
            Route::GET('/category_list', 'categoryList')->name('category.list');
            Route::PUT('/update_category/{id}', 'createCategory')->name('category.update');
            Route::GET('/delete_category/{id}', 'deleteCategory')->name('category.delete');
            Route::POST('/create_category', 'createCategory')->name('category.create');
            Route::GET('/get-subcategories/{id?}', 'getSubcategories')->name('category.subcategory');
        });
        Route::controller(FormController::class)->group(function () {
            Route::GET('/add_form/{id?}', 'addForm')->name('form.add');
            Route::POST('/build_form', 'buildForm')->name('form.build');
            Route::GET('/delete_form/{id}', 'deleteForm')->name('form.delete');
            Route::GET('/delete_post/{id}/{table}', 'deletePost')->name('form.postdelete');
            Route::GET('/forms', 'formsList')->name('form.list');
            Route::GET('/all-posts', 'getAllposts')->name('form.all');
            Route::GET('/view-post-admin/{id}/{table}', 'viewPostAdmin')->name('form.viewPost');

        });
        Route::controller(TransactionController::class)->group(function () {
            Route::GET('/transactions', 'index')->name('transactions.get');

        });
    });
