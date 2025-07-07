$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function addForm_recrutement() {
    $('#largeModal_recrutement').modal('show');
}

$(function(){
    $("#adddepartements").on("submit", function(e){
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
                    $("#largeModal_recrutement").modal("hide");
                    swal("Great", "Successfully Customer Data Inputed", "success");
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


    // View Data
    $(document).on("click", "#view", function(e){
        e.preventDefault();
        var id = $(this).data("id");
        var url = $(this).attr("href");

        $.ajax({
            url: url,
            data: {id:id},
            type: "GET",
            dataType: "JSON",
            success: function(response){
                if($.isEmptyObject(response) != null){
                    $("#ViewCustomer").modal("show");
                    $("#customername").text(response.name + "'s Data");
                    $(".cname").text("Name: " + response.name);
                    $(".cphone").text("Phone: " + response.phone);
                    $(".cemail").text("Email: " + response.email);
                    $(".cdistrict").text("District: " + response.district);
                }
            }
        });

    });


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
                $("#Update_recrutement").modal("show");
                $("#dlabel").val(response.label);
                $("#duniteid").val(response.uniteid);
                $("#ddescription").val(response.description);
                $("#departementid").val(response.id);
                $("#titeupdate").text("Modifier " + response.label + "");
            }
        })

    });

    // Delete Data
    $(document).on("click", "#deleteDeparte", function(arg){
        arg.preventDefault();
        var id = $(this).data("id");
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            data: {id:id},
            type: "GET",
            dataType: "JSON",
            success(response){
                swal("Deleted", "Le departement a été supprimé avec success", "success");
                return reloedMoment();
            }
        })

    });

    $("#updatedepartements").on("submit", function(arg){
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
                    $("#Update_recrutement").modal("hide");
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