<?php
// init_db.php
// Run this once to create the SQLite database (or the app will auto-create on first request).
$dbFile = __DIR__ . '/data/database.sqlite';
if (!is_dir(__DIR__ . '/data')) mkdir(__DIR__ . '/data', 0755, true);
$first = !file_exists($dbFile);
$db = new PDO('sqlite:' . $dbFile);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($first) {
    $db->exec("CREATE TABLE users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      username TEXT UNIQUE NOT NULL,
      password_hash TEXT NOT NULL,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    CREATE TABLE listings (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      uid TEXT UNIQUE NOT NULL,
      title TEXT NOT NULL,
      price TEXT,
      beds INTEGER,
      area TEXT,
      img TEXT,
      description TEXT,
      published INTEGER DEFAULT 0,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    CREATE TABLE leads (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      email TEXT,
      phone TEXT NOT NULL,
      message TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );");

    // create default admin: username=admin, password=admin123
    $pw = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, password_hash) VALUES (:u, :p)");
    $stmt->execute([':u' => 'admin', ':p' => $pw]);

    // sample listings
    $sample = [
      ['uid'=>'MK-100','title'=>'Luxury 3-Bed Home — Sandton','price'=>'R3,200,000','beds'=>3,'area'=>'Sandton, Johannesburg','img'=>'/uploads/sample-sandton.jpg','description'=>'Modern finishes • Secure estate • Double garage','published'=>1],
      ['uid'=>'MK-101','title'=>'Spacious 2-Bed Apartment — Rosebank','price'=>'R1,850,000','beds'=>2,'area'=>'Rosebank, Johannesburg','img'=>'/uploads/sample-rosebank.jpg','description'=>'Walk to Gautrain • High-end finishes • Balcony','published'=>1],
    ];
    $stmt = $db->prepare("INSERT INTO listings (uid,title,price,beds,area,img,description,published) VALUES (:uid,:title,:price,:beds,:area,:img,:desc,:pub)");
    foreach ($sample as $s) $stmt->execute([
      ':uid'=>$s['uid'], ':title'=>$s['title'], ':price'=>$s['price'], ':beds'=>$s['beds'], ':area'=>$s['area'],
      ':img'=>$s['img'], ':desc'=>$s['description'], ':pub'=>$s['published']
    ]);

    echo "Database initialized: {$dbFile}\n";
} else {
    echo "Database already exists: {$dbFile}\n";
}
