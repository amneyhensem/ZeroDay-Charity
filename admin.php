<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Database connection parameters
$servername = 'localhost';
$username = 'root';
$password_db = ''; // Replace with your actual MySQL password
$dbname = 'donasi';

// Create connection
$conn = new mysqli($servername, $username, $password_db, $dbname);
$conn->set_charset("utf8");

// Check connection
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Pagination and search parameters
$limit = 10; // records per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Count total records for pagination
$count_sql = "SELECT COUNT(*) as total FROM bayaran WHERE name LIKE ? OR email LIKE ?";
$stmt_count = $conn->prepare($count_sql);
$search_param = "%$search%";
$stmt_count->bind_param("ss", $search_param, $search_param);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_records = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

// Fetch donation data with search and pagination using prepared statement
$sql = "SELECT name, email, gender, amount, date FROM bayaran WHERE name LIKE ? OR email LIKE ? ORDER BY date DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $search_param, $search_param, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Fetch summary statistics
$summary_sql = "SELECT COUNT(DISTINCT email) as total_donors, SUM(amount) as total_amount FROM bayaran";
$summary_result = $conn->query($summary_sql);
$summary = $summary_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard | Donation Records</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="css/admin.css" />
</head>
<body>
  <header>
    <div class="logo"><i class="fa-solid fa-hand-holding-heart"></i>ZeroDay Admin</div>
    <button aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="adminNav" class="hamburger" id="hamburgerBtn" type="button">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <nav class="admin-nav" id="adminNav" role="navigation" aria-label="Primary Navigation">
      <a href="index.html" tabindex="0"><i class="fa fa-home"></i> Website</a>
      <a href="donor.php" tabindex="0"><i class="fa fa-donate"></i> Donations</a>
      <a href="subscribers.php" tabindex="0"><i class="fa fa-envelope"></i> Subscribers</a>
      <a href="#causes" tabindex="0"><i class="fa fa-hands-helping"></i> Causes</a>
      <a href="logout.php" tabindex="0"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </nav>
  </header>
  <div class="admin-container">
    <h2>Admin Dashboard</h2>
    <div class="summary">
      <p><strong>Total Donors:</strong> <?= number_format($summary['total_donors']) ?></p>
      <p><strong>Total Donations:</strong> $<?= number_format($summary['total_amount'], 2) ?></p>
    </div>
    <form method="GET" action="admin.php" class="search-form" role="search" aria-label="Search donations">
      <input type="search" name="search" placeholder="Search by name or email" value="<?= htmlspecialchars($search) ?>" aria-label="Search donations by name or email" />
      <button type="submit" aria-label="Search donations"><i class="fa fa-search"></i></button>
    </form>
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th><i class="fa-solid fa-user"></i> Name</th>
            <th><i class="fa-solid fa-envelope"></i> Email</th>
            <th><i class="fa-solid fa-venus-mars"></i> Gender</th>
            <th><i class="fa-solid fa-dollar-sign"></i> Amount (USD)</th>
            <th><i class="fa-solid fa-calendar-days"></i> Date</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                  echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                  echo "<td>" . ucfirst(htmlspecialchars($row["gender"])) . "</td>";
                  echo "<td>$" . number_format($row["amount"], 2) . "</td>";
                  echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='5' class='no-data'><i class='fa-solid fa-circle-info'></i> No donations found</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
    <nav class="pagination" aria-label="Pagination">
      <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>" aria-label="Previous page">&laquo; Prev</a>
      <?php endif; ?>
      <?php for ($p = 1; $p <= $total_pages; $p++): ?>
        <a href="?page=<?= $p ?>&search=<?= urlencode($search) ?>" class="<?= $p === $page ? 'active' : '' ?>" aria-current="<?= $p === $page ? 'page' : 'false' ?>"><?= $p ?></a>
      <?php endfor; ?>
      <?php if ($page < $total_pages): ?>
        <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>" aria-label="Next page">Next &raquo;</a>
      <?php endif; ?>
    </nav>
  </div>
  <!-- Motion graphic: animated floating dots background -->
  <canvas id="bg-canvas" style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:-1;pointer-events:none;"></canvas>
  <script src="js/admin.js"></script>
</body>
</html>

<?php
    