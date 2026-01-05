<!DOCTYPE html>
<html>
<head>
    <title>NAS Admission</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h1 style="color: #2563eb;">Congratulations, {{ $student->first_name }}!</h1>
    
    <p>We are pleased to inform you that your application to the National Academy of Sports has been <strong>APPROVED</strong>.</p>
    
    <p>You have been officially enrolled in <strong>{{ $student->section->section_name }}</strong>.</p>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

    <h3 style="color: #1e40af;">Your Official Student Portal Account</h3>
    <p>An official institutional account has been created for you. Please use these credentials to log in:</p>

    <div style="background-color: #f3f4f6; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb;">
        <p style="margin: 5px 0;"><strong>Login URL:</strong> <a href="{{ route('login') }}" style="color: #2563eb;">{{ route('login') }}</a></p>
        <p style="margin: 5px 0;"><strong>Official Email (Username):</strong> <span style="color: #dc2626; font-weight: bold;">{{ $student->email_address }}</span></p>
        <p style="margin: 5px 0;"><strong>Temporary Password:</strong> <span style="font-family: monospace; font-size: 1.1em; background: #fff; padding: 2px 5px;">{{ $tempPassword }}</span></p>
    </div>

    <p style="font-size: 0.9em; color: #666; margin-top: 20px;">
        <em>Note: This is your permanent school email. Please use this for all future transactions and portal access.</em>
    </p>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
    
    <p>Regards,<br><strong>Office of the Registrar</strong><br>National Academy of Sports</p>
</body>
</html>