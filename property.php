<?php
require_once __DIR__ . '/../common.php';
$uid = $_GET['uid'] ?? '';
$prop = getListingByUid($uid);
if(!$prop){ http_response_code(404); echo 'Property not found'; exit; }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?= htmlspecialchars($prop['title']) ?> — Karam Real Estate</title>
  <link rel="stylesheet" href="/styles.css" />
</head>
<body>
<header>
  <div class="brand"><div class="logo">MK</div><div><div style="font-weight:800">Karam Real Estate</div></div></div>
</header>
<main class="container">
  <div class="card">
    <div style="display:flex;gap:16px">
      <img src="<?= htmlspecialchars($prop['img']) ?>" style="width:360px;height:240px;object-fit:cover;" onerror="this.src='/uploads/placeholder.png'" />
      <div>
        <h2 style="margin-top:0"><?= htmlspecialchars($prop['title']) ?></h2>
        <div class="muted"><?= htmlspecialchars($prop['area']) ?> • <?= htmlspecialchars($prop['beds']) ?> bed • <?= htmlspecialchars($prop['price']) ?></div>
        <p style="margin-top:12px"><?= nl2br(htmlspecialchars($prop['description'])) ?></p>
        <div style="margin-top:12px"><a href="/index.php">Back to listings</a></div>
      </div>
    </div>
  </div>

  <div style="height:12px"></div>
  <div class="card">
    <h3>Enquire about this property</h3>
    <form method="post" action="/api.php?action=lead">
      <input type="hidden" name="property" value="<?= htmlspecialchars($prop['title']) ?>" />
      <label>Full name</label>
      <input name="name" required class="input" />
      <label>Email</label>
      <input name="email" class="input" />
      <label>Phone</label>
      <input name="phone" required class="input" />
      <label>Message</label>
      <textarea name="message" rows="4" class="input">I am interested in: <?= htmlspecialchars($prop['title']) ?></textarea>
      <div style="margin-top:8px"><button type="submit">Send enquiry</button></div>
    </form>
  </div>
</main>
</body>
</html>
