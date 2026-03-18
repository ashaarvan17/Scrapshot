<?php
session_start();
include "config.php";

// assuming user is logged in
$uid = $_SESSION['uid'];

// get username
$userQuery = "SELECT uname FROM users WHERE uid='$uid'";
$userResult = mysqli_query($conn,$userQuery);
$user = mysqli_fetch_assoc($userResult);

// get posts
$postQuery = "SELECT * FROM posts WHERE uid='$uid' AND request = 'collected' ORDER BY pid DESC";
$postResult = mysqli_query($conn,$postQuery);

// count posts
$postCount = mysqli_num_rows($postResult);

// store posts in array
$posts = [];

while($row = mysqli_fetch_assoc($postResult)){
    $posts[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>

<style>


</style>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/user.css">
</head>

<body>

<div class="profile-header">
<h2><?php echo $user['uname']; ?></h2>
<p>Total Posts: <?php echo $postCount; ?></p>
</div>


<div class="grid" id="grid"></div>



<script>

// PHP → JS
const posts = <?php echo json_encode($posts); ?>;

const container = document.getElementById("grid");

posts.forEach(post => {

    const imgSrc =  post.img;

    const card = `
        <div class="card">
                    <img src="${imgSrc}" alt="${post.title}">
                    <h3>${post.title}</h3>
                    <p>${post.description}</p>
                    <small>${post.address || ""}</small>
                    
                </div>
    `;

    container.innerHTML += card;
});


function deletePost(pid, btn){

fetch("delete_post.php",{
method:"POST",
headers:{
"Content-Type":"application/x-www-form-urlencoded"
},
body:"pid="+pid
})
.then(res => res.text())
.then(data => {

if(data == "success"){
btn.closest(".card").remove();
}

});

}

</script>

</body>
</html>