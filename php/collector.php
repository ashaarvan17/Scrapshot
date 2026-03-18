<?php
//collector.php
session_start();
if (!isset($_SESSION['cid'])) {
    // Redirect to login if not logged in
    header("Location: login_collector.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Posts</title>
<link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/user.css">

    <style>
        .card {
            border: 1px solid #ccc;
            padding: 10px;
        }

        .card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
    </style> 
</head>
<body>

<div class="navigation">

  <a href="collector.php" class="nav-item">
    <span class="icon" data-lucide="bookCopy"></span>
    <span class="text">Posts</span>
  </a>

<a href="collection.php" class="nav-item">
    <span class="icon" data-lucide="messageSquareCheck"></span>
    <span class="text">Collect</span>
  </a>


  <a href="collector_profile.php" class="nav-item">
    <span class="icon" data-lucide="user"></span>
    <span class="text">Profile</span>
  </a>

  <a href="logout.js" class="nav-item">
    <span class="icon" data-lucide="log-out"></span>
    <span class="text">Logout</span>
  </a>
</div>

<div class="container">
  <div class="page-header">
    <h2>Available Scrap Posts</h2> 
  </div>

  <div class="grid" id="grid"></div>
</div>

<script>
// LOAD POSTS
function loadPosts() {
    fetch("handle_request.php", { credentials: "same-origin" })
    .then(res => res.json())
    .then(data => {
        let grid = document.getElementById("grid");
        grid.innerHTML = "";

        data.forEach(post => {
            grid.innerHTML += `
                <div class="card">
                    <img src="${post.img}">
                    <h3>${post.title}</h3>
                    <p>${post.description}</p>
                    <small>${post.address}</small>
                    <button id = request onclick="sendRequest('${post.pid}', this)"><span>Request</span></button>

                </div>
            `;
        });
    })
    .catch(err => console.error("Load posts error:", err));
}

window.sendRequest = function (pid) {
    fetch("handle_request.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ pid: pid }),
        credentials: "same-origin"
    })
    .then(res => res.json())
    .then(data => {
        loadPosts();
        
    })
    .catch(err => console.error("Request error:", err));

};

loadPosts();
</script>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();
</script>

</body>
</html>


