<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Card</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 4in;
            margin: 0 auto;
            padding: 0.25in;
        }
        
        .card {
            border: 2px solid #3b82f6;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .header {
            background-color: #3b82f6;
            color: white;
            text-align: center;
            padding: 10px;
        }
        
        .header h1 {
            font-size: 18px;
            margin: 0;
        }
        
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
        }
        
        .content {
            padding: 15px;
        }
        
        .patient-info {
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .appointment-info {
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .appointment-time {
            font-weight: bold;
            font-size: 16px;
            margin: 10px 0;
            text-align: center;
        }
        
        .appointment-details {
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .instructions {
            font-size: 12px;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        
        .urgent {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 5px;
            text-align: center;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
        }
        
        /* For print optimization */
        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
            
            @page {
                size: 4in 5.5in;
                margin: 0.25in;
            }
            
            .card {
                border: 1px solid #000;
            }
            
            .header {
                background-color: #f3f4f6 !important;
                color: #000 !important;
                border-bottom: 1px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print();" style="padding: 10px 20px; background-color: #3b82f6; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Print Appointment Card
        </button>
        <a href="{{ route('doctor.follow-up-appointments.show', $followUpAppointment) }}" style="display: inline-block; margin-left: 10px; padding: 10px 20px; background-color: #6b7280; color: white; border-radius: 5px; text-decoration: none;">
            Back to Appointment Details
        </a>
    </div>

    <div class="card">
        <div class="header">
            <h1>Hospital Emergency Medical Center</h1>
            <p>Follow-up Appointment</p>
        </div>

        <div class="content">
            <div class="patient-info">
                <p><strong>Patient:</strong> {{ $followUpAppointment->patient->full_name }}</p>
                <p><strong>MRN:</strong> {{ $followUpAppointment->patient->medical_record_number }}</p>
            </div>

            @if ($followUpAppointment->is_urgent)
                <div class="urgent">
                    URGENT FOLLOW-UP REQUIRED
                </div>
            @endif

            <div class="appointment-time">
                {{ $followUpAppointment->appointment_time->format('l, F j, Y') }}<br>
                {{ $followUpAppointment->appointment_time->format('g:i A') }}
            </div>

            <div class="appointment-details">
                <p><strong>Department:</strong> {{ $followUpAppointment->department ?: 'Not specified' }}</p>
                
                <p><strong>Provider:</strong> 
                    @if ($followUpAppointment->doctor)
                        Dr. {{ $followUpAppointment->doctor->name }}
                    @elseif ($followUpAppointment->specialty)
                        {{ $followUpAppointment->specialty }} Specialist
                    @else
                        To be determined
                    @endif
                </p>
                
                <p><strong>Reason:</strong> {{ $followUpAppointment->reason_for_visit }}</p>
                
                @if ($followUpAppointment->special_instructions)
                    <p><strong>Special Instructions:</strong> {{ $followUpAppointment->special_instructions }}</p>
                @endif
            </div>

            <div class="instructions">
                <p>Please arrive 15 minutes before your scheduled appointment time. Bring this card, your insurance information, and a list of current medications.</p>
                <p>If you need to cancel or reschedule, please call (555) 123-4567 at least 24 hours in advance.</p>
            </div>

            <div class="footer">
                <p>Hospital Emergency Medical Center<br>
                123 Healthcare Avenue, Medical City<br>
                Phone: (555) 123-4567</p>
            </div>
        </div>
    </div>
</body>
</html>
