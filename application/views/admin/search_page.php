<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-3" id="form-nib">
        <div class="form-group">
            <label class="bmd-label-floating">Numéro d'identité bancaire</label>
            <input id="nib"  type="number" class="form-control">
        </div>
    </div>
    <div class="col-md-3" id="form-nom">
        <div class="form-group">
            <label class="bmd-label-floating">Nom ou prenom</label>
            <input id="nom"  type="text" class="form-control">
        </div>
    </div>
    <div class="col-md-2 mt-4">
        <button id="btnfind" class="btn btn-primary btn-sm">Chercher</button>
    </div>
</div>
<div id="resultat" class="container mt-2">
<div class="table-responsive">
    <table class="table">
        <thead class=" text-primary">
            <th>Nom</th>
            <th>Prenom</th>
            <th>Numéro d'identité bancaire</th>
            <th>Solde(Ar)</th>
            <th>Action</th>
        </thead>
        <tbody id="result">
        </tbody>
    </table>
    </div>
</div>

<!-- Modal pour le transaction -->
<div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Historique de transaction</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-primary">
                        <th>Date</th>
                        <th>Etat</th>
                        <th>Montant(Ar)</th>
                      </thead>
                      <tbody id="mdltr">
                      </tbody>
                    </table>
                </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal pour le modif -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Modification d'un profil</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="bdedit">
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $.ajax({
            type: "get",
            url: site_url + "Admin/listAllClient",
            success: function(data){
                $("tbody#result").html(data);
            }
    })
    //$("#resultat").hide();
    disableBtn("#btnfind", "#nom");
    disableBtn("#btnfind", "#nib");
})

$("#btnfind").on("click",function(){
        $.ajax({
            type: "get",
            url: site_url + "/Admin/ajaxSearch",
            data: {
                nom: $("#nom").val(),
                nib: $("#nib").val()
            },
            success: function(data){
                $("tbody#result").html(data);
                $("#resultat").show();
            },
            error: function(){

            }
        })
})

$( "#transactionModal" ).on('shown.bs.modal', function(e){
    var $id_client = $(e.relatedTarget).data("id");
    $.ajax({
    type: "get",
    url: site_url + "/Admin/getLastTransaction",
    data:{
      id: $id_client
    },
    success: function(data){
      $("tbody#mdltr").html(data);
    }
  })
});

$( "#editModal" ).on('shown.bs.modal', function(e){

    var $id_client = $(e.relatedTarget).data("id");
    $.ajax({
    type: "get",
    url: site_url + "/Admin/getClient",
    data:{
      id: $id_client
    },
    success: function(data){
      $("#bdedit").html(data);
      validate();
      $('#nomP, #prenom, #adresse, #phone, #naissance').keyup(validate);
    }
  })
});

$('#transactionModal').on('hidden.bs.modal', function (e) {
  $("tbody#mdltr").html("");
})

$(document).on("click","#btnenregistrer",function(){
  $.ajax({
    type: "post",
    url: site_url + "/Admin/editClient",
    data:{
      id: $("#id").val(),
      nom: $("#nomP").val(),
      prenom: $("#prenom").val(),
      naissance: $("#naissance").val(),
      adresse: $("#adresse").val(),
      phone: $("#phone").val()
    },
    success: function(){
      $("#editModal").modal('toggle');
      $("#resultat").hide();
      swal("Modification terminé","","success")
    },
    error: function(){
      swal("Erreur","Veuilllez remplir tout les champs","error")
    }
  })
})

function disableBtn(btn, input){
  $(btn).attr('disabled',true);
  $(input).keyup(function(){
    if($(this).val().length !=0)
      $(btn).attr('disabled', false);            
    else
      $(btn).attr('disabled',true);
  })
}

function validate(){
    if ($('#nomP').val().length   >   0   &&
        $('#prenom').val().length  >   0   &&
        $('#adresse').val().length  >   0   &&
        $('#phone').val().length  >   0   &&
        $('#naissance').val().length    >   0) {
        $("#btnenregistrer").prop("disabled", false);
    }
    else {
        $("#btnenregistrer").prop("disabled", true);
    }
}

</script>