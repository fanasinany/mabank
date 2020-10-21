<div class="card">
    <div class="card-header card-header-success text-center">
        <h4 class="card-title">Mon Compte</h4>
    </div>
    <div class="card-body">
        <div class="mb-3" style="margin-left: 850px;">
            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#versementModal">Versement</button>
            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#retraitModal">Retrait</button>
        </div>
        <div class="row">
            <div class="col-6">
                <h5><?php echo date("F j, Y, H:i");?></h5>
                <h5>Votre solde est de :</h5>
                  <h1 id="solde"></h1>
                  <h5>Ariary</h5>
            </div>
            <div class="col-6">
                <h5>Dernier transaction effectué</h5>
                <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-primary">
                        <th>Date</th>
                        <th>Etat</th>
                        <th>Montant(Ar)</th>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour le versement-->
<div class="modal fade" id="versementModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Versement d'argent</h6></strong>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label class="bmd-label-floating">Numero Identité Bancaire</label>
            <input id="nib" type="text" class="form-control" value="<?= htmlentities($nib) ?>" disabled>
        </div>
        <div class="form-group">
            <label class="bmd-label-floating">Montant(en Ariary)</label>
            <input id="montant-versement" type="number" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button id="btnverser" class="btn btn-primary">Valider</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal pour le retrait-->
<div class="modal fade" id="retraitModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Retrait d'argent</h6></strong>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label class="bmd-label-floating">Numero Identité Bancaire</label>
            <input id="nibr" type="text" class="form-control" value="<?= htmlentities($nib) ?>" disabled>
        </div>
        <div class="form-group">
            <label class="bmd-label-floating">Montant(en Ariary)</label>
            <input id="montant-retrait" type="number" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button id="btnretrait" class="btn btn-primary">Valider</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
      </div>
    </div>
  </div>
</div>

<script>

$(document).ready(function (){

  disableBtn("#btnverser", "#montant-versement");
  disableBtn("#btnretrait", "#montant-retrait");
  getSolde();
  getLastTransaction();
})

$(document).on("click","#btnverser", function(){
  $.ajax({
    type: "post",
    url: site_url + "/Client/Versement",
    data: {
      nib: $("#nib").val(),
      montant: $("#montant-versement").val()
    },
    success: function(){
      $("#versementModal").modal('toggle');
      $("#montant-versement").val("");
      swal("Versement reussi","","success")
      getSolde();
      getLastTransaction();
      
    },
    error: function(){
      swal("Error","Veuillez saisir un montant","error")
    }
  })
})

$(document).on("click","#btnretrait", function(){
  $.ajax({
    type: "post",
    url: site_url + "/Client/Retrait",
    data: {
      nib: $("#nibr").val(),
      montant: $("#montant-retrait").val()
    },
    success: function(){
      $("#retraitModal").modal('toggle');
      $("#montant-retrait").val("");
      swal("Retrait reussi","","success")
      getSolde();
      getLastTransaction();
    },
    error: function(data){
      if(data.status == 404){
        swal("Retrait impossible","Votre solde est insuffisant pour ce retrait","error")
      }
      else if(data.status == 500){
        swal("Error","Veuillez saisir un montant","error")
      }
    }
  })
})

function getSolde(){
  $.ajax({
    type: "get",
    url: site_url + "/Client/getSolde",
    success: function(data){
      $("#solde").html(data);
    }
  })
}

function getLastTransaction(){
  $.ajax({
    type: "get",
    url: site_url + "/Client/getLastTransaction",
    success: function(data){
      $("tbody").html(data);
    }
  })
}

function disableBtn(btn, input){
  $(btn).attr('disabled',true);
  $(input).keyup(function(){
    if($(this).val().length !=0)
      $(btn).attr('disabled', false);            
    else
      $(btn).attr('disabled',true);
  })
}
</script>