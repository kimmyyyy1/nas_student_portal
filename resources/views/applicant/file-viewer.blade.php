<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $fileName }} - NAS View</title>

    {{-- 👇 DITO NA-UPDATE ANG FAVICON MO --}}
    <link rel="icon" href="{{ asset('images/nas/favicon1.png') }}" type="image/png">

    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; background: #525659; }
        .container { display: flex; flex-direction: column; height: 100vh; }
        
        /* Header Styling */
        .header {
            background: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 10;
            font-family: sans-serif;
        }
        
        .header-left { display: flex; align-items: center; gap: 10px; }
        
        /* Logo sa Header (Mananatiling horizontal para sa branding, o pwede mo rin palitan) */
        .header img { height: 35px; width: auto; object-fit: contain; }
        
        .header h1 { margin: 0; font-size: 16px; color: #333; font-weight: 600; }
        
        .btn-download {
            text-decoration: none; 
            color: #fff; 
            background: #4f46e5; 
            padding: 8px 16px; 
            border-radius: 6px; 
            font-size: 13px; 
            font-weight: bold;
            transition: background 0.2s;
        }
        .btn-download:hover { background: #4338ca; }
        
        /* File Viewer Area */
        .viewer { flex-grow: 1; display: flex; justify-content: center; align-items: center; overflow: auto; background-color: #525659; }
        iframe { width: 100%; height: 100%; border: none; }
        img { max-width: 100%; max-height: 100%; object-fit: contain; box-shadow: 0 0 20px rgba(0,0,0,0.5); }
    </style>
</head>
<body>

    <div class="container">
        {{-- Header Section --}}
        <div class="header">
            <div class="header-left">
                {{-- Logo sa loob ng Viewer (Visual Branding) --}}
                <img src="{{ asset('images/nas/horizontal.png') }}" alt="NAS Logo">
                <h1>{{ $fileName }}</h1>
            </div>
            
            {{-- Download Button --}}
            <a href="{{ $src }}" download="{{ $fileName }}" class="btn-download">
                Download File
            </a>
        </div>

        {{-- Main Viewer --}}
        <div class="viewer">
            @if($fileType === 'pdf')
                {{-- Para sa PDF --}}
                <iframe src="{{ $src }}"></iframe>
            @else
                {{-- Para sa Images --}}
                <img src="{{ $src }}" alt="Document Preview">
            @endif
        </div>
    </div>

</body>
</html>