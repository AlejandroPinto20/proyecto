<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\VolunteerAssistantController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Notifications routes
    Route::get('/notifications', [NotificationController::class, 'show'])->name('notifications.index');
    
    // Real-time notifications
    Route::get('/notifications/stream', [\App\Http\Controllers\NotificationStreamController::class, 'stream'])->name('notifications.stream');
    
    // API routes for notifications
    Route::prefix('api')->group(function () {
        Route::post('/assistant/ask', [VolunteerAssistantController::class, 'ask'])->name('api.assistant.ask');
        Route::get('/notifications', [NotificationController::class, 'index'])->name('api.notifications');
        Route::get('/notifications/latest', [\App\Http\Controllers\NotificationStreamController::class, 'latest'])->name('api.notifications.latest');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('api.notifications.read');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('api.notifications.mark-all-read');
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('api.notifications.unread-count');
    });

    Route::get('/mi-perfil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/deactivation-request', [ProfileController::class, 'requestDeactivation'])->name('profile.request-deactivation');


    // Activities routes (unified with calendar)
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/calendar-data', [ActivityController::class, 'getActivities'])->name('activities.calendar-data');
    Route::get('/activities/history', [ActivityController::class, 'history'])->name('activities.history');
    Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create')->middleware('admin');
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store')->middleware('admin');
    Route::get('/activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');
    Route::get('/activities/{activity}/edit', [ActivityController::class, 'edit'])->name('activities.edit')->middleware('admin');
    Route::put('/activities/{activity}', [ActivityController::class, 'update'])->name('activities.update')->middleware('admin');
    Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy')->middleware('admin');
    Route::post('/activities/{activity}/join', [ActivityController::class, 'join'])->name('activities.join');
    Route::post('/activities/{activity}/leave', [ActivityController::class, 'leave'])->name('activities.leave');
    Route::post('/activities/{activity}/complete/{user?}', [ActivityController::class, 'completeActivity'])->name('activities.complete')->middleware('admin');
    Route::resource('donations', DonationController::class);
    Route::resource('certificates', CertificateController::class);
    Route::get('certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
    Route::get('validate-certificate', [CertificateController::class, 'showValidateForm'])->name('certificates.validate-form');
    Route::post('validate-certificate', [CertificateController::class, 'validateCertificate'])->name('certificates.validate');

    Route::resource('rankings', RankingController::class);
    Route::post('rankings/update-all', [RankingController::class, 'updateAll'])->name('rankings.update-all');

    // Skills routes
    Route::get('/skills/setup', [SkillController::class, 'setup'])->name('skills.setup');
    Route::post('/skills/setup', [SkillController::class, 'storeSetup'])->name('skills.store-setup');
    Route::get('/skills/profile', [SkillController::class, 'profile'])->name('skills.profile');
    Route::get('/skills/recommendations', [SkillController::class, 'recommendations'])->name('skills.recommendations');
    Route::get('/api/skills/recommendations', [SkillController::class, 'apiRecommendations'])->name('api.skills.recommendations');
    
    // Markov routes
    Route::get('/markov/insights', [\App\Http\Controllers\MarkovController::class, 'insights'])->name('markov.insights');
    Route::get('/api/markov/recommendations', [\App\Http\Controllers\MarkovController::class, 'apiRecommendations'])->name('api.markov.recommendations');
    Route::post('/markov/refresh', [\App\Http\Controllers\MarkovController::class, 'refreshRecommendations'])->name('markov.refresh');
    
    // Achievement routes
    Route::get('/achievements', [\App\Http\Controllers\AchievementController::class, 'index'])->name('achievements.index');

    // Calendar routes (redirect to activities)
    Route::get('/calendar', function() { return redirect()->route('activities.index', ['view' => 'calendar']); })->name('calendar.index');
    Route::get('/calendar/activities/{activity}', [ActivityController::class, 'show'])->name('calendar.show');
    Route::post('/calendar/activities/{activity}/join', [ActivityController::class, 'join'])->name('calendar.join');
    Route::post('/calendar/activities/{activity}/leave', [ActivityController::class, 'leave'])->name('calendar.leave');
    
    // Participation routes
    Route::post('/activities/{activity}/join', [\App\Http\Controllers\ParticipationController::class, 'join'])->name('activities.join');
    Route::post('/activities/{activity}/leave', [\App\Http\Controllers\ParticipationController::class, 'leave'])->name('activities.leave');
    Route::post('/activities/{activity}/complete/{user?}', [\App\Http\Controllers\ParticipationController::class, 'markCompleted'])->name('activities.complete');
    
    // Note: Participation routes are now handled by ParticipationController

    // Rutas administrativas
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');

        Route::get('/activities', [AdminController::class, 'activities'])->name('activities');
        Route::get('/activities/pending-participations', [AdminController::class, 'pendingParticipations'])->name('activities.pending-participations');
        Route::post('/activities/{activity}/approve', [AdminController::class, 'approveActivity'])->name('activities.approve');
        Route::post('/activities/{activity}/reject', [AdminController::class, 'rejectActivity'])->name('activities.reject');
        Route::post('/activities/{activity}/complete', [AdminController::class, 'completeActivity'])->name('activities.complete');
        Route::post('/activities/{activity}/cancel', [AdminController::class, 'cancelActivity'])->name('activities.cancel');
        Route::get('/activities/{activity}/edit', [AdminController::class, 'editActivity'])->name('activities.edit');
        Route::put('/activities/{activity}', [AdminController::class, 'updateActivity'])->name('activities.update');

        Route::get('/certificates', [AdminController::class, 'certificates'])->name('certificates');
        Route::get('/certificates/create', [AdminController::class, 'createCertificate'])->name('certificates.create');
        Route::post('/certificates', [AdminController::class, 'storeCertificate'])->name('certificates.store');
        Route::post('/certificates/bulk', [AdminController::class, 'storeBulkCertificates'])->name('certificates.store-bulk');
        Route::get('/users/{user}/completed-activities', [CertificateController::class, 'getUserCompletedActivities'])->name('users.completed-activities');

        Route::get('/donations', [AdminController::class, 'donations'])->name('donations');
        Route::post('/donations/{donation}/approve', [AdminController::class, 'approveDonation'])->name('donations.approve');
        Route::post('/donations/{donation}/confirm', [AdminController::class, 'confirmDonation'])->name('donations.confirm');
        Route::post('/donations/{donation}/reject', [AdminController::class, 'rejectDonation'])->name('donations.reject');

        Route::resource('skills', SkillController::class);

        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
        
        Route::get('/notification-logs', [AdminController::class, 'notificationLogs'])->name('notification-logs');
        Route::get('/system-logs', [AdminController::class, 'systemLogs'])->name('system-logs');
        Route::get('/access-logs', [AdminController::class, 'accessLogs'])->name('access-logs');
        Route::get('/logs', [\App\Http\Controllers\Admin\LogController::class, 'index'])->name('logs');

        // Markov Chains routes
        Route::get('/markov-chains', [AdminController::class, 'markovChains'])->name('markov.index');
        Route::get('/markov-chains/user-evolution', [AdminController::class, 'getUserSkillEvolution'])->name('markov.user-evolution');
        Route::get('/markov-chains/chart-data', [AdminController::class, 'getMarkovChartData'])->name('markov.chart-data');
        
        Route::post('/activities/{activity}/users/{user}/approve', [AdminController::class, 'approveParticipation'])->name('activities.approve-participation');
        Route::post('/activities/{activity}/users/{user}/reject', [AdminController::class, 'rejectParticipation'])->name('activities.reject-participation');
        Route::post('/activities/{activity}/users/{user}/complete', [AdminController::class, 'completeParticipation'])->name('activities.complete-participation');
        Route::post('/activities/{activity}/bulk-complete', [AdminController::class, 'bulkCompleteActivity'])->name('activities.bulk-complete');
        Route::get('/activities/{activity}/participants', [AdminController::class, 'showParticipants'])->name('activities.participants');
        Route::post('/activities/{activity}/users/{user}/complete', [AdminController::class, 'markParticipantCompleted'])->name('activities.mark-completed');
    });
});

require __DIR__.'/auth.php';
