<!-- //request.php  -->
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

   <a href="user.php" class="nav-item">
    <span class="icon" data-lucide="bookCopy"></span>
    <span class="text">Posts</span>
  </a>

  <a href="request.php" class="nav-item">
    <span class="icon" data-lucide="messageSquareDot"></span>
    <span class="text">Requests</span>
  </a>

  <a href="profile.html" class="nav-item">
    <span class="icon" data-lucide="user"></span>
    <span class="text">Profile</span>
  </a>

  <a href="logout.js" class="nav-item">
    <span class="icon" data-lucide="log-out"></span>
    <span class="text">Logout</span>
  </a>

</div>

<div class="container"> <div class=""><div class="page-header">
    <h2>Requests by collectors</h2> 
</div>


<div class="grid" id="grid"></div>


<script>


// LOAD POSTS
// Example for loadPosts:
function loadPosts() {
    fetch("handle_request.php", { credentials: "same-origin" })
    .then(res => res.text())  // <-- get the response body as text
    .then(text => {
        let data;
        try {
            data = JSON.parse(text);  // now parse JSON
        } catch (e) {
            console.error("Invalid JSON response:", text);
            alert("An error occurred while loading posts. See console for details.");
            return;
        }

       

        if (!Array.isArray(data)) {
            console.error("Expected an array but got:", data);
            return;
        }

        const grid = document.getElementById("grid");
        grid.innerHTML = "";

        if (data.length === 0) {
            grid.innerHTML = "<p>No pending requests.</p>";
            return;
        }

        const html = data.map(post => {
            const imgSrc = post.img ? `${post.img}` : "php/default.png";
            return `
                <div class="card">
                    <img src="${imgSrc}" alt="${post.title}">
                    <h3>${post.title}</h3>
                    <p>${post.description}</p>
                    <small>${post.address || ""}</small>
                    <button onclick="acceptRequest('${post.pid}')">Accept Request</button>
                </div>
            `;
        }).join("");

        grid.innerHTML = html;
    })
    .catch(err => {
        console.error("Fetch error:", err);
        alert("Failed to load posts. See console for details.");
    });
}


function acceptRequest(pid) {
    fetch("final_request.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ pid: pid }),
        credentials: "same-origin"
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("Request sent!");
            
            loadPosts();
        } else {
            alert(data.error || "Something went wrong");
        }
    })
    .catch(err => console.error("Request error:", err));

};


loadPosts();
</script>

</body>
</html>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();
</script>