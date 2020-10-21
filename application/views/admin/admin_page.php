<div class="col-md-10 ml-5">
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title">Ajout d'un nouveau client</h4>
            <p class="card-category">Remplissez chaque champ</p>
        </div>
        <div class="card-body">
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Nom</label>
                        <input id="nom" type="text" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Prenom</label>
                        <input id="prenom" type="text" class="form-control">
                    </div>
                </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                          <label class="bmd-label-floating">Date de naissance</label>
                          <input id="naissance"  type="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                          <label class="bmd-label-floating">Sexe</label>
                          <select id="sexe" class="form-control selectpicker" data-style="btn btn-link">
                            <option>Homme</option>
                            <option>Femme</option>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Adresse</label>
                          <input id="adresse" type="text" class="form-control">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="bmd-label-floating">Téléphone</label>
                            <input id="phone" type="number" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Numero d'identité bancaire</label>
                          <input id="input-nib" type="int" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                            <div class="form-group">
                            <label class="bmd-label-floating">Mot de passe</label>
                            <input id="password" type="password" class="form-control">
                            </div>
                    </div>
                    <div class="col-md-6">
                            <div class="form-group">
                            <label class="bmd-label-floating">Retapez le mot de passe</label>
                            <input id="password-deux" type="password" class="form-control">
                            </div>
                    </div>
                </div>
                <button id="btnvalider"class="btn btn-primary" style="margin-left: 720px;">Valider</button>
            </div>
        </div>
    </div>
</div>
<script>

    $(document).ready(function (){
        validate();
        $('#nom, #prenom, #adresse, #password, #password-deux, #phone, #naissance').keyup(validate);
    });

    $(document).on("focus","#input-nib", function(){
        $.ajax({
            type: 'get',
            url: site_url + '/Admin/generateNIB',
            success: function(data){
                $("#input-nib").val(data);
                $("#input-nib").attr('disabled',true);
            }
        })
    })

    $(document).on("click","#btnvalider", function(){
        $.ajax({
            type: 'post',
            url: site_url + 'Admin/addClient',
            data:{
                nom: $("#nom").val(),
                prenom: $("#prenom").val(),
                naissance: $("#naissance").val(),
                sexe: $("#sexe").val(),
                adresse: $("#adresse").val(),
                nib: $("#input-nib").val(),
                phone: $("#phone").val(),
                password: $("#password").val(),
                password_deux: $("#password-deux").val(),
            },
            success: function(){
                swal("Client ajouté aves success","", "success");
                $("#nom").val("");
                $("#prenom").val("");
                $("#naissance").val("");
                $("#sexe").val("");
                $("#adresse").val("");
                $("#input-nib").val("");
                $("#input-nib").removeAttr("disabled");
                $("#phone").val("");
                $("#password").val("");
                $("#btnvalider").prop("disabled", true);
            },
            error: function(data){
                if(data.status == 404){
                    swal("Erreur", "Veuillez remplir tous les champs", "error");
                }
                else if(data.status == 500){
                    swal("Erreur", "Mot de passe saisies indifferents", "error");
                }
            }
        })
    })

    function validate(){
        if ($('#nom').val().length   >   0   &&
            $('#prenom').val().length  >   0   &&
            $('#adresse').val().length  >   0   &&
            $('#password').val().length  >   0   &&
            $('#password-deux').val().length  >   0   &&
            $('#phone').val().length  >   0   &&
            $('#naissance').val().length    >   0) {
            $("#btnvalider").prop("disabled", false);
        }
        else {
            $("#btnvalider").prop("disabled", true);
        }
    }
</script>