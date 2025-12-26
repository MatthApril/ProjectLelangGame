<nav class="bg-darkblue text-white">
  <div class="container-fluid">
    <div class="row d-flex align-items-center justify-content-center">      
      <div class="col-md-3 py-2">
        <a href="<?php echo e(route('user.home')); ?>" class="d-flex align-items-center text-white text-decoration-none">
          <img src="<?php echo e(asset('images/Logo/LogoWarna-RemoveBg.png')); ?>" alt="LelangGame Logo" width="50">
          <h5 class="fw-semibold">LelangGame</h5>
        </a>
      </div>
      <?php if(auth()->guard()->check()): ?>
      <div class="col-md-6">
        <form>
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-search"></i>
            </span>
            <input type="search" class="form-control" placeholder="Coba Cari Game" aria-label="Search" autofocus>
          </div>
        </form>
      </div>
      <div class="col-md-3 py-2">
        <div class="d-flex align-items-center justify-content-end gap-3">
            <a href="#" class="text-decoration-none text-white"><i class="bi bi-envelope" style="font-size: 1.5rem;"></i></a>
            <a href="#" class="text-decoration-none text-white"><i class="bi bi-bell" style="font-size: 1.5rem;"></i></a>
            <a href="#" class="text-decoration-none text-white"><i class="bi bi-cart3" style="font-size: 1.5rem;"></i></a>
            <span class="fs-5"> | </span>
            <div class="dropdown dropstart">
              <button class="btn btn-outline-light text-decoration-none text-wrap dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-person-circle"></i> <?php echo e(Auth::user()->username); ?>

              </button>

              <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="<?php echo e(route('profile')); ?>"><i class="bi bi-person-fill"></i> Profile</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <form action="<?php echo e(route('logout')); ?>" method="post">
                  <?php echo csrf_field(); ?>
                  <li><button type="submit" class="dropdown-item text-danger fw-semibold" href="<?php echo e(route('logout')); ?>"><i class="bi bi-box-arrow-left"></i> Logout</button></li>
                  </form>
              </ul>
          </div>
            
        </div>
      </div>
      <?php else: ?>
      <div class="col-md-6">
        <form>
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-search"></i>
            </span>
            <input type="search" class="form-control" placeholder="Coba Cari Game" aria-label="Search" autofocus>
          </div>
        </form>
      </div>
      <div class="col-md-3 py-2">
        <div class="d-flex align-items-center justify-content-end gap-3">
            <a href="<?php echo e(route('login')); ?>" class="text-decoration-none text-white"><i class="bi bi-cart3" style="font-size: 1.5rem;"></i></a>
            <span class="fs-5"> | </span>
            <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-light text-decoration-none fw-semibold">
              <i class="bi bi-box-arrow-in-right"></i> Masuk
            </a>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</nav>
<nav class="bg-navyblue text-white py-2">
  <div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center gap-3">
      <div class="dropdown">
        <a href="#" class="text-decoration-none text-white dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-grid"></i> Kategori
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#">Top Up Game</a></li>
          <li><a class="dropdown-item" href="#">Joki</a></li>
          <li><a class="dropdown-item" href="#">Akun</a></li>
          <li><a class="dropdown-item" href="#">Item</a></li>
        </ul>
      </div>
      <a href="#" class="text-decoration-none text-white"><i class="bi bi-steam"></i> Steam Gift Cards</a>
      <a href="#" class="text-decoration-none text-white"><i class="bi bi-gem"></i> Diamond MLBB</a>
      <a href="#" class="text-decoration-none text-white"><i class="bi bi-person-fill"></i> Akun Roblox</a>
    </div>
</nav>


<?php /**PATH D:\xampp\htdocs\ProjectLelangGame\resources\views/partials/header.blade.php ENDPATH**/ ?>