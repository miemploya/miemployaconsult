<?php
/**
 * Miemploya Consult — Admin API Handler
 * Backend for all admin CRUD operations
 */
require_once __DIR__ . '/includes/auth_guard.php';

// Ensure admin access
if (!isAdmin()) {
    json_response(['success' => false, 'message' => 'Unauthorized'], 403);
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── GET-based actions (CSV export) ──────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'export_registrations') {
        $program_id = intval($_GET['program_id'] ?? 0);
        if ($program_id) {
            $rows = db_query("SELECT user_name, email, phone, organization, created_at FROM training_registrations WHERE program_id = ? ORDER BY created_at", [$program_id]);
        } else {
            $rows = db_query("SELECT r.user_name, r.email, r.phone, r.organization, r.created_at, p.title as program FROM training_registrations r LEFT JOIN training_programs p ON r.program_id = p.id ORDER BY r.created_at");
        }
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="registrations_' . date('Ymd') . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, $program_id ? ['Name','Email','Phone','Organization','Registered'] : ['Name','Email','Phone','Organization','Program','Registered']);
        foreach ($rows as $r) {
            $row = [$r['user_name'], $r['email'], $r['phone'], $r['organization']];
            if (!$program_id) $row[] = $r['program'] ?? '';
            $row[] = $r['created_at'];
            fputcsv($out, $row);
        }
        fclose($out);
        exit;
    }
}

switch ($action) {

    // ══════════════════════════════════════════════════════
    //  VACANCIES
    // ══════════════════════════════════════════════════════
    case 'create_vacancy':
        db_insert("INSERT INTO job_vacancies (title, company_name, location, employment_type, deadline, description, requirements, is_active) VALUES (?,?,?,?,?,?,?,?)", [
            sanitize($_POST['title']), sanitize($_POST['company_name']), sanitize($_POST['location']),
            sanitize($_POST['employment_type']), $_POST['deadline'] ?: null,
            sanitize($_POST['description']), sanitize($_POST['requirements']), intval($_POST['is_active'])
        ]);
        json_response(['success' => true]);
        break;

    case 'update_vacancy':
        db_execute("UPDATE job_vacancies SET title=?, company_name=?, location=?, employment_type=?, deadline=?, description=?, requirements=?, is_active=? WHERE id=?", [
            sanitize($_POST['title']), sanitize($_POST['company_name']), sanitize($_POST['location']),
            sanitize($_POST['employment_type']), $_POST['deadline'] ?: null,
            sanitize($_POST['description']), sanitize($_POST['requirements']), intval($_POST['is_active']),
            intval($_POST['id'])
        ]);
        json_response(['success' => true]);
        break;

    case 'delete_vacancy':
        db_execute("DELETE FROM job_vacancies WHERE id=?", [intval($_POST['id'])]);
        json_response(['success' => true]);
        break;

    // ══════════════════════════════════════════════════════
    //  APPLICATION STATUS
    // ══════════════════════════════════════════════════════
    case 'update_application_status':
        $valid = ['new','reviewed','shortlisted','rejected'];
        $status = sanitize($_POST['status']);
        if (in_array($status, $valid)) {
            db_execute("UPDATE job_applications SET status=? WHERE id=?", [$status, intval($_POST['id'])]);
        }
        json_response(['success' => true]);
        break;

    // ══════════════════════════════════════════════════════
    //  TRAINING
    // ══════════════════════════════════════════════════════
    case 'create_training':
        db_insert("INSERT INTO training_programs (title, description, program_date, venue, fee, registration_limit, is_active) VALUES (?,?,?,?,?,?,?)", [
            sanitize($_POST['title']), sanitize($_POST['description']),
            $_POST['program_date'] ?: null, sanitize($_POST['venue']),
            floatval($_POST['fee']), intval($_POST['registration_limit']), intval($_POST['is_active'])
        ]);
        json_response(['success' => true]);
        break;

    case 'update_training':
        db_execute("UPDATE training_programs SET title=?, description=?, program_date=?, venue=?, fee=?, registration_limit=?, is_active=? WHERE id=?", [
            sanitize($_POST['title']), sanitize($_POST['description']),
            $_POST['program_date'] ?: null, sanitize($_POST['venue']),
            floatval($_POST['fee']), intval($_POST['registration_limit']), intval($_POST['is_active']),
            intval($_POST['id'])
        ]);
        json_response(['success' => true]);
        break;

    case 'delete_training':
        db_execute("DELETE FROM training_programs WHERE id=?", [intval($_POST['id'])]);
        json_response(['success' => true]);
        break;

    // ══════════════════════════════════════════════════════
    //  TEMPLATES
    // ══════════════════════════════════════════════════════
    case 'upload_template':
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            json_response(['success' => false, 'message' => 'Please select a file.'], 400);
        }
        $file = $_FILES['file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['pdf','doc','docx','xls','xlsx'])) {
            json_response(['success' => false, 'message' => 'Invalid file type.'], 400);
        }
        if (!is_dir(UPLOAD_TEMPLATES)) mkdir(UPLOAD_TEMPLATES, 0777, true);
        $fname = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
        move_uploaded_file($file['tmp_name'], UPLOAD_TEMPLATES . $fname);

        db_insert("INSERT INTO business_templates (title, description, category, file_path, file_type) VALUES (?,?,?,?,?)", [
            sanitize($_POST['title']), sanitize($_POST['description']),
            sanitize($_POST['category']), $fname, $ext
        ]);
        json_response(['success' => true]);
        break;

    case 'delete_template':
        $tpl = db_row("SELECT * FROM business_templates WHERE id=?", [intval($_POST['id'])]);
        if ($tpl) {
            @unlink(UPLOAD_TEMPLATES . $tpl['file_path']);
            db_execute("DELETE FROM business_templates WHERE id=?", [intval($_POST['id'])]);
        }
        json_response(['success' => true]);
        break;

    // ══════════════════════════════════════════════════════
    //  VIDEOS
    // ══════════════════════════════════════════════════════
    case 'create_video':
        db_insert("INSERT INTO videos (title, video_url, description, publish_date, is_featured) VALUES (?,?,?,?,?)", [
            sanitize($_POST['title']), sanitize($_POST['video_url']),
            sanitize($_POST['description']), $_POST['publish_date'] ?: null, intval($_POST['is_featured'])
        ]);
        json_response(['success' => true]);
        break;

    case 'update_video':
        db_execute("UPDATE videos SET title=?, video_url=?, description=?, publish_date=?, is_featured=? WHERE id=?", [
            sanitize($_POST['title']), sanitize($_POST['video_url']),
            sanitize($_POST['description']), $_POST['publish_date'] ?: null, intval($_POST['is_featured']),
            intval($_POST['id'])
        ]);
        json_response(['success' => true]);
        break;

    case 'delete_video':
        db_execute("DELETE FROM videos WHERE id=?", [intval($_POST['id'])]);
        json_response(['success' => true]);
        break;

    // ══════════════════════════════════════════════════════
    //  NEWS
    // ══════════════════════════════════════════════════════
    case 'create_news':
        db_insert("INSERT INTO news_posts (title, description, category, publish_date, link_url) VALUES (?,?,?,?,?)", [
            sanitize($_POST['title']), sanitize($_POST['description']),
            sanitize($_POST['category']), $_POST['publish_date'] ?: null, sanitize($_POST['link_url'])
        ]);
        json_response(['success' => true]);
        break;

    case 'update_news':
        db_execute("UPDATE news_posts SET title=?, description=?, category=?, publish_date=?, link_url=? WHERE id=?", [
            sanitize($_POST['title']), sanitize($_POST['description']),
            sanitize($_POST['category']), $_POST['publish_date'] ?: null, sanitize($_POST['link_url']),
            intval($_POST['id'])
        ]);
        json_response(['success' => true]);
        break;

    case 'delete_news':
        db_execute("DELETE FROM news_posts WHERE id=?", [intval($_POST['id'])]);
        json_response(['success' => true]);
        break;

    // ══════════════════════════════════════════════════════
    //  POSTERS
    // ══════════════════════════════════════════════════════
    case 'upload_poster':
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            json_response(['success' => false, 'message' => 'Please select an image.'], 400);
        }
        $file = $_FILES['image'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
            json_response(['success' => false, 'message' => 'Invalid image type.'], 400);
        }
        if (!is_dir(UPLOAD_POSTERS)) mkdir(UPLOAD_POSTERS, 0777, true);
        $fname = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
        move_uploaded_file($file['tmp_name'], UPLOAD_POSTERS . $fname);

        db_insert("INSERT INTO posters (title, image_path, event_date, registration_link) VALUES (?,?,?,?)", [
            sanitize($_POST['title']), $fname, $_POST['event_date'] ?: null, sanitize($_POST['registration_link'])
        ]);
        json_response(['success' => true]);
        break;

    case 'delete_poster':
        $p = db_row("SELECT * FROM posters WHERE id=?", [intval($_POST['id'])]);
        if ($p) {
            @unlink(UPLOAD_POSTERS . $p['image_path']);
            db_execute("DELETE FROM posters WHERE id=?", [intval($_POST['id'])]);
        }
        json_response(['success' => true]);
        break;

    // ══════════════════════════════════════════════════════
    //  PRODUCTS
    // ══════════════════════════════════════════════════════
    case 'create_product':
        $slug = generate_slug(sanitize($_POST['name']));
        db_insert("INSERT INTO products (name, slug, tagline, description, features, icon_class, color_from, color_to, external_link, is_active) VALUES (?,?,?,?,?,?,?,?,?,?)", [
            sanitize($_POST['name']), $slug, sanitize($_POST['tagline']),
            sanitize($_POST['description']), $_POST['features'] ?? '[]',
            sanitize($_POST['icon_class']), sanitize($_POST['color_from']),
            sanitize($_POST['color_to']), sanitize($_POST['external_link']), intval($_POST['is_active'])
        ]);
        json_response(['success' => true]);
        break;

    case 'update_product':
        $slug = generate_slug(sanitize($_POST['name']));
        db_execute("UPDATE products SET name=?, slug=?, tagline=?, description=?, features=?, icon_class=?, color_from=?, color_to=?, external_link=?, is_active=? WHERE id=?", [
            sanitize($_POST['name']), $slug, sanitize($_POST['tagline']),
            sanitize($_POST['description']), $_POST['features'] ?? '[]',
            sanitize($_POST['icon_class']), sanitize($_POST['color_from']),
            sanitize($_POST['color_to']), sanitize($_POST['external_link']), intval($_POST['is_active']),
            intval($_POST['id'])
        ]);
        json_response(['success' => true]);
        break;

    // ══════════════════════════════════════════════════════
    //  CONSULTING STATUS
    // ══════════════════════════════════════════════════════
    case 'update_consulting_status':
        $valid = ['new','contacted','in_progress','completed'];
        $status = sanitize($_POST['status']);
        if (in_array($status, $valid)) {
            db_execute("UPDATE consulting_requests SET status=? WHERE id=?", [$status, intval($_POST['id'])]);
        }
        json_response(['success' => true]);
        break;

    // ══════════════════════════════════════════════════════
    //  USERS
    // ══════════════════════════════════════════════════════
    case 'create_user':
        if (!isSuperAdmin()) json_response(['success' => false, 'message' => 'Unauthorized'], 403);
        $result = registerUser(sanitize($_POST['name']), sanitize($_POST['email']), $_POST['password'], sanitize($_POST['phone'] ?? ''));
        if ($result['success'] && $_POST['role'] !== 'user') {
            db_execute("UPDATE users SET role=?, is_active=? WHERE id=?", [sanitize($_POST['role']), intval($_POST['is_active']), $result['user_id']]);
        }
        json_response($result);
        break;

    case 'update_user':
        if (!isSuperAdmin()) json_response(['success' => false, 'message' => 'Unauthorized'], 403);
        $fields = "name=?, email=?, role=?, is_active=?";
        $params = [sanitize($_POST['name']), sanitize($_POST['email']), sanitize($_POST['role']), intval($_POST['is_active'])];
        if (!empty($_POST['password'])) {
            $fields .= ", password=?";
            $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        $params[] = intval($_POST['id']);
        db_execute("UPDATE users SET $fields WHERE id=?", $params);
        json_response(['success' => true]);
        break;

    // ══════════════════════════════════════════════════════
    //  HOMEPAGE PINS
    // ══════════════════════════════════════════════════════
    case 'create_pin':
        if (!isSuperAdmin()) json_response(['success' => false, 'message' => 'Unauthorized'], 403);
        db_insert("INSERT INTO homepage_pins (content_type, content_id, sort_order) VALUES (?,?,?)", [
            sanitize($_POST['content_type']), intval($_POST['content_id']), intval($_POST['sort_order'])
        ]);
        json_response(['success' => true]);
        break;

    case 'delete_pin':
        if (!isSuperAdmin()) json_response(['success' => false, 'message' => 'Unauthorized'], 403);
        db_execute("DELETE FROM homepage_pins WHERE id=?", [intval($_POST['id'])]);
        json_response(['success' => true]);
        break;

    // ══════════════════════════════════════════════════════
    //  QUARTERLY TRAINING REQUESTS
    // ══════════════════════════════════════════════════════
    case 'delete_quarterly_request':
        db_execute("DELETE FROM quarterly_training_requests WHERE id=?", [intval($_POST['id'])]);
        json_response(['success' => true]);
        break;

    // ══════════════════════════════════════════════════════
    //  CHANGE PASSWORD
    // ══════════════════════════════════════════════════════
    case 'change_password':
        $user = currentUser();
        if (!$user) json_response(['success' => false, 'message' => 'Not logged in.'], 401);
        $current = db_row("SELECT password FROM users WHERE id = ?", [$user['id']]);
        if (!$current || !password_verify($_POST['current_password'], $current['password'])) {
            json_response(['success' => false, 'message' => 'Current password is incorrect.']);
        }
        db_execute("UPDATE users SET password = ? WHERE id = ?", [
            password_hash($_POST['new_password'], PASSWORD_DEFAULT), $user['id']
        ]);
        json_response(['success' => true]);
        break;

    default:
        json_response(['success' => false, 'message' => 'Invalid action.'], 400);
}
