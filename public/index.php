<?php
require_once __DIR__ . '/../common.php';
$listings = getPublishedListings();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Karam Real Estate — Listings</title>
  <link rel="stylesheet" href="/styles.css" />
</head>
<body>
<header>
  <div class="brand">
    <div class="logo">MK</div>
    <div>
      <div style="font-weight:800">Karam Real Estate</div>
      <div class="muted">Michael Karam • Trusted local agent</div>
    </div>
  </div>
  <div>
    <a href="/admin-login.php" style="color:white;text-decoration:none">Admin</a>
  </div>
</header>

<main class="container">
  <div class="grid">
    <div>
      <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <div><div style="font-weight:700;font-size:16px">Available Properties</div><div class="muted">Click a listing for details or enquire below</div></div>
        </div>
        <div style="margin-top:12px">
          <input id="search" class="input" placeholder="Search properties, e.g. Sandton" oninput="filterListings()" />
        </div>
        <div id="listings" style="margin-top:12px">
<?php foreach($listings as $p): ?>
  <div class="prop" style="margin-bottom:12px">
    <img src="<?= htmlspecialchars($p['img']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" onerror="this.src='/uploads/placeholder.png'" />
    <div class="meta">
      <div style="font-weight:700"><a href="/property.php?uid=<?= htmlspecialchars($p['uid']) ?>" style="color:inherit;text-decoration:none"><?= htmlspecialchars($p['title']) ?></a></div>
      <div class="muted"><?= htmlspecialchars($p['area']) ?> • <?= htmlspecialchars($p['beds']) ?> bed • <?= htmlspecialchars($p['price']) ?></div>
      <div style="margin-top:8px"><a href="/property.php?uid=<?= htmlspecialchars($p['uid']) ?>">View details</a></div>
    </div>
  </div>
<?php endforeach; ?>
        </div>
      </div>

      <div style="height:12px"></div>

      <div class="card">
        <h3 style="margin:0 0 8px">Enquire</h3>
        <div class="muted">Send Michael an enquiry about any property.</div>
        <form method="post" action="/api.php?action=lead" style="margin-top:10px">
          <label>Full name</label>
          <input name="name" required class="input" />
          <label>Email</label>
          <input name="email" class="input" />
          <label>Phone</label>
          <input name="phone" required class="input" />
          <label>Message / Property</label>
          <textarea name="message" rows="4" class="input"></textarea>
          <div style="margin-top:8px"><button type="submit">Send enquiry</button></div>
        </form>
      </div>
    </div>

    <aside>
      <div class="card">
        <h3 style="margin:0">Contact</h3>
        <div class="muted">Michael Karam • Karam Real Estate</div>
        <div style="margin-top:8px">Phone: +27 82 000 0000<br>Email: michael@karamrealestate.co.za</div>
      </div>

      <div style="height:12px"></div>
      <div class="card">
        <h4 style="margin:0">Recent enquiries</h4>
        <div class="muted" style="margin-top:8px">Latest enquiries are available in the admin dashboard.</div>
      </div>
    </aside>
  </div>
</main>

<script>
function filterListings(){
  const q = document.getElementById('search').value.toLowerCase();
  document.querySelectorAll('#listings .prop').forEach(el=>{
    const txt = el.innerText.toLowerCase();
    el.style.display = txt.includes(q) ? 'flex' : 'none';
  });
}
</script>

</body>
</html>
