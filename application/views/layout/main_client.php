<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : "MaBank" ?></title>
    <link rel="icon" type="image/png" href="<?=base_url('assets/img/mabank-icon.png');?>">
    <link href="<?= base_url('/assets/css/material-dashboard.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('/assets/fontawesome/css/all.css') ?>" rel="stylesheet" />
    <script src="<?= base_url('assets/js/core/jquery.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/core/popper.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/plugins/sweetalert2.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/core/bootstrap-material-design.min.js') ?>" type="text/javascript"></script>
    <script type="text/javascript">
      var site_url = "<?= base_url();?>";
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">MaBank</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-bar navbar-kebab"></span>
    <span class="navbar-toggler-bar navbar-kebab"></span>
    <span class="navbar-toggler-bar navbar-kebab"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <span class="navbar-text">
          <?= strtoupper($nom) ?> <?= strtoupper($prenom) ?>
        </span>
        <li class="nav-item">
          <a id="btnlogout" class="nav-link" data-toggle="tooltip" data-placement="bottom" title="Déconnexion"><i class="fas fa-sign-out-alt"></i></a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">

    <?php $this->load->view($content); ?>

</div>
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Déconnexion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Voulez-vous vraiment déconnecter?
      </div>
      <div class="modal-footer" style="text-align: center;">
        <a class="btn btn-primary" href="<?= base_url("/Client/logout") ?>">Confirmer</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).on("click","#btnlogout",function(){
    $('#logoutModal').modal('show');
  })
</script>
</body>
</html>