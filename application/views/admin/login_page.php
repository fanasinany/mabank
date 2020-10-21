<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="icon" type="image/png" href="<?=base_url('assets/img/mabank-icon.png');?>">
    <link href="<?= base_url('/assets/css/material-dashboard.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('/assets/fontawesome/css/all.css') ?>" rel="stylesheet" />
    <script src="<?= base_url('assets/js/core/jquery.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/core/popper.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/core/bootstrap-material-design.min.js') ?>" type="text/javascript"></script>
</head>
<body>
  <div class="container">
    <div class="row">
    <div class="col-lg-4 col-md-6 col-sm-8 mt-5 ml-auto mr-auto">
      <div class="card" style="margin-top: 120px;">
        <div class="card-header card-header-primary text-center">
          <h4 class="card-title">Connexion sur MaBank</h4>
        </div>
        <div class="card-body">
        <p class="card-description text-center">Authentification</p>
          <span class="bmd-form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="fas fa-user"></i>
                </span>
              </div>
              <input type="text" class="form-control" placeholder="Nom d'utilisateur" name="username" id="username" required>
            </div>
          </span>
          <span class="bmd-form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="fas fa-key"></i>
                </span>
              </div>
              <input type="password" class="form-control" placeholder="Mot de passe" name="password" id="password" required>
            </div>
          </span>
        </div>
        <div class="card-footer">
          <a href="<?= base_url("/Client") ?>">Espace Client</a>
          <button id="btnconnexion" class="btn btn-primary btn-md">Connexion</button>
        </div>
      </div>
    </div>
    </div>
  </div>
  <script type="text/javascript">

  var site_url = "<?= base_url();?>";
  $(document).on("click","#btnconnexion", function(){
    $.ajax({
      type: 'POST',
      url: site_url + 'Admin/login',
      data: {
        pseudo: $("#username").val(),
        password: $("#password").val()
      },
      success: function(){
        window.location.href = site_url + 'Admin/home'
      },
      error: function (data){
        if(data.status == 404){
          $(".card-body").append("<div id=\"nok-connection\" class=\"alert alert-danger text-center mt-3\" role=\"alert\">Identifiant incorrect</div>"),
          setTimeout(function(){
            $("#nok-connection").remove();
          }, 1500)
        }
        else if(data.status == 500){
          $(".card-body").append("<div id=\"nok-connection\" class=\"alert alert-danger text-center mt-3\" role=\"alert\">Tous les champs requis</div>"),
          setTimeout(function(){
            $("#nok-connection").remove();
          }, 1500)
        }
      }
    })
  })

  </script>
</body>
</html>