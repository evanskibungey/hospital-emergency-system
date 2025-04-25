<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discharge Instructions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 8.5in;
            margin: 0 auto;
            padding: 0.5in;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }
        
        .header h1 {
            font-size: 24px;
            margin: 0;
        }
        
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        
        .patient-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 5px;
        }
        
        .patient-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .section {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .section-header {
            background-color: #f3f4f6;
            padding: 10px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }
        
        .section-content {
            padding: 10px;
        }
        
        h2 {
            font-size: 18px;
            margin-top: 0;
        }
        
        h3 {
            font-size: 16px;
            margin-top: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th, table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }
        
        table th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #000;
            font-size: 12px;
            text-align: center;
        }
        
        .emergency-info {
            margin-top: 20px;
            padding: 10px;
            background-color: #fee2e2;
            border: 1px solid #ef4444;
            border-radius: 5px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
            
            @page {
                margin: 0.5in;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print();" style="padding: 10px 20px; background-color: #3b82f6; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Print Discharge Instructions
        </button>
        <a href="{{ route('doctor.discharges.show', $discharge) }}" style="display: inline-block; margin-left: 10px; padding: 10px 20px; background-color: #6b7280; color: white; border-radius: 5px; text-decoration: none;">
            Back to Discharge Details
        </a>
    </div>

    <div class="header">
        <h1>Hospital Emergency Medical Center</h1>
        <p>123 Healthcare Avenue, Medical City</p>
        <p>Phone: (555) 123-4567 | Fax: (555) 765-4321</p>
        <h2>DISCHARGE INSTRUCTIONS</h2>
    </div>

    <div class="patient-info">
        <div class="patient-info-grid">
            <div>
                <p><strong>Patient Name:</strong> {{ $discharge->patient->full_name }}</p>
                <p><strong>Date of Birth:</strong> {{ $discharge->patient->date_of_birth->format('M d, Y') }} ({{ $discharge->patient->date_of_birth->age }} years)</p>
                <p><strong>Medical Record #:</strong> {{ $discharge->patient->medical_record_number }}</p>
            </div>
            <div>
                <p><strong>Visit #:</strong> {{ $discharge->visit_id }}</p>
                <p><strong>Discharge Date:</strong> {{ $discharge->discharged_at->format('M d, Y g:i A') }}</p>
                <p><strong>Attending Physician:</strong> Dr. {{ $discharge->dischargedBy->name }}</p>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h3>DISCHARGE DIAGNOSIS</h3>
        </div>
        <div class="section-content">
            <p>{{ $discharge->discharge_diagnosis }}</p>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h3>DISCHARGE INSTRUCTIONS</h3>
        </div>
        <div class="section-content">
            <p>{{ $discharge->discharge_instructions }}</p>
        </div>
    </div>

    @if (!empty($discharge->medications_at_discharge))
        <div class="section">
            <div class="section-header">
                <h3>MEDICATIONS</h3>
            </div>
            <div class="section-content">
                <p>{{ $discharge->medications_at_discharge }}</p>
                
                @if ($prescriptions && $prescriptions->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Medication</th>
                                <th>Dosage</th>
                                <th>Frequency</th>
                                <th>Instructions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prescriptions as $prescription)
                                <tr>
                                    <td>{{ $prescription->medication_name }}</td>
                                    <td>{{ $prescription->dosage }}</td>
                                    <td>{{ $prescription->frequency }}</td>
                                    <td>{{ $prescription->instructions }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endif

    @if (!empty($discharge->activity_restrictions))
        <div class="section">
            <div class="section-header">
                <h3>ACTIVITY RESTRICTIONS</h3>
            </div>
            <div class="section-content">
                <p>{{ $discharge->activity_restrictions }}</p>
            </div>
        </div>
    @endif

    @if (!empty($discharge->diet_instructions))
        <div class="section">
            <div class="section-header">
                <h3>DIET INSTRUCTIONS</h3>
            </div>
            <div class="section-content">
                <p>{{ $discharge->diet_instructions }}</p>
            </div>
        </div>
    @endif

    @if (!empty($discharge->follow_up_instructions) || ($discharge->followUpAppointments && $discharge->followUpAppointments->count() > 0))
        <div class="section">
            <div class="section-header">
                <h3>FOLLOW-UP CARE</h3>
            </div>
            <div class="section-content">
                @if (!empty($discharge->follow_up_instructions))
                    <p>{{ $discharge->follow_up_instructions }}</p>
                @endif
                
                @if ($discharge->followUpAppointments && $discharge->followUpAppointments->count() > 0)
                    <h4>Scheduled Appointments:</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Provider/Department</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($discharge->followUpAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->appointment_time->format('M d, Y g:i A') }}</td>
                                    <td>
                                        @if ($appointment->doctor)
                                            Dr. {{ $appointment->doctor->name }}
                                        @else
                                            {{ $appointment->department ?: $appointment->specialty ?: 'Not specified' }}
                                        @endif
                                    </td>
                                    <td>{{ $appointment->reason_for_visit }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endif

    <div class="emergency-info">
        <h3>WHEN TO SEEK MEDICAL ATTENTION</h3>
        <p>Return to the emergency department or call 911 immediately if you experience:</p>
        <ul>
            <li>Difficulty breathing or shortness of breath</li>
            <li>Chest pain or pressure</li>
            <li>Severe pain that is not relieved by your prescribed medications</li>
            <li>Persistent fever above 101.5°F (38.6°C)</li>
            <li>Uncontrolled bleeding</li>
            <li>New or worsening confusion</li>
            <li>Inability to eat, drink, or take medications</li>
            <li>Any other concerning symptoms</li>
        </ul>
    </div>

    <div class="footer">
        <p>These discharge instructions were provided by Dr. {{ $discharge->dischargedBy->name }} on {{ $discharge->discharged_at->format('M d, Y') }}.</p>
        <p>If you have any questions, please contact our hospital at (555) 123-4567.</p>
        <p>Hospital Emergency Medical Center - 123 Healthcare Avenue, Medical City</p>
    </div>
</body>
</html>
