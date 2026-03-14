<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <title>@yield('title', 'Admin') | Carpir</title>
    <style>
        :root { --accent-red: #ff6b6b; --accent-blue: #0066cc; --dark-navy: #0a1628; --navy: #152238; --ff-sansation: 'Sansation', sans-serif; --ff-montserrat: 'Montserrat', sans-serif; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: var(--ff-montserrat); background: transparent; }
        .form-modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.7);
            display: flex; justify-content: center; align-items: center;
            z-index: 10000; padding: 2rem; overflow-y: auto;
        }
        .form-modal-content {
            background: white; border-radius: 20px;
            width: 100%; max-width: 900px; max-height: 90vh; overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: modalSlideUp 0.3s ease;
        }
        @keyframes modalSlideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-modal-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 2rem 2rem 1rem; border-bottom: 2px solid #f0f0f0;
        }
        .form-modal-header h2 { font-family: var(--ff-sansation); font-size: 1.75rem; color: var(--accent-red); margin: 0; }
        .form-modal-close {
            display: inline-flex; align-items: center; justify-content: center;
            width: 40px; height: 40px; border-radius: 50%;
            background: none; border: none; font-size: 2rem; color: #999;
            text-decoration: none; cursor: pointer; transition: all 0.3s ease;
        }
        .form-modal-close:hover { background: #f5f5f5; color: #333; }
        .admin-form-body { padding: 2rem; }
        .admin-form-body .form-group { margin-bottom: 1.25rem; }
        .admin-form-body .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem; }
        .admin-form-body .form-group input,
        .admin-form-body .form-group select,
        .admin-form-body .form-group textarea {
            width: 100%; max-width: 100%; padding: 0.875rem;
            border: 2px solid #e0e0e0; border-radius: 10px;
            font-family: var(--ff-montserrat); font-size: 1rem;
            background: #fff; color: #2d2d2d;
        }
        .admin-form-body .form-group input:focus,
        .admin-form-body .form-group select:focus,
        .admin-form-body .form-group textarea:focus {
            outline: none; border-color: var(--accent-red);
            box-shadow: 0 0 0 3px rgba(255,107,107,0.1);
        }
        .admin-form-body .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .admin-form-body .form-actions {
            display: flex; justify-content: flex-end; gap: 1rem;
            padding-top: 1rem; margin-top: 1rem; border-top: 2px solid #f0f0f0;
        }
        .admin-form-body .cancel-button {
            padding: 0.875rem 2rem; border: none; border-radius: 10px;
            background: #e0e0e0; color: #333; font-family: var(--ff-montserrat);
            font-size: 1rem; font-weight: 700; cursor: pointer; text-decoration: none;
            display: inline-block; transition: all 0.3s ease;
        }
        .admin-form-body .cancel-button:hover { background: #d0d0d0; }
        .admin-form-body .save-button {
            padding: 0.875rem 2rem; border: none; border-radius: 10px;
            background: linear-gradient(135deg, var(--accent-red), #ff4d2e);
            color: white; font-family: var(--ff-montserrat);
            font-size: 1rem; font-weight: 700; cursor: pointer;
            box-shadow: 0 4px 15px rgba(255,107,107,0.4);
            transition: all 0.3s ease;
        }
        .admin-form-body .save-button:hover:not(:disabled) {
            transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,107,107,0.5);
        }
        .admin-form-body .save-button:disabled { opacity: 0.6; cursor: not-allowed; }
        .admin-form-body .form-error {
            background: #fee; color: var(--accent-red); padding: 1rem;
            border-radius: 10px; border: 1px solid #fcc; margin-bottom: 1rem;
        }
        trix-editor { border: 2px solid #e0e0e0; border-radius: 10px; padding: 0.875rem; min-height: 120px; background: #fff; color: #2d2d2d; }
        trix-toolbar .trix-button-group { border-color: #dee2e6; background: #f8f9fa; }
        @media (max-width: 600px) { .admin-form-body .form-row { grid-template-columns: 1fr; } }
    </style>
    @stack('styles')
</head>
<body>
    <div class="form-modal-overlay">
        <div class="form-modal-content">
            <div class="form-modal-header">
                <h2>@yield('modal_title')</h2>
                <a href="{{ route('admin.index') }}" class="form-modal-close" aria-label="Cerrar">×</a>
            </div>
            <div class="admin-form-body">
                @yield('content')
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
