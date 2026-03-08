<?php
/**
 * Miemploya Consult — Public API Handler
 * Handles all public form submissions via AJAX
 */
require_once __DIR__ . '/includes/db.php';

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    // ── Consulting Request ──────────────────────────────
    case 'submit_consulting':
        $company = sanitize($_POST['company_name'] ?? '');
        $contact = sanitize($_POST['contact_person'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $sector = sanitize($_POST['industry_sector'] ?? '');
        $desc = sanitize($_POST['description'] ?? '');

        if (!$company || !$contact || !$email || !$phone || !$desc) {
            json_response(['success' => false, 'message' => 'Please fill all required fields.'], 400);
        }

        db_insert(
            "INSERT INTO consulting_requests (company_name, contact_person, email, phone, industry_sector, description) VALUES (?,?,?,?,?,?)",
            [$company, $contact, $email, $phone, $sector, $desc]
        );
        json_response(['success' => true, 'message' => 'Consulting request submitted.']);
        break;

    // ── Job Application ─────────────────────────────────
    case 'apply_job':
        $vacancy_id = intval($_POST['vacancy_id'] ?? 0);
        $name = sanitize($_POST['full_name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $address = sanitize($_POST['address'] ?? '');
        $education = sanitize($_POST['education'] ?? '');
        $experience = sanitize($_POST['experience'] ?? '');
        $cover = sanitize($_POST['cover_letter'] ?? '');

        if (!$vacancy_id || !$name || !$email || !$phone || !$education || !$experience) {
            json_response(['success' => false, 'message' => 'Please fill all required fields.'], 400);
        }

        // Handle CV upload
        $cv_path = '';
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['cv'];
            if ($file['size'] > MAX_CV_SIZE) {
                json_response(['success' => false, 'message' => 'CV file too large (max 5MB).'], 400);
            }
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['pdf', 'doc', 'docx'])) {
                json_response(['success' => false, 'message' => 'Only PDF, DOC, and DOCX files are allowed.'], 400);
            }
            if (!is_dir(UPLOAD_CVS)) mkdir(UPLOAD_CVS, 0777, true);
            $cv_path = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
            move_uploaded_file($file['tmp_name'], UPLOAD_CVS . $cv_path);
        } else {
            json_response(['success' => false, 'message' => 'Please upload your CV.'], 400);
        }

        db_insert(
            "INSERT INTO job_applications (vacancy_id, full_name, email, phone, address, education, experience, cv_path, cover_letter) VALUES (?,?,?,?,?,?,?,?,?)",
            [$vacancy_id, $name, $email, $phone, $address, $education, $experience, $cv_path, $cover]
        );
        json_response(['success' => true, 'message' => 'Application submitted successfully.']);
        break;

    // ── Training Registration ───────────────────────────
    case 'register_training':
        $program_id = intval($_POST['program_id'] ?? 0);
        $name = sanitize($_POST['user_name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $org = sanitize($_POST['organization'] ?? '');

        if (!$program_id || !$name || !$email) {
            json_response(['success' => false, 'message' => 'Name and email are required.'], 400);
        }

        // Check registration limit
        $program = db_row("SELECT * FROM training_programs WHERE id = ?", [$program_id]);
        if (!$program) json_response(['success' => false, 'message' => 'Program not found.'], 404);
        if ($program['registration_limit'] && $program['registrations_count'] >= $program['registration_limit']) {
            json_response(['success' => false, 'message' => 'This program is fully booked.'], 400);
        }

        db_insert(
            "INSERT INTO training_registrations (program_id, user_name, email, phone, organization) VALUES (?,?,?,?,?)",
            [$program_id, $name, $email, $phone, $org]
        );
        db_execute("UPDATE training_programs SET registrations_count = registrations_count + 1 WHERE id = ?", [$program_id]);
        json_response(['success' => true, 'message' => 'Registration successful.']);
        break;

    // ── Quarterly Training Request ──────────────────────
    case 'submit_quarterly':
        $company = sanitize($_POST['company_name'] ?? '');
        $contact = sanitize($_POST['contact_person'] ?? '');
        $dept = sanitize($_POST['department'] ?? '');
        $staff = intval($_POST['staff_count'] ?? 0);
        $cat = sanitize($_POST['training_category'] ?? '');
        $period = sanitize($_POST['preferred_period'] ?? '');

        if (!$company || !$contact) {
            json_response(['success' => false, 'message' => 'Company name and contact are required.'], 400);
        }

        db_insert(
            "INSERT INTO quarterly_training_requests (company_name, contact_person, department, staff_count, training_category, preferred_period) VALUES (?,?,?,?,?,?)",
            [$company, $contact, $dept, $staff, $cat, $period]
        );
        json_response(['success' => true, 'message' => 'Quarterly training request submitted.']);
        break;

    // ── Template Download ───────────────────────────────
    case 'download_template':
        $id = intval($_GET['id'] ?? 0);
        $tpl = db_row("SELECT * FROM business_templates WHERE id = ? AND is_active = 1", [$id]);
        if (!$tpl) { header('HTTP/1.1 404 Not Found'); die('Template not found.'); }

        db_execute("UPDATE business_templates SET download_count = download_count + 1 WHERE id = ?", [$id]);

        $filepath = UPLOAD_TEMPLATES . $tpl['file_path'];
        if (file_exists($filepath)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($tpl['file_path']) . '"');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit;
        } else {
            header('HTTP/1.1 404 Not Found');
            die('File not found on server.');
        }
        break;

    // ── Contact Message ─────────────────────────────────
    case 'contact_message':
        // For now, just redirect back with success
        flash('contact_success', 'Thank you for your message! We\'ll get back to you soon.');
        redirect(SITE_URL . '/contact.php');
        break;

    default:
        json_response(['success' => false, 'message' => 'Invalid action.'], 400);
}
