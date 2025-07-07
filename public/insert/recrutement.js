$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

/*function addForm_declaration() {
    $('#largeModal_categorie').modal('show');
}*/

$(function(){
    $("#addcategorie").on("submit", function(e){
        e.preventDefault();
        var form = $(this);
        var url = form.attr("action");
        var type = form.attr("method");
        var data = form.serialize();

        $.ajax({

            url: url,
            data: data,
            type: type,
            dataType: "JSON",
            beforeSend: function(){
                $(".load").fadeIn();
            },
            success: function(data){
                if(data == "success"){
                    $("#largeModal_categorie").modal("hide");
                    swal("Great", "Données de la catégorie saisies avec succès", "success");
                    form[0].reset();
                    return reloedMoment();
                }
            },
            complete: function(){
                $(".load").fadeOut();
            },

        });

    });
    
    function reloedMoment() {
        setTimeout(function() {
            window.location.reload();
        }, 1000);
    }

    // Edit
    $(document).on("click", "#declaration_new", function(arg){

        arg.preventDefault();
        var id = $(this).data("id");
        var url = $(this).attr("href");

        $.ajax({
            url: url,
            data: {id:id},
            dataType:"JSON",
            type: "GET",
            success(response){
                $("#addForm_declaration_new").modal("show");
                $("#travailleurid").val(response.id);
            }
        })

    });

    // Edit
    $(document).on("click", "#reconduire_journalier", function(arg){

        arg.preventDefault();
        var id = $(this).data("id");
        var url = $(this).attr("href");

        $.ajax({
            url: url,
            data: {id:id},
            dataType:"JSON",
            type: "GET",
            success(response){
                $("#addForm_reconduireJournalier").modal("show");
                $("#travailid").val(response.id);
            }
        })

    });

    //
    jQuery("select#motif_absence").change(function(){
        var anbsence = jQuery('#motif_absence').val();

        if(anbsence == 6){
            jQuery('#mission').slideDown();
            jQuery('#hdebut').slideUp();
            jQuery('#hfin').slideUp();
        }else if(anbsence == 2) {
            jQuery('#hdebut').slideDown();
            jQuery('#hfin').slideDown();
            jQuery('#mission').slideUp();
        }else {
            jQuery('#hdebut').slideUp();
            jQuery('#hfin').slideUp();
            jQuery('#mission').slideUp();
        }
    });

    //AUTORISATIONS
    jQuery("select#sanction_applique").change(function(){
        var sanct = jQuery('#sanction_applique').val();

        if(sanct == 2){
            jQuery('#nombre_jour').slideDown();
        }else {
            jQuery('#nombre_jour').slideUp();
        }
    });

    //RECHERCHE
    jQuery("select#recherche").change(function(){
        var reche = jQuery('#recherche').val();

        if(reche == 1){
            jQuery('#idunitechoix').slideDown();
			jQuery('#date_debut').slideUp();
			jQuery('#date_fin').slideUp();
        }else if(reche == 3){
            jQuery('#date_debut').slideDown();
            jQuery('#date_fin').slideDown();
			jQuery('#idunitechoix').slideUp();
        }else if(reche == 4){
            jQuery('#date_debut').slideDown();
            jQuery('#date_fin').slideDown();
			jQuery('#idunitechoix').slideUp();
        }else {
            jQuery('#idunitechoix').slideUp();
			jQuery('#date_debut').slideUp();
			jQuery('#date_fin').slideUp();
        }
    });

    jQuery("select#type_id").change(function(){
        var type = jQuery('#type_id').val();

        if(type == 1){
            jQuery('#recherchechoix').slideUp();
            jQuery('#idunitechoix').slideUp();
        }else {
            jQuery('#recherchechoix').slideDown();
            jQuery('#idunitechoix').slideDown();
        }
    });

    jQuery("select#idtype_contrat").change(function(){
        var typecont = jQuery('#idtype_contrat').val();

        if( (typecont == 1) || (typecont == 2) || (typecont == 3) || (typecont == 5) || (typecont == 6) ){
            jQuery('#newcontrat_embauche').slideDown();
            jQuery('#newcontrat_fin').slideDown();
            jQuery('#newcontrat_embauche_journalier').slideUp();
        }else if(typecont == 4) {
            jQuery('#newcontrat_embauche_journalier').slideDown();
            jQuery('#newcontrat_embauche').slideUp();
            jQuery('#newcontrat_fin').slideUp();
        }
		
    });

    jQuery("select#actionid").change(function(){

        var act = jQuery('#actionid').val();

        if(act == 3){
            jQuery('#motif').slideDown();
            jQuery('#numcnps').slideUp();
            jQuery('#uniteChoix').slideUp();
            jQuery('#equipeChoix').slideUp();
            jQuery('#uniteDepart').slideUp();
        }else if(act == 5){
            jQuery('#numcnps').slideDown();
            jQuery('#motif').slideUp();
            jQuery('#uniteChoix').slideUp();
            jQuery('#equipeChoix').slideUp();
            jQuery('#uniteDepart').slideUp();
        }else if(act == 8){
            jQuery('#uniteChoix').slideDown();
            jQuery('#equipeChoix').slideDown();
            jQuery('#uniteDepart').slideDown();
            jQuery('#motif').slideUp();
            jQuery('#numcnps').slideUp();
        }else {
            jQuery('#motif').slideUp();
            jQuery('#numcnps').slideUp();
            jQuery('#uniteChoix').slideUp();
            jQuery('#equipeChoix').slideUp();
            jQuery('#uniteDepart').slideUp();
        }

    });

    // Delete Data
    $(document).on("click", "#deletePays", function(arg){
        arg.preventDefault();
        var id = $(this).data("id");
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            data: {id:id},
            type: "GET",
            dataType: "JSON",
            success(response){
                swal("Deleted", "Le pays a été supprimé avec success", "success");
                return reloedMoment();
            }
        })

    });

    $("#updtdeclarations").on("submit", function(arg){
        arg.preventDefault();
        var form =$(this);
        var url = form.attr("action");
        var type = form.attr("method");
        var data = form.serialize();

        $.ajax({
            url: url,
            type: type,
            dataType: "JSON",
            data: data,
            beforeSend: function(){
                $(".load").fadeIn();
            },
            success: function(response){
                if(response == "success"){
                    swal("Données mis a jour avec succèes", "Success", "success");
                    $("#addForm_declaration_new").modal("hide");
                    return reloedMoment();
                }
            },
            complete: function(){
                $(".load").fadeOut();
            }
        });

    });

});