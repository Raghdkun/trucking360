<?php

use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\TrackVisitor;
use Illuminate\Support\Facades\Route;


use App\Models\Visitor;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\GeneralSettingController;
use App\Models\GeneralSetting;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\AddCarouselController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardContactController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServicePageController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AfpDetailController;
use App\Http\Controllers\LogController;
use App\Models\Log;
use App\Models\Faq;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ZoloClientInfoController;




Route::get('/sitemap.xml', [SitemapController::class, 'generate'])->name('sitemap');

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware(TrackVisitor::class);
Route::post('/visitors/store', [VisitorController::class, 'store'])->name('visitors.store');
$settings = GeneralSetting::first();

Route::view('/message', 'message', compact('settings'))->name('message');
Route::get('/comingsoon', function () {
    $settings = GeneralSetting::first();

    return view('comingsoon', compact('settings'));
})->name('comingsoon');
Route::get('/aboutus', function () {
    $settings = GeneralSetting::first();

    return view('about.show', compact('settings'));
})->name('aboutus');

Route::get('/bookings/create', function () {
    $settings = GeneralSetting::first();

    return view('embedbooking', compact('settings'));
})->name('embedbooking');

Route::get('/pricing', function () {
    $settings = GeneralSetting::first();

    return view('pricing.show', compact('settings'));
})->name('pricing');

Route::get('/faqs', function () {
    $settings = GeneralSetting::first();
    $faqs = Faq::all();

    return view('faqs.show', compact('settings', 'faqs'));
})->name('faqs');
Route::get('/dashboard360', function () {
    $settings = GeneralSetting::first();
    $faqs = Faq::all();


    return view('dashboard360.index', compact('settings', 'faqs'));
})->name('dashboard360');

Route::get('/services', [ServicePageController::class, 'index'])->name('services.index')->middleware(TrackVisitor::class);;
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show')->middleware(TrackVisitor::class);;

Route::get('/form', function () {


    return view('form.show');
})->name('form');

Route::get('/booking', function () {


    return view('bookings.show');
})->name('booking');




Route::get('/contact', function () {
    $settings = GeneralSetting::first();

    return view('contact', compact('settings'));
})->middleware(TrackVisitor::class);;
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/contactus', function () {
    $settings = GeneralSetting::first();

    return view('contact.show', compact('settings'));
})->name('contact');

Route::get('/whyt360', function () {
    $settings = GeneralSetting::first();

    return view('why.show', compact('settings'));
})->name('whyt360');


Route::get('/about', [AboutUsController::class, 'show'])->name('about');



// Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create')->middleware(TrackVisitor::class);;
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store')->middleware(TrackVisitor::class);;


Route::resource('customers', CustomerController::class);
// Route::get('/bookings', [BookingController::class, 'store']);



Route::resource('schedules', ScheduleController::class);

// API route to fetch bookings for the calendar
Route::get('/api/bookings', [BookingController::class, 'index']);


Route::get('/dashboard', function () {
    $settings = GeneralSetting::first();
    $logs = Log::with('user')->orderBy('created_at', 'desc')->paginate(10);
    $visitors = Visitor::latest()->paginate(10);




    return view('dashboard', compact('settings', 'logs', 'visitors'));
})->middleware(['auth', 'verified'])->name('dashboard');


// Register
Route::get('dsp1738', [RegisteredUserController::class, 'create'])->name('register');
Route::post('dsp1738', [RegisteredUserController::class, 'store']);

// Login
Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store']);

//dropdown-dashboard-pages


Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/markAsRead', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');


Route::middleware('auth')->group(function () {


    // Route::resource('carousel', CarouselController::class);
    // routes/web.php
    // Route::post('/carousel/store', [CarouselController::class, 'store'])->name('carousel.store');
    // // routes/web.php
    Route::get('/dashboard/visitors', [VisitorController::class, 'index'])->name('visitors.index');


    // Route::get('/carousel', [CarouselController::class, 'index'])->name('carousel.index');
    Route::get('dashboard/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('dashboard/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('dashboard/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');

    Route::get('/dashboard/logs', [LogController::class, 'index'])->name('logs.index');

    // Route::get('/dashboard/about', function () {
    //     return view('dashboard.about');
    // })->name('dashboard.about');
    // Meeting Schedule Routes

    // Display all client records
    Route::get('dashboard/clientinfo', [ZoloClientInfoController::class, 'index'])
        ->name('zolo-clientinfo.index');

    // Show the edit form
    Route::get('dashboard/clientinfo/{id}/edit', [ZoloClientInfoController::class, 'edit'])
        ->name('zolo-clientinfo.edit');

    // Handle the update request
    Route::put('dashboard/clientinfo/{id}', [ZoloClientInfoController::class, 'update'])
        ->name('zolo-clientinfo.update');

    // Route::get('/dashboard/contact', [DashboardContactController::class, 'index'])->name('dashboard.contact');
    Route::get('/dashboard/contact', [DashboardContactController::class, 'index'])->name('dashboard.contact');
    Route::get('dashboard/contact/export/csv', [DashboardContactController::class, 'exportCsv'])->name('dashboard.contact.export.csv');
    Route::get('dashboard/contact/export/excel', [DashboardContactController::class, 'exportExcel'])->name('dashboard.contact.export.excel');

    Route::get('/dashboard/pricing', function () {
        $settings = GeneralSetting::first();

        return view('dashboard.pricing', compact('settings'));
    })->name('dashboard.pricing');

    // Route::get('/dashboard/contact', function () {
    //     return view('dashboard.contact');
    // })->name('dashboard.contact');

    // Route::get('/dashboard/services', function () {
    //     return view('dashboard.services');
    // })->name('dashboard.services');
    // Route::resource('dashboard/services', ServiceController::class);

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('services/create', [ServiceController::class, 'create'])->name('services.create');
        Route::post('services', [ServiceController::class, 'store'])->name('services.store');
        Route::get('services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
        Route::put('services/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    });

    // Group routes under the dashboard prefix and middleware
    Route::prefix('dashboard')->middleware(['auth'])->group(function () {
        Route::get('/faqs', [FaqController::class, 'adminIndex'])->name('dashboard.faqs.index');
        Route::get('/faqs/create', [FaqController::class, 'create'])->name('dashboard.faqs.create');
        Route::post('/faqs', [FaqController::class, 'store'])->name('dashboard.faqs.store');
        Route::get('/faqs/{faq}/edit', [FaqController::class, 'edit'])->name('dashboard.faqs.edit');
        Route::put('/faqs/{faq}', [FaqController::class, 'update'])->name('dashboard.faqs.update');
        Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])->name('dashboard.faqs.destroy');
        Route::post('/faqs/update-order', [FaqController::class, 'updateOrder'])->name('dashboard.faqs.updateOrder');
    });


    // About Us Routes
    Route::get('/dashboard/about', [AboutUsController::class, 'index'])->name('dashboard.about');
    Route::post('/dashboard/about/store', [AboutUsController::class, 'store'])->name('dashboard.about.store');


    // Features Routes
    Route::post('/dashboard/about/features', [AboutUsController::class, 'storeFeature'])->name('dashboard.about.features.store');
    Route::delete('/dashboard/about/features/{feature}', [AboutUsController::class, 'destroyFeature'])->name('dashboard.about.features.destroy');

    // Team Members Routes
    Route::post('/dashboard/about/team', [AboutUsController::class, 'storeTeamMember'])->name('dashboard.about.team.store');
    Route::delete('/dashboard/about/team/{teamMember}', [AboutUsController::class, 'destroyTeamMember'])->name('dashboard.about.team.destroy');


    Route::get('/dashboard/generals', [GeneralSettingController::class, 'index'])->name('dashboard.generals');
    Route::post('/dashboard/generals', [GeneralSettingController::class, 'update'])->name('dashboard.generals.update');
});

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::resource('bookings', BookingController::class);
});

Route::resource('customers', CustomerController::class);
Route::get('customers/export/{type}', [CustomerController::class, 'export'])->name('customers.export');

Route::resource('details', AfpDetailController::class);
Route::get('details/export/{type}', [AfpDetailController::class, 'export'])->name('details.export');

// Logout
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });



Route::fallback(function () {
    $settings = GeneralSetting::first();

    return response()->view('errors.404', compact('settings'), 404,);
});
// require __DIR__.'/auth.php';
