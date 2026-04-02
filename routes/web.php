<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FechaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\UploadController;
use App\Models\Noticia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/noticias', [NoticiaController::class, 'index'])->name('noticias.index');
Route::permanentRedirect('/noticias.html', '/noticias');
Route::get('/noticias/{id}', [NoticiaController::class, 'show'])->name('noticias.show');
Route::get('/fechas', [FechaController::class, 'index'])->name('fechas.index');
Route::get('/escuchanos', [HomeController::class, 'escuchanos'])->name('escuchanos.index');
Route::get('/sitemap.xml', function () {
    $urls = [
        [
            'loc' => route('home'),
            'lastmod' => now()->toDateString(),
            'changefreq' => 'weekly',
            'priority' => '1.0',
        ],
        [
            'loc' => route('noticias.index'),
            'lastmod' => now()->toDateString(),
            'changefreq' => 'daily',
            'priority' => '0.9',
        ],
        [
            'loc' => route('fechas.index'),
            'lastmod' => now()->toDateString(),
            'changefreq' => 'daily',
            'priority' => '0.8',
        ],
        [
            'loc' => route('escuchanos.index'),
            'lastmod' => now()->toDateString(),
            'changefreq' => 'weekly',
            'priority' => '0.7',
        ],
    ];

    $noticias = Noticia::select(['id', 'updated_at', 'fecha'])->orderByFechaNewestFirst()->get();
    foreach ($noticias as $noticia) {
        $urls[] = [
            'loc' => route('noticias.show', $noticia->id),
            'lastmod' => ($noticia->updated_at instanceof Carbon ? $noticia->updated_at : now())->toDateString(),
            'changefreq' => 'monthly',
            'priority' => '0.6',
        ];
    }

    $xml = view('sitemap', ['urls' => $urls])->render();
    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::post('/contacto', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contacto.store');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/upload', [UploadController::class, 'store'])->middleware('auth')->name('upload.store');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/noticias/crear', [AdminController::class, 'createNoticia'])->name('noticias.create');
    Route::get('/noticias/{id}/editar', [AdminController::class, 'editNoticia'])->name('noticias.edit');
    Route::post('/noticias', [AdminController::class, 'storeNoticia'])->name('noticias.store');
    Route::put('/noticias/{id}', [AdminController::class, 'updateNoticia'])->name('noticias.update');
    Route::delete('/noticias/{id}', [AdminController::class, 'destroyNoticia'])->name('noticias.destroy');
    Route::get('/integrantes/crear', [AdminController::class, 'createIntegrante'])->name('integrantes.create');
    Route::get('/integrantes/{id}/editar', [AdminController::class, 'editIntegrante'])->name('integrantes.edit');
    Route::post('/integrantes', [AdminController::class, 'storeIntegrante'])->name('integrantes.store');
    Route::put('/integrantes/{id}', [AdminController::class, 'updateIntegrante'])->name('integrantes.update');
    Route::post('/integrantes/{id}/toggle', [AdminController::class, 'toggleIntegrante'])->name('integrantes.toggle');
    Route::put('/nosotros', [AdminController::class, 'updateNosotros'])->name('nosotros.update');
    Route::get('/escuchanos/crear', [AdminController::class, 'createEscuchanos'])->name('escuchanos.create');
    Route::get('/escuchanos/{id}/editar', [AdminController::class, 'editEscuchanos'])->name('escuchanos.edit');
    Route::post('/escuchanos', [AdminController::class, 'storeEscuchanos'])->name('escuchanos.store');
    Route::put('/escuchanos/{id}', [AdminController::class, 'updateEscuchanos'])->name('escuchanos.update');
    Route::delete('/escuchanos/{id}', [AdminController::class, 'destroyEscuchanos'])->name('escuchanos.destroy');
    Route::get('/fechas/crear', [AdminController::class, 'createFecha'])->name('fechas.create');
    Route::get('/fechas/{id}/editar', [AdminController::class, 'editFecha'])->name('fechas.edit');
    Route::post('/fechas', [AdminController::class, 'storeFecha'])->name('fechas.store');
    Route::put('/fechas/{id}', [AdminController::class, 'updateFecha'])->name('fechas.update');
    Route::delete('/fechas/{id}', [AdminController::class, 'destroyFecha'])->name('fechas.destroy');
});
