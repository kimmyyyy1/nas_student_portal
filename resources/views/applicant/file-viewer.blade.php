<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $fileName }} - NAS View</title>

    {{-- 👇 FAVICON (Mananatili ito) --}}
    <link rel="icon" href="{{ asset('images/nas/favicon1.png') }}?v=2" type="image/png">
    <link rel="shortcut icon" href="{{ asset('images/nas/favicon1.png') }}?v=2" type="image/png">

    <style>
        body, html { 
            margin: 0; 
            padding: 0; 
            height: 100%; 
            overflow: hidden; 
            background: #525659; /* Standard dark grey background for file viewers */
        }
        
        .container { 
            display: flex; 
            flex-direction: column; 
            height: 100vh; 
            width: 100vw;
        }
        
        /* File Viewer Area */
        .viewer { 
            flex-grow: 1; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            overflow: auto; 
        }
        
        /* PDF Style */
        iframe { 
            width: 100%; 
            height: 100%; 
            border: none; 
        }
        
        /* Image Style */
        img { 
            max-width: 100%; 
            max-height: 100%; 
            object-fit: contain; 
            box-shadow: 0 0 20px rgba(0,0,0,0.5); 
        }
    </style>
</head>
<body>

    <div class="container">
        {{-- Tinanggal na ang Header, Logo, at Download Button --}}

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