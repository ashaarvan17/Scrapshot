<!-- //user.php  -->
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



  <a href="user_profile.php" class="nav-item">
    <span class="icon" data-lucide="user"></span>
    <span class="text">Profile</span>
  </a>

  <a href="logout.js" class="nav-item">
    <span class="icon" data-lucide="log-out"></span>
    <span class="text">Logout</span>
  </a>

</div>

<div class="container"> <div class=""><div class="page-header">
    <h2>Your Scrap Posts</h2> 
</div>
<button class= "add-btn" onclick="openModal()">+ Add Post </button>

<div class="grid" id="grid"></div>

<!-- MODAL -->
<div class="modal" id="modal">
    <div class="modal-box">
        <input id="title" placeholder="Title"><br><br>
        <textarea id="desc" placeholder="Description"></textarea><br><br>
        <input type="file" id="photo"><br><br>
        <input id="address" placeholder="Address"><br><br>

        <button onclick="addPost()">Save</button>
        <button onclick="closeModal()">Cancel</button>
    </div>
</div>

<script>


function openModal() {
    document.getElementById("modal").style.display = "flex";
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
}

// LOAD POSTS
// Example for loadPosts:
function loadPosts() {
   fetch("post_handler.php", {
    method: "GET",
    credentials: "include"
})
    .then(res => res.text()) // get raw text
    .then(text => {
        let data;
        try {
           data = JSON.parse(text);
        } catch (e) {
            console.error("Invalid JSON response:", text);
            alert("An error occurred while loading posts. See console for details.");
            return;
        }

        // Handle error response from PHP
        if (data.error) {
            alert("Error: " + data.error);
            return;
        }

        // Ensure data is an array
        if (!Array.isArray(data)) {
            console.error("Expected an array but got:", data);
            return;
        }

        const grid = document.getElementById("grid");
        grid.innerHTML = ""; // clear previous content

        if (data.length === 0) {
            grid.innerHTML = "<p>No posts available.</p>";
            return;
        }

        // Build HTML using map + join for better performance
        const html = data.map(post => {
            const imgSrc = post.img ? `${post.img}` : "default.png";
            return `
                <div class="card">
                    <img src="${imgSrc}" alt="${post.title}">
                    <h3>${post.title}</h3>
                    <p>${post.description}</p>
                    <small>${post.address || ""}</small>
                    <button onclick="deletePost('${post.pid}', this)">Delete</button>
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


// ADD POST
function addPost() {

    let formData = new FormData();
    formData.append("title", document.getElementById("title").value);
    formData.append("desc", document.getElementById("desc").value);
    formData.append("photo", document.getElementById("photo").files[0]);
    formData.append("address", document.getElementById("address").value);

    fetch("post_handler.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())   // 👈 IMPORTANT
    .then(data => {
        console.log(data);
        if (data.status === "success") {
            closeModal();
            loadPosts();
        }
    })
    .catch(err => console.error(err));
}

loadPosts();

function deletePost(pid) {

    if (!confirm("Are you sure you want to delete this post?")) return;

    fetch("post_handler.php?delete=" + pid)
    .then(res => res.json())
    .then(data => {
        if (data.status === "deleted") {
            loadPosts();
        }
    });
}

</script>

</body>
</html>


<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();
</script>