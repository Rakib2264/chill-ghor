<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'চিল ঘর — ঘরের স্বাদ, রেস্টুরেন্টে')</title>
  <meta name="description" content="@yield('description', 'চিল ঘর রেস্টুরেন্টে অর্ডার করুন কাচ্চি বিরিয়ানি, ইলিশ, কাবাব ও ঐতিহ্যবাহী বাঙালি খাবার।')">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Tiro+Bangla:ital@0;1&display=swap" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary:    { DEFAULT: '#c0392b', fg: '#ffffff', glow: '#e8671a' },
            spice:      { DEFAULT: '#f0a020', fg: '#3d2d28' },
            cream:      '#faf6ef',
            charcoal:   '#2a1d18',
            'charcoal-soft': '#3d2d28',
          },
          fontFamily: {
            sans:    ['Hind Siliguri', 'sans-serif'],
            display: ['Tiro Bangla', 'serif'],
          },
          boxShadow: {
            warm: '0 10px 30px -10px rgba(192,57,43,0.35)',
            soft: '0 4px 16px -4px rgba(61,45,40,0.12)',
          },
          backgroundImage: {
            'gradient-warm': 'linear-gradient(135deg, #c0392b 0%, #e8671a 100%)',
          },
          animation: {
            'fade-in':  'fadeIn 0.3s ease-out',
            'slide-up': 'slideUp 0.35s cubic-bezier(.4,0,.2,1)',
          },
          keyframes: {
            fadeIn:  { from: { opacity: '0' }, to: { opacity: '1' } },
            slideUp: { from: { opacity: '0', transform: 'translateY(12px)' }, to: { opacity: '1', transform: 'translateY(0)' } },
          },
        },
      },
    };
  </script>

  <style>
    body { font-family: 'Hind Siliguri', sans-serif; background: #faf6ef; color: #2a1d18; }
    .font-display { font-family: 'Tiro Bangla', serif; }
    .gradient-text {
      background: linear-gradient(135deg, #c0392b, #e8671a);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-thumb { background: #cdc0b0; border-radius: 99px; }
  </style>
  @stack('head')
</head>
<body class="min-h-screen flex flex-col">

  @include('partials.navbar')

  <main class="flex-1">
    @yield('content')
  </main>

  @include('partials.footer')
  @include('partials.toast')

  @stack('scripts')
</body>
</html>
