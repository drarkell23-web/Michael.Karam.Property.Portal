<?php
require_once __DIR__ . '/../common.php';
require_login();
$db = get_db();
$all = getAllListings();
$leads = getLeads();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Dashboard — Karam Real Estate</title>
  <link rel="stylesheet" href="/styles.css" />
</head>
<body>
<header>
  <div class="brand"><div class="logo">MK</div><div><div style="font-weight:800">Karam Real Estate — Admin</div></div></div>
  <div><a href="/api.php?action=logout" style="color:white;text-decoration:none">Logout</a></div>
</header>
<main class="container">
  <div class="card">
    <h3 style="margin-top:0">Create / Edit Listing</h3>
    <form method="post" action="/api.php?action=save_listing" enctype="multipart/form-data">
      <input name="id" placeholder="ID (leave blank to create)" class="input" />
      <label>Title</label>
      <input name="title" class="input" />
      <label>Price</label>
      <input name="price" class="input" />
      <label>Bedrooms</label>
      <input name="beds" class="input" />
      <label>Area</label>
      <input name="area" class="input" />
      <label>Image (upload or provide URL)</label>
      <input type="file" name="image" />
      <input name="img_url" placeholder="Or image URL" class="input" />
      <label>Description</label>
      <textarea name="description" rows="4" class="input"></textarea>
      <div style="margin-top:8px"><button type="submit">Save Listing</button></div>
    </form>
  </div>

  <div style="height:12px"></div>

  <div class="card">
    <h3 style="margin-top:0">Listings</h3>
    <?php if(!$all): ?><div class="muted">No listings yet.</div><?php else: ?>
      <?php foreach($all as $l): ?>
        <div style="border:1px solid #eef2f6;padding:10px;border-radius:8px;margin-bottom:8px;display:flex;justify-content:space-between;align-items:center">
          <div>
            <div style="font-weight:700"><?= htmlspecialchars($l['title']) ?></div>
            <div class="muted"><?= htmlspecialchars($l['area']) ?> • <?= htmlspecialchars($l['beds']) ?> bed • <?= htmlspecialchars($l['price']) ?></div>
          </div>
          <div style="display:flex;gap:6px">
            <form method="post" action="/api.php?action=toggle_publish" style="display:inline">
              <input type="hidden" name="uid" value="<?= htmlspecialchars($l['uid']) ?>" />
              <button type="submit" class="small"><?= $l['published'] ? 'Unpublish' : 'Publish' ?></button>
            </form>
            <form method="post" action="/api.php?action=delete_listing" style="display:inline" onsubmit="return confirm('Delete listing?')">
              <input type="hidden" name="uid" value="<?= htmlspecialchars($l['uid']) ?>" />
              <button type="submit" class="small">Delete</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <div style="height:12px"></div>

  <div class="card">
    <h3 style="margin-top:0">Leads</h3>
    <div style="margin-bottom:8px">
      <a href="/api.php?action=export_leads">Export CSV</a> | <a href="/api.php?action=export_listings">Export listings JSON</a>
    </div>
    <?php if(!$leads): ?><div class="muted">No leads yet.</div><?php else: ?>
      <?php foreach($leads as $ld): ?>
        <div class="lead-row">
          <strong><?= htmlspecialchars($ld['name']) ?></strong>
          <div class="muted"><?= htmlspecialchars($ld['phone']) ?> • <?= htmlspecialchars($ld['email']) ?></div>
          <div style="margin-top:6px"><?= nl2br(htmlspecialchars($ld['message'])) ?></div>
          <div class="muted" style="margin-top:6px;font-size:12px">Received: <?= htmlspecialchars($ld['created_at']) ?></div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
    <div style="margin-top:8px"><form method="post" action="/api.php?action=clear_leads" onsubmit="return confirm('Clear all leads?')"><button type="submit" class="small">Clear all leads</button></form></div>
  </div>

</main>
</body>
</html>
