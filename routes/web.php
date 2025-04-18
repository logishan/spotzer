<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardsController;
use App\Http\Controllers\AdvanceduiController;
use App\Http\Controllers\ChartsController;
use App\Http\Controllers\ComponentsController;
use App\Http\Controllers\ElementsController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\IconsController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\TablesController;
use App\Http\Controllers\WidgetsController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskTypeController;
use App\Http\Controllers\ComplexityLevelController;
use App\Http\Controllers\TaskTypeComplexityController;
use App\Http\Controllers\TaskGroupController;
use App\Http\Controllers\ShiftScheduleController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

// Public routes - No authentication required

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('login', [PagesController::class, 'login'])->name('login');
    Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('login.authenticate');
    Route::get('register', [PagesController::class, 'register'])->name('register');
    Route::post('register', [PagesController::class, 'handleRegister'])->name('register.store');
    Route::get('forgot-password', [PagesController::class, 'forgot_password'])->name('password.request');
});

// Logout route - available to authenticated users
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Social Login Routes
Route::get('auth/{provider}', [App\Http\Controllers\Auth\SocialiteController::class, 'redirectToProvider'])->name('social.login');
Route::get('auth/{provider}/callback', [App\Http\Controllers\Auth\SocialiteController::class, 'handleProviderCallback'])->name('social.callback');

// Error Pages - Publicly Accessible
Route::get('error400', [PagesController::class, 'error400']);
Route::get('error401', [PagesController::class, 'error401']);
Route::get('error403', [PagesController::class, 'error403']);
Route::get('error404', [PagesController::class, 'error404']);
Route::get('error500', [PagesController::class, 'error500']);
Route::get('error503', [PagesController::class, 'error503']);

// Debug route - Only in development
if (app()->environment('local')) {
    // Old debug implementation - commenting out to avoid route conflict
    /*
    Route::get('debug-task-complexities', function (Request $request) {
        $taskTypeId = $request->input('task_type_id');
        $taskGroupId = $request->input('task_group_id');
        $shouldFix = $request->input('fix', false);
        
        if (!$taskTypeId || !$taskGroupId) {
            return [
                'error' => 'Please provide task_type_id and task_group_id parameters',
                'example' => url('debug-task-complexities?task_type_id=1&task_group_id=1')
            ];
        }
        
        $taskType = \App\Models\TaskType::find($taskTypeId);
        $taskGroup = \App\Models\TaskGroup::find($taskGroupId);
        
        if (!$taskType || !$taskGroup) {
            return [
                'error' => 'Task type or task group not found',
                'task_type_exists' => (bool)$taskType,
                'task_group_exists' => (bool)$taskGroup
            ];
        }
        
        $complexities = \App\Models\TaskTypeComplexity::where('task_type_id', $taskTypeId)
            ->where('task_group_id', $taskGroupId)
            ->where('is_active', true)
            ->with(['complexityLevel', 'taskType'])
            ->get();
            
        // Get available complexity levels for this task group
        $availableComplexities = \App\Models\ComplexityLevel::where(function($query) use ($taskGroupId) {
            $query->where('task_group_id', $taskGroupId)
                ->orWhereNull('task_group_id');
        })->get();
        
        $result = [
            'task_type' => $taskType->toArray(),
            'task_group' => $taskGroup->toArray(),
            'existing_complexities' => $complexities->toArray(),
            'existing_count' => $complexities->count(),
            'available_complexities' => $availableComplexities->toArray(),
            'available_count' => $availableComplexities->count()
        ];
        
        // If fix=true and there are no existing complexities but available ones exist
        if ($shouldFix && $complexities->isEmpty() && $availableComplexities->isNotEmpty()) {
            $created = [];
            
            foreach ($availableComplexities as $complexity) {
                $ttc = \App\Models\TaskTypeComplexity::create([
                    'task_type_id' => $taskTypeId,
                    'complexity_level_id' => $complexity->id,
                    'task_group_id' => $taskGroupId,
                    'allocated_minutes' => 30, // Default value
                    'is_active' => true,
                    'created_by' => auth()->id() ?? 1,
                    'updated_by' => auth()->id() ?? 1
                ]);
                
                $created[] = $ttc->toArray();
            }
            
            $result['fixed'] = true;
            $result['created_complexities'] = $created;
            $result['message'] = 'Created ' . count($created) . ' task type complexity entries';
        } elseif ($shouldFix) {
            $result['fixed'] = false;
            $result['message'] = 'No fix needed or not possible';
        }
        
        return $result;
    });
    */
}

// Debug routes - only available in local environment
if (app()->environment('local')) {
    Route::get('debug-task-complexities', [App\Http\Controllers\TaskController::class, 'debugTaskTypeComplexities'])
        ->name('debug.task-complexities');
}

// Root route - direct to controller for homepage
Route::get('/', function () {
    if (Auth::check()) {
        // Directly use the controller instead of redirecting
        return app(DashboardsController::class)->index();
    }
    return redirect()->route('login');
})->name('home');

// ============================================
// ALL ROUTES BELOW REQUIRE AUTHENTICATION
// ============================================
Route::middleware(['auth'])->group(function () {
    // Dashboard Routes
    Route::get('index', [DashboardsController::class, 'index'])->name('index');
    Route::get('index2', [DashboardsController::class, 'index2']);
    Route::get('index3', [DashboardsController::class, 'index3']);
    Route::get('index4', [DashboardsController::class, 'index4']);
    Route::get('index5', [DashboardsController::class, 'index5']);

    // Widgets
    Route::get('widgets', [WidgetsController::class, 'widgets']);

    // Maps
    Route::get('maps', [MapsController::class, 'maps']);
    Route::get('maps1', [MapsController::class, 'maps1']);
    Route::get('maps2', [MapsController::class, 'maps2']);

    // Components
    Route::get('cards', [ComponentsController::class, 'cards']);
    Route::get('calendar', [ComponentsController::class, 'calendar']);
    Route::get('calendar2', [ComponentsController::class, 'calendar2']);
    Route::get('chat', [ComponentsController::class, 'chat']);
    Route::get('notify', [ComponentsController::class, 'notify']);
    Route::get('sweetalert', [ComponentsController::class, 'sweetalert']);
    Route::get('rangeslider', [ComponentsController::class, 'rangeslider']);
    Route::get('scroll', [ComponentsController::class, 'scroll']);
    Route::get('loaders', [ComponentsController::class, 'loaders']);
    Route::get('counters', [ComponentsController::class, 'counters']);
    Route::get('rating', [ComponentsController::class, 'rating']);
    Route::get('timeline', [ComponentsController::class, 'timeline']);
    Route::get('treeview', [ComponentsController::class, 'treeview']);

    // Elements
    Route::get('alerts', [ElementsController::class, 'alerts']);
    Route::get('buttons', [ElementsController::class, 'buttons']);
    Route::get('colors', [ElementsController::class, 'colors']);
    Route::get('avatarsquare', [ElementsController::class, 'avatarsquare']);
    Route::get('avatar-round', [ElementsController::class, 'avatar_round']);
    Route::get('avatar-radius', [ElementsController::class, 'avatar_radius']);
    Route::get('dropdown', [ElementsController::class, 'dropdown']);
    Route::get('list', [ElementsController::class, 'list']);
    Route::get('tags', [ElementsController::class, 'tags']);
    Route::get('toast', [ElementsController::class, 'toast']);
    Route::get('pagination', [ElementsController::class, 'pagination']);
    Route::get('navigation', [ElementsController::class, 'navigation']);
    Route::get('typography', [ElementsController::class, 'typography']);
    Route::get('breadcrumbs', [ElementsController::class, 'breadcrumbs']);
    Route::get('badge', [ElementsController::class, 'badge']);
    Route::get('panels', [ElementsController::class, 'panels']);
    Route::get('thumbnails', [ElementsController::class, 'thumbnails']);

    // Advanced UI
    Route::get('mediaobject', [AdvanceduiController::class, 'mediaobject']);
    Route::get('accordion', [AdvanceduiController::class, 'accordion']);
    Route::get('tabs', [AdvanceduiController::class, 'tabs']);
    Route::get('chart', [AdvanceduiController::class, 'chart']);
    Route::get('modal', [AdvanceduiController::class, 'modal']);
    Route::get('tooltipandpopover', [AdvanceduiController::class, 'tooltipandpopover']);
    Route::get('progress', [AdvanceduiController::class, 'progress']);
    Route::get('carousel', [AdvanceduiController::class, 'carousel']);
    Route::get('footers', [AdvanceduiController::class, 'footers']);
    Route::get('users-list', [AdvanceduiController::class, 'users_list']);
    Route::get('search', [AdvanceduiController::class, 'search']);
    Route::get('crypto-currencies', [AdvanceduiController::class, 'crypto_currencies']);

    // Pages Routes
    Route::get('lockscreen', [PagesController::class, 'lockscreen']);
    Route::get('file-manager', [PagesController::class, 'file_manager']);
    Route::get('filemanager-list', [PagesController::class, 'filemanager_list']);
    Route::get('filemanager-details', [PagesController::class, 'filemanager_details']);
    Route::get('file-attachments', [PagesController::class, 'file_attachments']);
    Route::get('shop', [PagesController::class, 'shop']);
    Route::get('shop-description', [PagesController::class, 'shop_description']);
    Route::get('cart', [PagesController::class, 'cart']);
    Route::get('add-product', [PagesController::class, 'add_product']);
    Route::get('wishlist', [PagesController::class, 'wishlist']);
    Route::get('checkout', [PagesController::class, 'checkout']);
    Route::get('blog', [PagesController::class, 'blog']);
    Route::get('blog-details', [PagesController::class, 'blog_details']);
    Route::get('blog-post', [PagesController::class, 'blog_post']);
    Route::get('profile', [PagesController::class, 'profile']);
    Route::get('editprofile', [PagesController::class, 'editprofile']);
    Route::get('email', [PagesController::class, 'email']);
    Route::get('emailservices', [PagesController::class, 'emailservices']);
    Route::get('mail-read', [PagesController::class, 'mail_read']);
    Route::get('gallery', [PagesController::class, 'gallery']);
    Route::get('about', [PagesController::class, 'about']);
    Route::get('services', [PagesController::class, 'services']);
    Route::get('faq', [PagesController::class, 'faq']);
    Route::get('terms', [PagesController::class, 'terms']);
    Route::get('invoice', [PagesController::class, 'invoice']);
    Route::get('notify-list', [PagesController::class, 'notify_list']);
    Route::get('pricing', [PagesController::class, 'pricing']);
    Route::get('switcher', [PagesController::class, 'switcher']);
    Route::get('emptypage', [PagesController::class, 'emptypage']);
    Route::get('construction', [PagesController::class, 'construction']);

    // Forms
    Route::get('form-elements', [FormsController::class, 'form_elements']);
    Route::get('form-editor', [FormsController::class, 'form_editor']);
    Route::get('form-wizard', [FormsController::class, 'form_wizard']);
    Route::get('form-validation', [FormsController::class, 'form_validation']);
    Route::get('form-layouts', [FormsController::class, 'form_layouts']);

    // Icons
    Route::get('icons', [IconsController::class, 'icons']);
    Route::get('icons2', [IconsController::class, 'icons2']);
    Route::get('icons3', [IconsController::class, 'icons3']);
    Route::get('icons4', [IconsController::class, 'icons4']);
    Route::get('icons5', [IconsController::class, 'icons5']);
    Route::get('icons6', [IconsController::class, 'icons6']);
    Route::get('icons7', [IconsController::class, 'icons7']);
    Route::get('icons8', [IconsController::class, 'icons8']);
    Route::get('icons9', [IconsController::class, 'icons9']);
    Route::get('icons10', [IconsController::class, 'icons10']);
    Route::get('icons11', [IconsController::class, 'icons11']);

    // Charts
    Route::get('chart-chartist', [ChartsController::class, 'chart_chartist']);
    Route::get('chart-flot', [ChartsController::class, 'chart_flot']);
    Route::get('chart-echart', [ChartsController::class, 'chart_echart']);
    Route::get('chart-morris', [ChartsController::class, 'chart_morris']);
    Route::get('charts', [ChartsController::class, 'charts']);
    Route::get('chart-line', [ChartsController::class, 'chart_line']);
    Route::get('chart-donut', [ChartsController::class, 'chart_donut']);
    Route::get('chart-pie', [ChartsController::class, 'chart_pie']);

    // Tables
    Route::get('tables', [TablesController::class, 'tables']);
    Route::get('datatable', [TablesController::class, 'datatable']);
    Route::get('edit-table', [TablesController::class, 'edit_table']);

    // Role Management routes
    Route::resource('roles', RoleController::class);

    // User Management routes
    Route::prefix('users')->as('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('create');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
    });

    // Department routes
    Route::resource('departments', DepartmentController::class);

    // Task Types Management
    Route::resource('task-types', TaskTypeController::class);

    // Complexity Levels Management
    Route::resource('task-complexity', ComplexityLevelController::class);

    // Task Type Complexities Management
    Route::resource('task-type-complexities', TaskTypeComplexityController::class);

    // Task Groups Management
    Route::resource('task-groups', TaskGroupController::class);
    Route::post('task-groups/{taskGroup}/users/{user}/permissions', [TaskGroupController::class, 'updateUserPermissions'])
        ->name('task-groups.update-user-permissions');

    // Shift Schedules Management
    Route::resource('shift-schedules', ShiftScheduleController::class);

    // Attendance Management
    // Specific routes must come BEFORE the resource controller
    Route::get('attendances/sheet', [AttendanceController::class, 'sheet'])->name('attendances.sheet');
    Route::post('check-in', [AttendanceController::class, 'checkIn'])->name('attendances.check-in');
    Route::post('check-out', [AttendanceController::class, 'checkOut'])->name('attendances.check-out');
    
    // Resource controller (with all CRUD routes) comes last
    Route::resource('attendances', AttendanceController::class);
    
    // Task Management
    Route::get('tasks/kanban/{taskGroup?}', [App\Http\Controllers\TaskController::class, 'kanban'])->name('tasks.kanban');
    Route::post('tasks/{task}/status', [App\Http\Controllers\TaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::resource('tasks', App\Http\Controllers\TaskController::class);
    
    // Form Fields Management
    Route::resource('form-fields', App\Http\Controllers\FormFieldController::class);
});