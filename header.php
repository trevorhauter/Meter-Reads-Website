<nav class="navbar navbar-expand-lg navbar-light bg-light headerStyle">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><img src="Images/realmtrx.png" alt="realmtrx logo" width="150" height="44"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item dropdown ">
          <a class="nav-link dropdown-toggle <?php if(isset($_GET['page'])){ if($_GET['page']=='meterreads'){ echo 'active'; }} ?>" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Properties  
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="main.php?client=greenacres&utility=ele&page=meterreads&monthdiff=0">Green Acres</a></li>
            <li><a class="dropdown-item" href="main.php?client=williams&page=meterreads&monthDiff=0">Williams</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if(isset($_GET['page'])){ if($_GET['page']=='contact'){ echo 'active'; }} ?>" href="contact.php?page=contact">Contact Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if(isset($_GET['page'])){ if($_GET['page']=='about'){ echo 'active'; }} ?>" href="about.php?page=about">About</a>
        </li>
      </ul>
      <form class="d-flex">
        <button class="btn btn-outline-success">Profile</button>
      </form>
    </div>
  </div>
</nav>

