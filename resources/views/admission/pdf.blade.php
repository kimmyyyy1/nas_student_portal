<!DOCTYPE html>
<html>
<head>
    <title>Admission Application Form</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* LETTER SIZE OPTIMIZATION (8.5 x 11 inches) */
        @page { margin: 0.5in 0.5in; } /* Standard narrow margins for forms */
        
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            font-size: 10px; 
            color: #000;
            line-height: 1.1; /* Tighter line height for Short Bond Paper */
        }
        
        /* Compact Header */
        .header { text-align: center; margin-bottom: 8px; border-bottom: 2px solid black; padding-bottom: 4px; }
        .logo { height: 55px; width: auto; margin-bottom: 2px; }
        .title { font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .subtitle { font-size: 10px; font-weight: bold; text-transform: uppercase; margin: 0; }
        
        /* Section Headers */
        .section-header { 
            background-color: #d1d5db; 
            padding: 3px 6px; 
            font-weight: bold; 
            text-transform: uppercase; 
            border: 1px solid #6b7280; 
            margin-top: 8px; /* Reduced vertical gap */
            font-size: 9px;
        }
        
        table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        td { padding: 2px 3px; vertical-align: top; } /* Tighter padding */
        
        .label { font-size: 8px; font-weight: bold; color: #4b5563; text-transform: uppercase; display: block; margin-bottom: 0px;}
        .value { font-size: 10px; font-weight: bold; border-bottom: 1px solid #9ca3af; display: block; padding-bottom: 1px; min-height: 13px; }
        
        /* Photo Box */
        .photo-box { width: 100px; height: 100px; border: 1px solid #000; text-align: center; position: relative; }
        .photo-img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .photo-placeholder { position: absolute; top: 40%; width: 100%; text-align: center; font-size: 8px; color: #6b7280; font-weight: bold; }

        /* Checkboxes */
        .checkbox-group { margin-top: 4px; margin-bottom: 4px; }
        .checkbox-item { display: inline-block; margin-right: 12px; }
        .checkbox-box { 
            display: inline-block; 
            width: 10px; 
            height: 10px; 
            border: 1px solid #000; 
            text-align: center; 
            line-height: 9px; 
            font-size: 9px;
            margin-right: 3px;
            vertical-align: middle;
        }
        .checkbox-label { font-weight: bold; text-transform: uppercase; font-size: 8px; vertical-align: middle; }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/nas/nas-logo-sidebar.png') }}" class="logo">
        <h1 class="title">National Academy of Sports</h1>
        <p class="subtitle">Student-Athlete Admission Application Form</p>
        <p style="font-size: 8px; font-style: italic; margin: 0;">New Clark City, Capas, Tarlac</p>
    </div>

    <div class="section-header">I. Applicant Information</div>
    
    <table>
        <tr>
            <td style="width: 110px;">
                <div class="photo-box">
                    @if(isset($application->uploaded_files['id_picture']))
                        <img src="{{ public_path('storage/' . $application->uploaded_files['id_picture']) }}" class="photo-img">
                    @else
                        <div class="photo-placeholder">2x2 PHOTO</div>
                    @endif
                </div>
            </td>
            <td>
                <table>
                    <tr>
                        <td colspan="2">
                            <span class="label">Full Name (Last, First, Middle)</span>
                            <span class="value">{{ $application->last_name }}, {{ $application->first_name }} {{ $application->middle_name }}</span>
                        </td>
                        <td>
                            <span class="label">LRN</span>
                            <span class="value">{{ $application->lrn }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="label">Birthdate</span>
                            <span class="value">{{ \Carbon\Carbon::parse($application->date_of_birth)->format('Y-m-d') }}</span>
                        </td>
                        <td><span class="label">Age / Sex</span><span class="value">{{ $application->age }} / {{ $application->gender }}</span></td>
                        <td><span class="label">Religion</span><span class="value">{{ $application->religion }}</span></td>
                    </tr>
                    <tr>
                        <td colspan="3"><span class="label">Place of Birth</span><span class="value">{{ $application->birthplace }}</span></td>
                    </tr>
                    <tr>
                        <td colspan="3"><span class="label">Email Address</span><span class="value">{{ $application->email_address }}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td><span class="label">Region</span><span class="value">{{ $application->region }}</span></td>
            <td><span class="label">Province</span><span class="value">{{ $application->province }}</span></td>
            <td><span class="label">Municipality</span><span class="value">{{ $application->municipality_city }}</span></td>
            <td><span class="label">Barangay</span><span class="value">{{ $application->barangay }}</span></td>
        </tr>
        <tr>
            <td colspan="4"><span class="label">Street Address / House No.</span><span class="value">{{ $application->street_address }} (Zip: {{ $application->zip_code }})</span></td>
        </tr>
    </table>
    
    <div class="checkbox-group" style="border-top: 1px solid #d1d5db; padding-top: 4px;">
        <div class="checkbox-item"><span class="checkbox-box">{{ $application->is_ip ? '✓' : '' }}</span><span class="checkbox-label">Indigenous People (IP)</span></div>
        <div class="checkbox-item"><span class="checkbox-box">{{ $application->is_pwd ? '✓' : '' }}</span><span class="checkbox-label">PWD</span></div>
        <div class="checkbox-item"><span class="checkbox-box">{{ $application->is_4ps ? '✓' : '' }}</span><span class="checkbox-label">4Ps Beneficiary</span></div>
    </div>

    <div class="section-header">II. Academic & Sports Profile</div>
    <table>
        <tr>
            <td colspan="2"><span class="label">Last School Attended</span><span class="value">{{ $application->previous_school }} ({{ $application->school_type }})</span></td>
            <td><span class="label">Applied Grade</span><span class="value">{{ $application->grade_level_applied }}</span></td>
        </tr>
        <tr>
            <td colspan="2"><span class="label">Priority Sport</span><span class="value">{{ $application->sport }}</span></td>
            <td>
                <span class="label">Palaro Participation</span>
                <span class="value">
                    {{ $application->has_palaro_participation ? 'YES (' . $application->palaro_year . ')' : 'NO' }}
                </span>
            </td>
        </tr>
    </table>

    <div class="section-header">III. Guardian Information</div>
    <table>
        <tr>
            <td><span class="label">Guardian Name</span><span class="value">{{ $application->guardian_name }}</span></td>
            <td><span class="label">Relationship</span><span class="value">{{ $application->guardian_relationship }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Contact Number</span><span class="value">{{ $application->guardian_contact }}</span></td>
            <td><span class="label">Email</span><span class="value">{{ $application->guardian_email }}</span></td>
        </tr>
    </table>

    <div class="section-header">IV. Submitted Documents Checklist</div>
    <table style="margin-top: 4px;">
        @php
            $files = $application->uploaded_files ?? [];
            $docs = [
                'scholarship_form' => 'Scholarship Application Form',
                'student_profile' => 'Student-Athlete Profile Form',
                'coach_reco' => 'Coach Recommendation Form',
                'adviser_reco' => 'Adviser Recommendation Form',
                'medical_clearance' => 'Medical/Physical Clearance',
                'guardian_id' => 'Guardian Valid ID'
            ];
            $count = 0;
        @endphp
        <tr>
            @foreach($docs as $key => $label)
                <td style="padding-bottom: 2px;">
                    <div class="checkbox-item">
                        <span class="checkbox-box">{{ isset($files[$key]) ? '✓' : '' }}</span>
                        <span class="checkbox-label" style="font-size: 8px;">{{ $label }}</span>
                    </div>
                </td>
                @php $count++; @endphp
                @if($count % 2 == 0) </tr><tr> @endif
            @endforeach
        </tr>
    </table>

    <div style="margin-top: 15px; text-align: center; font-size: 8px; color: #6b7280; border-top: 1px solid #d1d5db; padding-top: 5px;">
        <p>System Generated Report | National Academy of Sports Admission System | Date Printed: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

</body>
</html>