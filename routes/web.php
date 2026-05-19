<?php

use App\Http\Controllers\Admin\Pages;
use App\Http\Controllers\FrontEnd\SubjectDetail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\Dashboards;
use App\Http\Controllers\Admin\Users;
use App\Http\Controllers\Admin\Roles;
use App\Http\Controllers\Admin\Permissions;
use App\Http\Controllers\Admin\ContactUs;
use App\Http\Controllers\Admin\CustomQuery;
use App\Http\Controllers\Admin\PaymentInfo;
use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Admin\Page;
use App\Http\Controllers\Admin\Menus;
use App\Http\Controllers\Admin\Subjects;
use App\Http\Controllers\Admin\Boards;
use App\Http\Controllers\Admin\Chapters;
use App\Http\Controllers\Admin\Topics;
use App\Http\Controllers\Admin\QuestionGroup;
use App\Http\Controllers\Admin\Question;
use App\Http\Controllers\Admin\Quiz;
use App\Http\Controllers\Admin\Levels;
use App\Http\Controllers\Admin\Assignment;
use App\Http\Controllers\Admin\CaseStudys;
use App\Http\Controllers\Admin\Submissions;
use App\Http\Controllers\Admin\Teacher;
use App\Http\Controllers\Admin\Exam;
use App\Http\Controllers\Admin\ExamSchedule;
use App\Http\Controllers\Admin\Grade;
use App\Http\Controllers\Admin\TakeExam;
use App\Http\Controllers\Admin\PaymentMethod;
use App\Http\Controllers\Admin\Coureses;
use App\Http\Controllers\Admin\Notification;
use App\Http\Controllers\Admin\ThankYou;
use App\Http\Controllers\Admin\ReNewPackage;
use App\Http\Controllers\Admin\Settings;
use App\Http\Controllers\Admin\UserNotification;
use App\Http\Controllers\Admin\StudentDashboard;
use App\Http\Controllers\Admin\StudentSection;
use App\Http\Controllers\Admin\BookDetailSection;
use App\Http\Controllers\Admin\BookSection;
use App\Http\Controllers\Admin\ChepterSection;
use App\Http\Controllers\Admin\CourseSection;
use App\Http\Controllers\Admin\Check;
use App\Http\Controllers\Admin\Career;
use App\Http\Controllers\Admin\Featured;
use App\Http\Controllers\Admin\PackageContentController;
use App\Http\Controllers\Admin\ScholarShip;
use App\Http\Controllers\Admin\Tabs;
use App\Http\Controllers\Admin\PayemtFailed;
use App\Http\Controllers\Admin\PackageController;



// front end
use App\Http\Controllers\FrontEnd\Home;
use App\Http\Controllers\FrontEnd\Error;
use App\Http\Controllers\FrontEnd\About;
use App\Http\Controllers\Admin\Subscribe;
use App\Http\Controllers\FrontEnd\CaseStudies;
use App\Http\Controllers\FrontEnd\SubscriptionForm;
use App\Http\Controllers\Admin\UserProfile;
use App\Http\Controllers\FrontEnd\Packages;
use App\Http\Controllers\FrontEnd\Board;

Route::get('/phpinfo', function () {
    phpinfo();
});

// routes/web.php
// routes/web.php

Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::prefix('admin')->name('admin.')->group(function () {
             Route::get('/subscriptions', [Subscribe::class, 'index'])->name('subscribe.index');
        Route::get('/subscriptions/{id}/view', [Subscribe::class, 'view'])->name('subscribe.view');
        Route::post('/subscriptions/update', [Subscribe::class, 'updateSubscription'])->name('subscribe.update');
        });
        Route::post('/packages/get-levels-by-board', [PackageController::class, 'getLevelsByBoard'])
         ->name('admin.packages.get-levels-by-board');
        Route::get('/admin/packages/{package}/subjects/{subject}/content', [PackageController::class, 'manageSubjectContent'])
        ->name('admin.packages.subjects.content');
        Route::DELETE('/admin/packages/{package}/subjects/{subject}/remove', [PackageController::class, 'romoveSubjectContent'])
        ->name('admin.packages.subjects.remove');
    
        Route::post('/admin/packages/{package}/subjects/{subject}/content/save', [PackageController::class, 'saveSubjectContent'])
        ->name('admin.packages.subjects.content.save');
        Route::prefix('admin')->name('admin.')->group(function () {
        Route::prefix('packages')->name('packages.')->group(function () {

        Route::get('/', [PackageController::class, 'index'])->name('index');
        Route::get('/create', [PackageController::class, 'create'])->name('create');
        Route::post('/', [PackageController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PackageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PackageController::class, 'update'])->name('update');
        Route::delete('/{id}', [PackageController::class, 'destroy'])->name('destroy');

        // Manage Subjects (list)
        Route::get('/{package}/subjects', [PackageController::class, 'manageSubjects'])->name('subjects');
        Route::post('/{package}/subjects', [PackageController::class, 'addSubject'])->name('add-subject');

        // Manage Content of a Specific Subject (jsTree)
        Route::get('/{package}/subjects/{subject}/content', [PackageController::class, 'manageSubjectContent'])
            ->name('subjects.content');

        Route::post('/{package}/subjects/{subject}/content/save', [PackageController::class, 'saveSubjectContent'])
            ->name('subjects.content.save');

        // Other routes...
        Route::post('/{package}/subjects/{subject}/update-price', [PackageController::class, 'updateSubjectPrice'])
            ->name('update-subject-price');
        Route::delete('/{package}/subjects/{subject}', [PackageController::class, 'removeSubject'])
            ->name('remove-subject');
    });
});
    // feature
    Route::prefix('features')->name('features.')->group(function () {
        Route::get('/', [Featured::class, 'index'])->name('index');
        Route::post('/', [Featured::class, 'store'])->name('store');
        Route::post('/reorder', [Featured::class, 'reorder'])->name('reorder');
        Route::post('/{feature}/toggle-status', [Featured::class, 'toggleStatus'])
            ->name('toggle.status');
        Route::delete('/{feature}', [Featured::class, 'destroy'])->name('destroy');
    });
    // File Manager Routes
    Route::get('/filemanager', [FileManagerController::class, 'index'])->name('filemanager.index');
    Route::get('/filemanager/items', [FileManagerController::class, 'getItems'])->name('filemanager.items');
    Route::get('/filemanager/breadcrumb', [FileManagerController::class, 'getBreadcrumb'])->name('filemanager.breadcrumb');
    Route::post('/filemanager/folder', [FileManagerController::class, 'createFolder'])->name('filemanager.folder.create');
    Route::post('/filemanager/upload', [FileManagerController::class, 'uploadFiles'])->name('filemanager.upload');
    Route::post('/filemanager/upload-vimeo', [FileManagerController::class, 'uploadVimeoLink'])->name('filemanager.upload.vimeo');
    Route::delete('/filemanager/file/{id}', [FileManagerController::class, 'deleteFile'])->name('filemanager.file.delete');
    Route::delete('/filemanager/folder/{id}', [FileManagerController::class, 'deleteFolder'])->name('filemanager.folder.delete');
    Route::put('/filemanager/rename', [FileManagerController::class, 'rename'])->name('filemanager.rename');
    Route::get('/filemanager/download/{id}', [FileManagerController::class, 'download'])->name('filemanager.download');
    Route::post('/filemanager/copy', [FileManagerController::class, 'copy'])->name('filemanager.copy');
    Route::post('/filemanager/paste', [FileManagerController::class, 'paste'])->name('filemanager.paste');
    Route::get('/filemanager/properties', [FileManagerController::class, 'getProperties'])->name('filemanager.properties');
    Route::get('/filemanager/view/{id}', [FileManagerController::class, 'view'])->name('filemanager.view');
    Route::get('/filemanager/vimeo-embed/{id}', [FileManagerController::class, 'getVimeoEmbedUrl'])->name('filemanager.vimeo.embed');
    Route::get('/filemanager/vimeo-proxy/{id}', [FileManagerController::class, 'proxyVimeoVideo'])->name('filemanager.vimeo.proxy');
    //    // page settings 
    Route::get('/settings', [Settings::class, 'index'])->name('admin.settings.index');
    Route::post('/settings/store', [Settings::class, 'store'])->name('settings.store');
    Route::post('/upload/image', [Settings::class, 'handleFileUpload'])->name('upload.image');
    Route::post('/settings/add-row', [Settings::class, 'addRow'])->name('settings.add-row');

    //    // subscribe
    //     Route::get('/subscribe', Subscribe::class)->name('admin.subscribe');
    // Route::get('/user/profile', [UserProfile::class, 'index'])->name('admin.user.profile');
Route::prefix('admin')->group(function () {
        Route::get('/profile', [UserProfile::class, 'index'])->name('admin.profile');
        Route::post('/profile/update-name', [UserProfile::class, 'updateName'])->name('admin.profile.update-name');
        Route::post('/profile/update-password', [UserProfile::class, 'updatePassword'])->name('admin.profile.update-password');
    });
        Route::post('/student/completed-slide', [StudentDashboard::class, 'completedSlide'])->name('student.completed-slide');
    //     Route::post('/student/coureses/completed-slide', [Coureses::class, 'completedSlide'])->name('student.completed-slide.coureses');
        Route::post('/student/previous-slide', [StudentDashboard::class, 'previousSlide'])->name('student.previous-slide');
    //     Route::get('/student/notification/{id}', UserNotification::class)->name('student.notification');

    //     // student dashboard
  // Add these routes
     // Student Dashboard Routes
    Route::prefix('student')->name('student.')->middleware(['auth', 'verified'])->group(function () {
        Route::get('/subscription-cancelled', function () {
        return view('student.subscription-cancelled');
    })->name('subscription.cancelled');
     Route::get('/subscription-expired', function () {
        return view('student.subscription-expired');
    })->name('subscription.expired');
    // Main Dashboard
    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
    
    
Route::get('/chapter-content/{chapterId}', [StudentDashboard::class, 'getChapterContent']);
Route::post('/mark-completed/{contentId}', [StudentDashboard::class, 'markAsCompleted']);

// Update the feature content route
    Route::get('/feature-content/{featureKey}', [StudentDashboard::class, 'getFeatureContent'])
        ->name('feature-content');
    // Folder & Media Management
    Route::get('/folder-media/{folder_id}', [StudentDashboard::class, 'getFolderMedia'])->name('folder.media');
    Route::post('/mark-media-completed', [StudentDashboard::class, 'markMediaCompleted'])->name('mark.media.completed');
    Route::post('/toggle-folder/{folder_id}', [StudentDashboard::class, 'toggleFolder'])->name('toggle.folder');
    Route::post('/restart-folder/{folder_id}', [StudentDashboard::class, 'restartFolder'])->name('restart.folder');
    
    // Plan Management
    Route::post('/cancel-plan', [StudentDashboard::class, 'cancelPlan'])->name('cancel-plan');
    Route::post('/resubscribe', [StudentDashboard::class, 'RESUBSCRIBE'])->name('resubscribe');
    
    // Media Slideshow Routes (for old slideshow functionality)
    Route::post('/complete-media', [StudentDashboard::class, 'completeMedia'])->name('complete.media');
    Route::post('/completed-slide', [StudentDashboard::class, 'completedSlide'])->name('completed.slide');
    Route::post('/previous-slide', [StudentDashboard::class, 'previousSlide'])->name('previous.slide');
    
    // Feature Content Routes
    Route::get('/feature-content/{feature_name}', [StudentDashboard::class, 'getFeatureContent'])->name('feature.content');
    Route::get('/all-feature-content', [StudentDashboard::class, 'getAllFeatureContent'])->name('all.feature.content');
    Route::get('/feature-folders/{feature_name}', [StudentDashboard::class, 'getFeatureFolders'])->name('feature.folders');
    
    // Exams
    Route::get('/take-exam/{folder_id}', [StudentDashboard::class, 'takeExam'])->name('take.exam');
    
    // Additional routes that might be needed
    Route::get('/progress', [StudentDashboard::class, 'progress'])->name('progress');
    Route::get('/achievements', [StudentDashboard::class, 'achievements'])->name('achievements');
    Route::get('/profile', [StudentDashboard::class, 'profile'])->name('profile');
    Route::post('/update-profile', [StudentDashboard::class, 'updateProfile'])->name('update.profile');
    Route::get('/get-stats', [StudentDashboard::class, 'getStats'])->name('get.stats');
    
Route::get('/get-recent-activity', [StudentDashboard::class, 'getRecentActivity'])->name('get.recent.activity');
 // Chapter routes
    
    Route::get('/take-exam/{chapter_id}', [StudentDashboard::class, 'takeExam'])
        ->name('take-exam');
         // Chapter Management
    Route::get('/complete-course/{chapter_id}', [StudentDashboard::class, 'completeCourse'])->name('complete-course');
    Route::post('/mark-file-completed', [StudentDashboard::class, 'markFileCompleted'])->name('mark-file-completed');
    Route::post('/restart-chapter/{chapter_id}', [StudentDashboard::class, 'restartChapter'])->name('restart-chapter');
    Route::get('/student/refresh-chapter-data/{chapter_id}', [StudentDashboard::class, 'refreshChapterData'])->name('student.refresh-chapter-data');
Route::get('/student/get-file/{file_id}', [StudentDashboard::class, 'getFile'])->name('student.get-file');
});
    // Route::get('/payment-method', [PaymentMethod::class,'index'])->name('student.payment.method');
    Route::prefix('student')->middleware(['auth','verified'])->group(function () {
    Route::get('/ReNewPackage', [PaymentMethod::class, 'index'])->name('ReNewPackage');
    Route::get('/payment-method', [PaymentMethod::class, 'index'])->name('payment.method');
    Route::get('/payment-method/plan-details/{plan}', [PaymentMethod::class, 'getPlanDetails']);
    Route::post('/payment-method/calculate-price', [PaymentMethod::class, 'calculatePrice']);
    Route::post('/payment-method/checkout', [PaymentMethod::class, 'processCheckout']);
    Route::get('/payment-method/details/{id}', [PaymentMethod::class, 'showDetails'])->name('payment.method.details');
     // New payment response routes
    Route::get('/payment/response/{id}', [PaymentMethod::class, 'paymentResponse'])->name('payment.response');
    Route::get('/payment/cancel', [PaymentMethod::class, 'paymentCancel'])->name('payment.cancel');
    Route::get('/payment/success/{id}', [PaymentMethod::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/failed', [PaymentMethod::class, 'paymentFailed'])->name('paymentfailed');
    Route::get('/thank-you', [PaymentMethod::class, 'thankYou'])->name('thank-you');
    // / Payment response routes
    Route::get('/payment/response/{id}', [PaymentMethod::class, 'paymentResponse'])->name('payment.response');
    Route::get('/payment/failed', [PaymentMethod::class, 'paymentFailed'])->name('paymentfailed');
});
    Route::get('/payment-info', [PaymentInfo::class, 'index'])->name('student.payment.info');

    Route::get('/my-courses', [Coureses::class, 'index'])->name('student.my.courses');
    // admin
    Route::get('/dashboard', [Dashboards::class, 'index'])->name('admin.dashboard');
    Route::post('/dashboard', [Dashboards::class, 'index'])->name('admin.dashboard.filter');
    // Route::get('/dashboard', [Dashboards::class,'index'])->name('admin.dashboard');
    // Route::get('/filemanager', [FileManager::class, 'index'])->name('admin.filemanager');
    // Route::get('/filepreview/{path}', [FileController::class, 'show'])->where('path', '.*')->name('assets.show');
    Route::controller(Roles::class)->group(function () {
        Route::get('/user/role', 'index')->name('users.roles.index');
        Route::post('/user/role/show', 'show')->name('user.role.show');
        Route::post('/user/role/store', 'store')->name('user.role.store');
        Route::post('/user/role/edit', 'edit')->name('user.role.edit');
        Route::post('/user/role/update', 'update')->name('user.role.update');
        Route::post('/user/role/destroy', 'destroy')->name('user.role.destroy');
    });

    Route::controller(Users::class)->group(function () {
        Route::get('/user/list', 'index')->name('user.list');
        Route::post('/user/show', 'show')->name('user.show');
        Route::post('/user/store', 'store')->name('user.store');
        Route::post('/user/edit', 'edit')->name('user.edit');
        Route::post('/user/update', 'update')->name('user.update');
        Route::post('/user/destroy', 'destroy')->name('user.destroy');
    });
    Route::controller(Permissions::class)->group(function () {
        Route::get('/user/permissions', 'index')->name('users.permissions.index');
        Route::post('/user/permissions/show', 'show')->name('user.permission.show');
        Route::post('/user/permissions/store', 'store')->name('user.permission.store');
        Route::post('/user/permissions/edit', 'edit')->name('user.permission.edit');
        Route::post('/user/permissions/update', 'update')->name('user.permission.update');
        Route::post('/user/permissions/destroy', 'destroy')->name('user.permission.destroy');
    });
    Route::controller(Pages::class)->group(function () {
        Route::get('/pages/list', 'index')->name('pages.list');
        Route::post('/pages/show', 'show')->name('pages.show');
        Route::get('/pages/create', 'create')->name('pages.create');
        Route::post('/pages/destroy', 'destroy')->name('pages.destroy');
        Route::post('/pages/store', 'save')->name('pages.store');
        Route::get('/pages/edit/{id}', 'edit')->name('pages.edit');
        Route::post('/pages/update', 'update')->name('pages.update');
        Route::post('/upload-image', 'upload')->name('pages.upload');


        Route::get('/preview/{slug}',  'preview')->name('preview');
        Route::get('/live-preview/{id}', 'livePreview')->name('live-preview');


        Route::post('/upload-image', 'uploadImage')->name('pages.uploadImage');
        // In web.php
        Route::post('/pages/check-slug', 'checkSlug')->name('pages.checkSlug');
        Route::get('/pages/section/{id}', 'section')->name('pages.section');
        Route::post('/pages/section/show/{id}', 'sectionShow')->name('pages.section.show');
        Route::get('/pages/section/create/{id}', 'sectionCreate')->name('pages.section.create');
        Route::post('/pages/section/store/{id}', 'sectionStore')->name('pages.section.store');
        Route::get('/pages/section/edit/{section_id}/{id}', 'sectionEdit')->name('pages.section.edit');
        Route::post('/pages/section/destroy', 'sectionDestroy')->name('pages.section.destroy');
    });
    Route::prefix('menus')->name('menus.')->group(function () {
        // Main Menu Routes
        Route::get('/menu', [Menus::class, 'index'])->name('index');

        // Submenu Routes
        Route::get('/submenu/{menuId}', [Menus::class, 'submenu'])->name('submenu');

        // Subchild Routes
        Route::get('/subchild/{menuId}/{submenuId}', [Menus::class, 'subchild'])->name('subchild');

        // Create/Edit Form
        Route::get('/create', [Menus::class, 'create'])->name('create');
        Route::get('/edit/{id}', [Menus::class, 'edit'])->name('edit');

        // Unified Store/Update
        Route::post('/store-update', [Menus::class, 'storeUpdate'])->name('store-update');

        // Delete
        Route::post('/destroy', [Menus::class, 'destroy'])->name('destroy');
    });
    Route::resource('scholarship', Scholarship::class)->except(['store', 'update']);
    Route::post('scholarship', [Scholarship::class, 'save'])->name('admin.scholarship.store');
    Route::put('scholarship/{id}', [Scholarship::class, 'save'])->name('admin.scholarship.update');


    Route::get('/scholarship', [Scholarship::class, 'index'])->name('admin.scholarship.index');
    Route::get('/scholarship/create', [Scholarship::class, 'create'])->name('admin.scholarship.create');
    //     Route::post('/scholarship', [Scholarship::class, 'save'])->name('admin.scholarship.store');
    Route::get('/scholarship/{id}', [Scholarship::class, 'show'])->name('admin.scholarship.show');
    Route::get('/scholarship/{id}/edit', [Scholarship::class, 'edit'])->name('admin.scholarship.edit');
    Route::put('/scholarship/{id}', [Scholarship::class, 'save'])->name('admin.scholarship.update');
    Route::delete('/scholarship/{scholarship}', [Scholarship::class, 'destroy'])->name('admin.scholarship.destroy');

    Route::prefix('boards')->name('boards.')->group(function () {
        Route::get('/', [Boards::class, 'index'])->name('index');
        Route::get('/create', [Boards::class, 'create'])->name('create');
        Route::post('/store', [Boards::class, 'store'])->name('store');
        Route::get('/{board}/edit', [Boards::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [Boards::class, 'update'])->name('update'); // Changed to PUT
        Route::delete('/delete/{board}', [Boards::class, 'destroy'])->name('destroy');
        Route::get('/{board}', [Boards::class, 'show'])->name('show');
        Route::patch('/{board}/toggle-status', [Boards::class, 'toggleStatus'])->name('toggle-status');
    });

    Route::prefix('levels')->name('levels.')->group(function () {
        Route::get('/', [Levels::class, 'index'])->name('index');
        Route::get('/create', [Levels::class, 'create'])->name('create');
        Route::post('/store', [Levels::class, 'store'])->name('store');
        Route::get('/{level}/edit', [Levels::class, 'edit'])->name('edit');
        Route::put('/{level}', [Levels::class, 'update'])->name('update');
        Route::delete('/{level}', [Levels::class, 'destroy'])->name('destroy');
    });
    Route::prefix('subjects')->name('subjects.')->group(function () {
        Route::get('/', [Subjects::class, 'index'])->name('index');
        Route::get('/create', [Subjects::class, 'create'])->name('create');
        Route::post('/store', [Subjects::class, 'store'])->name('store');
        Route::get('/{subject}/edit', [Subjects::class, 'edit'])->name('edit');
        Route::put('/{subject}', [Subjects::class, 'update'])->name('update');
        Route::delete('/{subject}', [Subjects::class, 'destroy'])->name('destroy');
         Route::post('/levels/get-by-boards', [Subjects::class, 'getLevelsByBoards'])
         ->name('levels.get-by-boards');
    });
    Route::prefix('custom-queries')->name('custom-queries.')->group(function () {
        Route::get('/', [CustomQuery::class, 'index'])->name('index');
        Route::get('/create', [CustomQuery::class, 'create'])->name('create');
        Route::post('/store', [CustomQuery::class, 'store'])->name('store');
        Route::get('/{customQuery}', [CustomQuery::class, 'show'])->name('show');
        Route::get('/{customQuery}/edit', [CustomQuery::class, 'edit'])->name('edit');
        Route::put('/{customQuery}', [CustomQuery::class, 'update'])->name('update');
        Route::delete('/{customQuery}', [CustomQuery::class, 'destroy'])->name('destroy');
    });

    Route::get('/submissions/form', [Submissions::class, 'index'])->name('admin.submissions.form');
    Route::get('/take-exam', [TakeExam::class, 'index'])->name('admin.take-exam');

    Route::get('/menus', [Menus::class, 'index'])->name('admin.menus');
    Route::post('/menus/menuDelete', [Menus::class, 'menuDelete'])->name('admin.menus.menuDelete');
    Route::post('/menu/childDelete', [Menus::class, 'SubmenuDelete'])->name('admin.menus.childdelete');
    Route::post('/menu/subchildDelete', [Menus::class, 'SubChildmenuDelete'])->name('admin.menus.subchilddelete');
    // case study

    Route::prefix('case-studies')->name('case-studies.')->group(function () {
        Route::get('/', [CaseStudys::class, 'index'])->name('index');
        Route::get('/create', [CaseStudys::class, 'create'])->name('create');
        Route::post('/store', [CaseStudys::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CaseStudys::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [CaseStudys::class, 'update'])->name('update');
        Route::post('/destroy', [CaseStudys::class, 'destroy'])->name('destroy');
    });

    Route::prefix('careers')->name('careers.')->group(function () {
        Route::get('/', [Career::class, 'index'])->name('index');
        Route::get('/show/{id}', [Career::class, 'show'])->name('show');
        Route::post('/destroy', [Career::class, 'destroy'])->name('destroy');
        Route::get('/download/{id}', [Career::class, 'downloadResume'])->name('download');
    });
});


require __DIR__ . '/auth.php';

Route::get('/', [Home::class, 'index'])->name('home');
Route::get('/about', [About::class, 'index'])->name('about');
Route::post('/contact-us/{slug?}', [About::class, 'ContactRequest'])->name('contact-us');
Route::get('/subject-detail', [SubjectDetail::class, 'index'])->name('subject-detail');
// Route::get('/page/{slug}', Pages::class)->name('frontend.page');
// Route::get('/packages/{slug?}', Packages::class)->name('frontend.packages');
Route::get('{slug?}/{submenuSlug?}/{childSlug?}', [About::class, 'index'])->name('frontend.page');



// front end


// Route::get('/login', function () {
//     return view('auth.login');
// })->name('login');
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('login');


Route::post('/form/scholar/scholarship', [About::class, 'scholarships'])->name('scholarships.form');
Route::post('/form/career', [About::class, 'careers'])->name('careers.form');
Route::get('/subscription-form', [SubscriptionForm::class, 'subscribeForm'])->name('subscription-form');
Route::get('/subscription/package/{slug}/{levelslug?}', [SubscriptionForm::class, 'subscribes'])->name('subscription.package');

Route::post('/custom-query/', [About::class, 'CustomQuery'])->name('subscription.custom');
Route::get('/email/send/{email}/{token}', [SubscriptionForm::class, 'emailSend'])
    ->name('email.send');
Route::post('/resend-verification-email', [SubscriptionForm::class, 'resendVerificationEmail'])
    ->name('verification.resend');
Route::get('/email/verify/{id}/{hash}', [SubscriptionForm::class, 'emailVerify'])->name('email.verify');
Route::get('/user/email/verified/hash', [SubscriptionForm::class, 'emailVerified'])->name('email.verified');


Route::get('login/facebook/callback', [SubscriptionForm::class, 'handleFacebookCallback']);
Route::get('login/facebook/{slug?}', [SubscriptionForm::class, 'redirectToFacebook'])->name('login.facebook');

Route::get('login/google/callback', [SubscriptionForm::class, 'handleGoogleCallback']);
Route::get('login/google/{slug?}', [SubscriptionForm::class, 'redirectToGoogle'])->name('login.google');
Route::get('/page/{slug}', [Pages::class, 'index'])->name('frontend.page');
Route::get('/packages/{slug?}', [Packages::class, 'index'])->name('frontend.packages');
Route::get('{slug?}/{submenuSlug?}/{childSlug?}', [About::class, 'index'])->name('frontend.page');
Route::get('/board/{slug}', [Boards::class, 'index'])->name('frontend.board');

// Route::get('/storage/filemanager/{filename}', function ($filename) {
//     $path = storage_path('app/public/images/' . $filename);

//     if (!File::exists($path)) {
//         abort(404);
//     }

//     return Response::file($path);
// });

// Route::get('/case-study/{slug?}', CaseStudies::class)->name('frontend.case-studies');
// Route::get('/all/{slug?}', CaseStudies::class)->name('frontend.package');

// Vimeo Link Route - Temporary fix
// Route::post('/vimeo/store', [\LivewireFilemanager\Filemanager\Http\Controllers\VimeoLinkController::class, 'store'])->name('vimeo.store');
