<?php
// common.php - shared helpers

session_start();

function get_db(){
    $dbFile = __DIR__ . '/data/database.sqlite';
    if(!is_dir(__DIR__ . '/data')) mkdir(__DIR__ . '/data', 0755, true);
    $create = !file_exists($dbFile);
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($create){
        // run init if not present
        require_once __DIR__ . '/init_db.php';
    }
    return $db;
}

function is_logged_in(){
    return !empty($_SESSION['user_id']);
}

function require_login(){
    if(!is_logged_in()){
        header('Location: /admin-login.php');
        exit;
    }
}

function getPublishedListings(){
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM listings WHERE published=1 ORDER BY created_at DESC');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getListingByUid($uid){
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM listings WHERE uid = :uid LIMIT 1');
    $stmt->execute([':uid'=>$uid]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAllListings(){
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM listings ORDER BY created_at DESC');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addLead($name,$email,$phone,$message){
    $db = get_db();
    $stmt = $db->prepare('INSERT INTO leads (name,email,phone,message) VALUES (:n,:e,:p,:m)');
    $stmt->execute([':n'=>$name,':e'=>$email,':p'=>$phone,':m'=>$message]);
    return $db->lastInsertId();
}

function getLeads(){
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM leads ORDER BY created_at DESC');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function csv_download($rows,$filename='export.csv'){
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.basename($filename).'"');
    $out = fopen('php://output','w');
    foreach($rows as $r) fputcsv($out,$r);
    fclose($out);
    exit;
}
