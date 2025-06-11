    <?php
    // Database connection parameters
    $servername = 'localhost';
    $username = 'root';
    $password_db = ''; // Replace with your actual MySQL password
    $dbname = 'subscribe';

    // Create connection
    $conn = new mysqli($servername, $username, $password_db, $dbname);
    $conn->set_charset("utf8");

    // Check connection
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    // Fetch donation data
    $sql = "SELECT email FROM subrek";
    $result = $conn->query($sql);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>Admin Dashboard | Donation Records</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
      <style>
        /* Base styling from original + added hamburger styles */
        body {
          font-family: 'Segoe UI', Arial, sans-serif;
          background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
          color: #222;
          margin: 0;
          padding: 0;
          min-height: 100vh;
          overflow-x: hidden;
        }
        .admin-container {
          max-width: 1100px;
          margin: 40px auto;
          background: rgba(255,255,255,0.97);
          border-radius: 1.5rem;
          box-shadow: 0 8px 32px rgba(238,9,121,0.15), 0 1.5px 8px rgba(255,106,0,0.08);
          padding: 2.5rem 2rem 2rem 2rem;
          animation: fadeIn 1.2s cubic-bezier(.68,-0.55,.27,1.55);
        }
        @keyframes fadeIn {
          from { opacity: 0; transform: translateY(40px); }
          to { opacity: 1; transform: translateY(0); }
        }
        h2 {
          color: #ee0979;
          text-align: center;
          font-size: 2.5rem;
          margin-bottom: 1.5rem;
          letter-spacing: 1px;
          font-weight: 800;
          text-shadow: 0 2px 8px rgba(255,106,0,0.08);
        }
        .table-responsive {
          overflow-x: auto;
          border-radius: 1rem;
          box-shadow: 0 2px 12px rgba(238,9,121,0.07);
          background: #fff;
          animation: tableSlideIn 1.2s 0.2s cubic-bezier(.68,-0.55,.27,1.55) backwards;
        }
        @keyframes tableSlideIn {
          from { opacity: 0; transform: scale(0.97); }
          to { opacity: 1; transform: scale(1); }
        }
        table {
          width: 100%;
          border-collapse: collapse;
          min-width: 600px;
        }
        th, td {
          padding: 16px 14px;
          text-align: left;
          border-bottom: 1px solid #f3f3f3;
          font-size: 1.08rem;
          transition: background 0.3s;
        }
        th {
          background: linear-gradient(90deg, #ee0979 60%, #ff6a00 100%);
          color: #fff;
          font-weight: 700;
          letter-spacing: 0.5px;
          border: none;
          position: sticky;
          top: 0;
          z-index: 2;
        }
        tr {
          transition: box-shadow 0.3s, transform 0.3s;
        }
        tr:hover {
          background: #fff3e6;
          box-shadow: 0 2px 12px rgba(255,106,0,0.08);
          transform: scale(1.01);
        }
        .icon {
          color: #ee0979;
          margin-right: 8px;
          animation: iconPulse 1.5s infinite alternate;
        }
        @keyframes iconPulse {
          from { transform: scale(1); }
          to { transform: scale(1.15); }
        }
        .no-data {
          text-align: center;
          color: #ee0979;
          font-size: 1.2rem;
          padding: 2rem 0;
        }
        @media (max-width: 800px) {
          .admin-container {
            padding: 1.2rem 0.5rem;
          }
          table, th, td {
            font-size: 0.98rem;
          }
        }
        @media (max-width: 600px) {
          .admin-container {
            padding: 0.5rem 0.1rem;
          }
          table {
            min-width: 400px;
          }
          th, td {
            padding: 10px 6px;
          }
          h2 {
            font-size: 1.5rem;
          }
        }

        /* Header with flex and spacing */
        header {
          background: linear-gradient(90deg, #222 0%, #ee0979 100%);
          color: #fff;
          padding: 1.2rem 5vw;
          box-shadow: 0 2px 8px rgba(0,0,0,0.07);
          display: flex;
          align-items: center;
          justify-content: space-between;
          position: relative;
          z-index: 10;
        }
        .logo {
          font-size: 2rem;
          font-weight: 700;
          letter-spacing: 2px;
          display: flex;
          align-items: center;
        }
        .logo i {
          margin-right: 10px;
          color: #ffe082;
        }
        .admin-nav {
          display: flex;
          gap: 1.3rem;
          align-items: center;
          font-weight: 500;
        }
        .admin-nav a {
          color: #fff;
          text-decoration: none;
          padding: 0.5rem 0.75rem;
          border-radius: 6px;
          transition: background-color 0.2s, color 0.2s;
        }
        .admin-nav a:hover,
        .admin-nav a:focus {
          background: #ffa726;
          color: #222;
          outline: none;
        }

        /* Hamburger icon styles */
        .hamburger {
          display: none;
          flex-direction: column;
          justify-content: space-around;
          width: 28px;
          height: 22px;
          background: transparent;
          border: none;
          cursor: pointer;
          padding: 0;
        }
        .hamburger:focus {
          outline: 2px solid #ffe082;
          outline-offset: 2px;
        }
        .hamburger span {
          width: 100%;
          height: 3px;
          background: #fff;
          border-radius: 2px;
          transition: all 0.3s linear;
          position: relative;
          transform-origin: 1px;
        }
        /* Hamburger open animation */
        .hamburger.open span:nth-child(1) {
          transform: rotate(45deg);
        }
        .hamburger.open span:nth-child(2) {
          opacity: 0;
        }
        .hamburger.open span:nth-child(3) {
          transform: rotate(-45deg);
        }

        /* Responsive nav menu for mobile */
        @media (max-width: 768px) {
          .admin-nav {
            position: fixed;
            top: 70px;
            right: 0;
            background: linear-gradient(90deg, #222 0%, #ee0979 100%);
            width: 220px;
            height: calc(100% - 70px);
            flex-direction: column;
            padding-top: 1rem;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            gap: 0;
            border-radius: 0 0 0 12px;
            box-shadow: -4px 0 12px rgba(0, 0, 0, 0.2);
            overflow-y: auto;
          }
          .admin-nav.show {
            transform: translateX(0);
          }
          .admin-nav a {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            width: 100%;
          }
          .admin-nav a:last-child {
            border-bottom: none;
          }
          .hamburger {
            display: flex;
          }
        }
      </style>
    </head>
    <body>
      <header>
        <div class="logo"><i class="fa-solid fa-hand-holding-heart"></i>ZeroDay Admin</div>
        <button aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="adminNav" class="hamburger" id="hamburgerBtn" type="button">
          <span></span>
          <span></span>
          <span></span>
        </button>
        <nav class="admin-nav" id="adminNav">
          <a href="index.html"><i class="fa fa-home"></i> Website</a>
          <a href="donor.php"><i class="fa fa-donate"></i> Donations</a>
          <a href="subscribers.php"><i class="fa fa-envelope"></i> Subscribers</a>
          <a href="#causes"><i class="fa fa-hands-helping"></i> Causes</a>
          <a href="#" onclick="logout()">Logout</a>
        </nav>
      </header>
      <div class="admin-container">
        <h2>Subscriber</h2>
        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th><i class="fa-solid fa-envelope"></i> Email</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Check if the query was successful
              if (!$result) {
                  echo "<tr><td colspan='5' class='no-data'>Query failed: " . $conn->error . "</td></tr>";
              } else if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td>".htmlspecialchars($row["email"])."</td>";
                      echo "</tr>";
                  }
              } else {
                  echo "<tr><td colspan='5' class='no-data'><i class='fa-solid fa-circle-info'></i> No donations found</td></tr>";
              }
              $conn->close();
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Motion graphic: animated floating dots background -->
      <canvas id="bg-canvas" style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:-1;pointer-events:none;"></canvas>

      <script>
        const canvas = document.getElementById('bg-canvas');
        const ctx = canvas.getContext('2d');
        let dots = [];
        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        function createDots() {
            dots = [];
            for (let i = 0; i < 30; i++) {
                dots.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    r: 8 + Math.random() * 12,
                    dx: (Math.random() - 0.5) * 0.7,
                    dy: (Math.random() - 0.5) * 0.7,
                    color: Math.random() > 0.5 ? 'rgba(238,9,121,0.13)' : 'rgba(255,106,0,0.13)'
                });
            }
        }
        function animateDots() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let dot of dots) {
                ctx.beginPath();
                ctx.arc(dot.x, dot.y, dot.r, 0, 2 * Math.PI);
                ctx.fillStyle = dot.color;
                ctx.fill();
                dot.x += dot.dx;
                dot.y += dot.dy;
                if (dot.x < 0 || dot.x > canvas.width) dot.dx *= -1;
                if (dot.y < 0 || dot.y > canvas.height) dot.dy *= -1;
            }
            requestAnimationFrame(animateDots);
        }
        window.addEventListener('resize', () => {
            resizeCanvas();
            createDots();
        });
        resizeCanvas();
        createDots();
        animateDots();

        // Hamburger menu toggle
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const adminNav = document.getElementById('adminNav');

        hamburgerBtn.addEventListener('click', () => {
          const isOpen = adminNav.classList.toggle('show');
          hamburgerBtn.classList.toggle('open');
          hamburgerBtn.setAttribute('aria-expanded', isOpen);
        });

        // Dummy logout
        function logout() {
          alert('Logged out!');
          window.location.href = 'index.html';
        }
      </script>
    </body>
    </html>

    <?php
    