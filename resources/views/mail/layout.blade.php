<!-- resources/views/emails/layout.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>{{ $subject ?? config('app.name') }}</title>
  <style>
    body, table, td, a { -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; }
    table { border-collapse: collapse !important; }
    img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
    body { margin: 0 !important; padding: 0 !important; width: 100% !important; }

    .email-wrapper {
      width: 100%;
      background-color: #f2f4f6;
      padding: 20px 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen,
                   Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
      color: #51545e;
    }
    .email-content {
      max-width: 600px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .email-header {
      background: #17023a;
      padding: 20px;
      text-align: center;
    }

    .email-header h1,
    .email-header span {
      color: #ffffff;
    }

    .email-header h1 {
      margin: 0;
      color: #ffffff;
      font-size: 24px;
      font-weight: 600;
    }

    .email-body {
      padding: 30px 20px;
      line-height: 1.6;
    }
    .email-body h2 {
      font-size: 20px;
      margin-top: 0;
      color: #1f2937;
    }
    .email-body p {
      margin: 0 0 1em;
    }

    .email-body table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.5rem;
        font-family: inherit;
    }

    .email-body th,
    .email-body td {
        border: 1px solid #e5e7eb;
        padding: 0.75rem 1rem;
        text-align: left;
        vertical-align: top;
        font-size: 14px;
    }

    .email-body thead th {
        background-color: #f3f4f6;
        color: #374151;
        font-weight: bolder;
    }

    .email-body tbody tr:nth-child(even) {
        background-color: #fafafa;
    }

    .email-body tbody tr:hover {
        background-color: #f1f5f9;
    }

    .email-body td {
        color: #4b5563;
    }

    .email-footer {
      background: #f2f4f6;
      padding: 20px;
      text-align: center;
      font-size: 12px;
      color: #6b7280;
    }
    .email-footer a {
      color: #4f46e5;
      text-decoration: none;
    }

    @media only screen and (max-width: 620px) {
      .email-content { width: 100% !important; border-radius: 0; }
      .email-header h1 { font-size: 20px !important; }
      .email-body { padding: 20px !important; }
    }
  </style>
</head>
<body>
  <div class="email-wrapper">
    <div class="email-content">
      <div class="email-header">
        <h1>Zeke Bondy-Villa</h1>
        <span>Invitational Golf Tournament</span>
      </div>

      <div class="email-body">
        @yield('content')
      </div>

      <div class="email-footer">
        &copy; {{ date('Y') }} Zeke Bondy-Villa Invitational Golf Tournament. All rights reserved.<br>
        <a href="{{ url('/') }}">{{ url('/') }}</a>
      </div>
    </div>
  </div>
</body>
</html>
