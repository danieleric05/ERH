$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function addForm_fonction() {
    $('#largeModal_fonction').modal('show');
}

$(function(){
    $("#addfonctions").on("submit", function(e){
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
                    $("#largeModal_fonction").modal("hide");
                    swal("Great", "Données de la fonction saisies avec succès", "success");
                    form[0].reset();
                    return reloedMoment();
                }
            },

            error: function(data){

                console.log( data );
                if(data == "error"){
                    $("#largeModal_fonction").modal("hide");
                    swal("Great", "Doublon sur l'identifiant", "error");
                    form[0].reset();
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
                $("#Update_fonction").modal("show");
                $("#eid").val(response.id);
                $("#elabel").val(response.label);
                $("#edescription").val(response.description);
                $("#fonctionid").val(response.id);
                $("#titeupdate").text("Modifier " + response.label + "");
            }
        })

    });

    // Delete Data
    $(document).on("click", "#deleteFonction", function(arg){
        arg.preventDefault();
        var id = $(this).data("id");
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            data: {id:id},
            type: "GET",
            dataType: "JSON",
            success(response){
                swal("Deleted", "La fonction a été supprimé avec success", "success");
                return reloedMoment();
            }
        })

    });

    $("#updatefonctions").on("submit", function(arg){
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
                    $("#Update_fonction").modal("hide");
                    return reloedMoment();
                }
            },
            complete: function(){
                $(".load").fadeOut();
            }
        });

    });

    $(document).on("click", ".pagination li a", function(e){
        e.preventDefault();
        var page = $(this).attr("href");
        var pagenumber = page.split("?page=")[1];
        return getPagination(pagenumber);
    });

    function getPagination(pagenumber){
        var geturl = $("#getalldatabypagination").data("url");
        var url = geturl+"?page=" + pagenumber;

        $.ajax({
            url: url,
            type: "GET",
            dataType: "HTML",
            success: function(response){
                $("#showAllDataHere").html(response);
            }
        });
    }

});