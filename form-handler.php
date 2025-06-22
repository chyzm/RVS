<?php
// Set headers for JSON response
header('Content-Type: application/json');

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get form data
$formData = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
    exit;
}

// Validate form type and required fields
if (!isset($formData['formType'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Form type not specified']);
    exit;
}

// In form-handler.php, add this check before processing:
    if (!empty($formData['website'])) {
        // Likely a bot submission
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Submission rejected']);
        exit;
    }

// Process different form types
switch ($formData['formType']) {
    case 'contact':
        $requiredFields = ['name', 'email', 'message'];
        $recipient = 'info@retroviralsolutions.com';
        $subject = 'New Contact Form Submission';
        $template = 'contact-email-template.php';
        break;
    
    case 'partner':
        $requiredFields = ['organization', 'contact_person', 'email', 'phone', 'partnership_type', 'details'];
        $recipient = 'partners@retroviralsolutions.com';
        $subject = 'New Partnership Request';
        $template = 'partner-email-template.php';
        break;
    
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid form type']);
        exit;
}

// Validate required fields
foreach ($requiredFields as $field) {
    if (empty($formData[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit;
    }
}

// Sanitize inputs
$cleanData = array_map('htmlspecialchars', $formData);

// Generate email content
ob_start();
include $template;
$emailContent = ob_get_clean();

// Set email headers
$headers = [
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=utf-8',
    'From: Retroviral Solutions <no-reply@retroviralsolutions.com>',
    'Reply-To: ' . $cleanData['email'],
    'X-Mailer: PHP/' . phpversion()
];

// Send email
$mailSent = mail($recipient, $subject, $emailContent, implode("\r\n", $headers));

if ($mailSent) {
    echo json_encode(['success' => true, 'message' => 'Thank you for your submission!']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to send email']);
}
?>