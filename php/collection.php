<?php
session_start();
if (!isset($_SESSION['cid'])) {
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

<a href="profile.html" class="nav-item">
<span class="icon" data-lucide="user"></span>
<span class="text">Profile</span>
</a>

<a href="logout.php" class="nav-item">
<span class="icon" data-lucide="log-out"></span>
<span class="text">Logout</span>
</a>

</div>

<div class="container">
<div class="page-header">
<h2>Request Accepted Posts</h2>
</div>

<div class="grid" id="grid"></div>

</div>

<script>

// LOAD POSTS
function loadPosts() {

fetch("collection_handle.php", {credentials:"same-origin"})
.then(res => res.json())
.then(data => {

const grid = document.getElementById("grid");
grid.innerHTML = "";

if(data.length === 0){
grid.innerHTML = "<p>No posts to collect.</p>";
return;
}

const html = data.map(post => {

const imgSrc = post.img ? post.img : "default.png";

return `
<div class="card">
<img src="${imgSrc}">
<h3>${post.title}</h3>
<p>${post.description}</p>
<button onclick="acceptRequest(${post.pid})">
Mark Collected
</button>
</div>
`;

}).join("");

grid.innerHTML = html;

})
.catch(err => console.error("Load error:", err));

}


// MARK COLLECTED
function acceptRequest(pid){

fetch("collection_handle.php",{

method:"POST",

headers:{
"Content-Type":"application/json"
},

body:JSON.stringify({pid:pid}),

credentials:"same-origin"

})

.then(res => res.json())
.then(data => {

loadPosts();

})
.catch(err => console.error("Update error:", err));

}

loadPosts();

</script>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
lucide.createIcons();
</script>

</body>
</html>