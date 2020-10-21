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

<script>
    $(document).ready(function(){
        $.ajax({
            type: "get",
            url: site_url + "Admin/listAllClient",
            success: function(data){
                $("tbody#result").html(data);
            }
        })
    })
</script>