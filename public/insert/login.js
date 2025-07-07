$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function addForm_mission() {
    $('#largeModal_mission').modal('show');
}

function addForm_mdp() {
    $('#largeModal_mdp').modal('show');
}

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
                    swal("Great", "DonnÃ©es de la catÃ©gorie saisies avec succÃ¨s", "success");
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

    function getCustomerData(){
        var url = $("#getalldata").data("url");

        $.ajax({
            url: url,
            type: "get",
            dataType: "HTMl",
            success: function(response){
                $("#showAllDataHere").html(response);
            }
        })
    }


    // Edit
    $(document).on("click", "#edit", function(arg){
        arg.preventDefault();
        var id = $(this).data("id");
        var url = $(this).attr("href");

        $.ajax({
            url: url,
            data: {id:id},
            dataType:"JSON",
            type: "GET",
            success(response){
                $("#Update_categories").modal("show");
                $("#elabel").val(response.label);
                $("#categorieid").val(response.id);
                $("#titeupdate").text("Modifier " + response.label + "");
            }
        })

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
                swal("Deleted", "Le pays a Ã©tÃ© supprimÃ© avec success", "success");
                return reloedMoment();
            }
        })

    });

    $("#updatecategories").on("submit", function(arg){
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
                    swal("DonnÃ©es mis a jour avec succÃ¨es", "Success", "success");
                    $("#Update_categorie").modal("hide");
                    return reloedMoment();
                }
            },
            complete: function(){
                $(".load").fadeOut();
            }
        });

    });

});