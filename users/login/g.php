<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css" />
    <title>Login dan Regis - Pesona Jateng</title>
  </head>
  <body>
    <div class="container" id="container">
      <!-- forms -->
      <div class="forms-container">
        <div class="signin-signup">
          <form action="#" class="sign-in-form">
            <h2 class="title">Registrasi</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" placeholder="Username" />
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Password" />
            </div>
            <input type="submit" value="Login" class="btn solid" />
          </form>

          <form action="#" class="sign-up-form">
            <h2 class="title">Login</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" placeholder="Username" />
            </div>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" placeholder="Email" />
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Password" />
            </div>
            <input type="submit" class="btn" value="Sign up" />
          </form>
        </div>
      </div>

      <!-- panels -->
      <div class="panels-container">
        <div class="panel left-panel">
          <!-- Glassmorphism welcome box -->
          <div class="brand-box">
            <img class="brand-logo" src="img/logo.png" alt="Logo Pesona Jateng" onerror="this.src='https://via.placeholder.com/150x150.png?text=Logo'"/>
            <div class="brand-headers">
              <div class="welcome-small">Selamat Datang</div>
              <h2 class="brand-title">Pesona Jateng</h2>
              <p class="brand-desc">Jelajahi keindahan alam dan budaya Jawa Tengah.</p>
            </div>

            <div class="brand-actions">
              <button class="btn transparent" id="sign-up-btn">Sign up</button>
              <button class="btn transparent alt" id="sign-in-btn">Sign in</button>
            </div>
          </div>

          <!-- existing illustration kept -->
          <img src="img/log.svg" class="image bottom-image" alt="Ilustrasi log" onerror="this.style.display='none'"/>
        </div>

        <div class="panel right-panel">
          <div class="content">
            <h3>One of us ?</h3>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum
              laboriosam ad deleniti.
            </p>
            <button class="btn transparent" id="sign-in-btn-duplicate">
              Sign in
            </button>
          </div>
          <img src="img/register.svg" class="image" alt="Ilustrasi register" onerror="this.style.display='none'"/>
        </div>
      </div>
    </div>

    <script src="app.js"></script>
  </body>
</html>
