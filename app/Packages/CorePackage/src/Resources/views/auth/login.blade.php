<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — {{ config('app.name', 'School Management') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; background: #f1f5f9; min-height: 100vh; display: flex; align-items: center; justify-content: center; }

        .login-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 2.5rem;
            width: 100%;
            max-width: 24rem;
            box-shadow: 0 4px 24px rgba(0,0,0,.06);
        }

        .form-label { display: block; font-size: .8125rem; font-weight: 500; color: #374151; margin-bottom: .375rem; }

        .form-input {
            width: 100%; padding: .625rem .875rem;
            border: 1px solid #d1d5db; border-radius: .5rem;
            font-size: .875rem; color: #1e293b; background: #fff;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }
        .form-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
        .form-input.error { border-color: #ef4444; }

        .btn-primary {
            width: 100%; padding: .75rem 1.5rem;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff; font-size: .875rem; font-weight: 600;
            border: none; border-radius: .5rem; cursor: pointer;
            transition: opacity .15s;
        }
        .btn-primary:hover { opacity: .9; }

        .error-msg { font-size: .75rem; color: #dc2626; margin-top: .375rem; }
    </style>
</head>
<body>

<div class="login-card">

    {{-- Logo --}}
    <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:2rem;">
        <div style="width:2.5rem;height:2.5rem;background:linear-gradient(135deg,#4f46e5,#6366f1);border-radius:.625rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:1.25rem;height:1.25rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <div>
            <p style="font-size:.9375rem;font-weight:700;color:#1e293b;line-height:1.2;">{{ config('app.name', 'School Management') }}</p>
            <p style="font-size:.7rem;color:#94a3b8;">Management System</p>
        </div>
    </div>

    <h1 style="font-size:1.25rem;font-weight:700;color:#1e293b;margin-bottom:.25rem;">Welcome back</h1>
    <p style="font-size:.8125rem;color:#64748b;margin-bottom:1.75rem;">Sign in to your account to continue.</p>

    @if($errors->any())
    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:.5rem;padding:.75rem 1rem;margin-bottom:1.25rem;">
        <p style="font-size:.8125rem;color:#b91c1c;font-weight:500;">{{ $errors->first() }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div style="margin-bottom:1.125rem;">
            <label class="form-label" for="email">Email address</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
                required
                class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                placeholder="admin@school.com"
            >
        </div>

        <div style="margin-bottom:1.5rem;">
            <label class="form-label" for="password">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                autocomplete="current-password"
                required
                class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                placeholder="••••••••"
            >
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
            <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;">
                <input type="checkbox" name="remember" style="width:1rem;height:1rem;accent-color:#6366f1;">
                <span style="font-size:.8125rem;color:#4b5563;">Remember me</span>
            </label>
        </div>

        <button type="submit" class="btn-primary">Sign in</button>
    </form>

    <p style="text-align:center;font-size:.75rem;color:#94a3b8;margin-top:1.5rem;">
        <a href="{{ route('home') }}" style="color:#6366f1;text-decoration:none;font-weight:500;">← Back to home</a>
    </p>
</div>

</body>
</html>
