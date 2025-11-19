<?php
require_once __DIR__ . '/../common.php';
$action = $_GET['action'] ?? '';
$db = get_db();
header('X-Engine: Karam-API');

if($action === 'logout'){
    session_destroy(); header('Location: /'); exit;
}

if($action === 'lead' && $_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if(!$name || !$phone){ $_SESSION['flash'] = 'Name and phone required'; header('Location: /'); exit; }
    $id = addLead($name,$email,$phone,$message);
    $_SESSION['flash'] = 'Enquiry received. Thank you.';
    header('Location: /'); exit;
}

if($action === 'save_listing' && $_SERVER['REQUEST_METHOD'] === 'POST'){
    require_login();
    $id = trim($_POST['id'] ?? '');
    $title = trim($_POST['title'] ?? '');
    if(!$title){ $_SESSION['flash'] = 'Title required'; header('Location: /admin-dashboard.php'); exit; }
    $price = trim($_POST['price'] ?? '');
    $beds = intval($_POST['beds'] ?? 0);
    $area = trim($_POST['area'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $img_url = trim($_POST['img_url'] ?? '');
    $imgPath = '';
    if(!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
        $uploaddir = __DIR__ . '/uploads/'; if(!is_dir($uploaddir)) mkdir($uploaddir,0755,true);
        $tmp = $_FILES['image']['tmp_name'];
        $name = basename($_FILES['image']['name']);
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $safe = 'img_'.time().'_'.bin2hex(random_bytes(4)).'.'.preg_replace('/[^a-zA-Z0-9]/','',substr($ext,0,8));
        $dest = $uploaddir . $safe;
        if(move_uploaded_file($tmp,$dest)){
            $imgPath = '/uploads/' . $safe;
        }
    }
    if(!$imgPath && $img_url) $imgPath = $img_url;

    if($id){
        $stmt = $db->prepare('UPDATE listings SET title=:t,price=:p,beds=:b,area=:a,img=:i,description=:d WHERE uid=:uid');
        $stmt->execute([':t'=>$title,':p'=>$price,':b'=>$beds,':a'=>$area,':i'=>$imgPath,':d'=>$desc,':uid'=>$id]);
    } else {
        $uid = 'MK-'.strtoupper(substr(bin2hex(random_bytes(3)),0,6));
        $stmt = $db->prepare('INSERT INTO listings (uid,title,price,beds,area,img,description,published) VALUES (:uid,:t,:p,:b,:a,:i,:d,0)');
        $stmt->execute([':uid'=>$uid,':t'=>$title,':p'=>$price,':b'=>$beds,':a'=>$area,':i'=>$imgPath,':d'=>$desc]);
    }
    $_SESSION['flash'] = 'Listing saved.'; header('Location: /admin-dashboard.php'); exit;
}

if($action === 'toggle_publish' && $_SERVER['REQUEST_METHOD'] === 'POST'){
    require_login();
    $uid = $_POST['uid'] ?? '';
    $stmt = $db->prepare('SELECT published FROM listings WHERE uid=:u LIMIT 1'); $stmt->execute([':u'=>$uid]); $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row){ $nv = $row['published'] ? 0 : 1; $stmt = $db->prepare('UPDATE listings SET published=:p WHERE uid=:u'); $stmt->execute([':p'=>$nv,':u'=>$uid]); }
    header('Location: /admin-dashboard.php'); exit;
}

if($action === 'delete_listing' && $_SERVER['REQUEST_METHOD'] === 'POST'){
    require_login();
    $uid = $_POST['uid'] ?? '';
    $stmt = $db->prepare('DELETE FROM listings WHERE uid=:u'); $stmt->execute([':u'=>$uid]);
    header('Location: /admin-dashboard.php'); exit;
}

if($action === 'export_leads'){
    require_login();
    $rows = [['id','name','email','phone','message','created_at']];
    $leads = getLeads(); foreach($leads as $l) $rows[] = [$l['id'],$l['name'],$l['email'],$l['phone'],$l['message'],$l['created_at']];
    csv_download($rows,'karam_leads.csv');
}

if($action === 'export_listings'){
    require_login();
    $all = getAllListings(); header('Content-Type: application/json'); echo json_encode($all,JSON_PRETTY_PRINT); exit;
}

if($action === 'clear_leads' && $_SERVER['REQUEST_METHOD'] === 'POST'){
    require_login();
    $db->exec('DELETE FROM leads'); header('Location: /admin-dashboard.php'); exit;
}

http_response_code(400); echo 'Bad action';