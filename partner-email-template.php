<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Partnership Request</title>
    <style type="text/css">
        /* Basic reset for email clients */
        body, p, h1, h2, h3 {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f7fafc;
            color: #1a202c;
            line-height: 1.5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            height: 64px;
            margin-bottom: 15px;
        }
        .card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .card-header {
            background-color: #4a6ee0;
            padding: 15px;
            color: white;
        }
        .card-content {
            padding: 20px;
        }
        .field-label {
            font-size: 12px;
            font-weight: bold;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 5px;
        }
        .field-value {
            font-size: 16px;
            margin-bottom: 15px;
        }
        .message-box {
            background-color: #f7fafc;
            padding: 15px;
            border-radius: 6px;
            margin-top: 10px;
        }
        .card-footer {
            background-color: #f7fafc;
            padding: 15px 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #718096;
        }
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .website-link {
            color: #4a6ee0;
            text-decoration: none;
            font-weight: bold;
        }
        .email-link {
            color: #4a6ee0;
            text-decoration: none;
        }
        .capitalize {
            text-transform: capitalize;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header with logo -->
        <div class="header">
            <div>
                <img src="https://retroviralsolution.com/img/logo-1.png" alt="Retroviral Solutions Logo" class="logo">
            </div>
            <h1 style="font-size: 24px; font-weight: bold; color: #4a6ee0; margin-bottom: 5px;">New Partnership Request</h1>
            <p style="color: #718096;">You've received a new partnership request from your website.</p>
        </div>

        <!-- Content card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <h2 style="font-size: 20px; font-weight: bold;">Partnership Details</h2>
            </div>
            
            <!-- Content -->
            <div class="card-content">
                <div style="margin-bottom: 20px;">
                    <div class="field-label">Organization</div>
                    <p class="field-value" style="font-weight: 500;"><?= $cleanData['organization'] ?></p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div class="field-label">Contact Person</div>
                    <p class="field-value"><?= $cleanData['contact_person'] ?></p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div class="field-label">Email</div>
                    <p class="field-value">
                        <a href="mailto:<?= $cleanData['email'] ?>" class="email-link"><?= $cleanData['email'] ?></a>
                    </p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div class="field-label">Phone</div>
                    <p class="field-value"><?= $cleanData['phone'] ?></p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div class="field-label">Partnership Type</div>
                    <p class="field-value capitalize"><?= str_replace('_', ' ', $cleanData['partnership_type']) ?></p>
                </div>
                
                <div>
                    <div class="field-label">Details</div>
                    <div class="message-box">
                        <p style="color: #4a5568;"><?= $cleanData['details'] ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="card-footer">
                <div class="footer-content">
                    <p>Received on <?= date('F j, Y \a\t g:i a') ?></p>
                    <a href="https://retroviralsolution.com" class="website-link">Visit Website</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>