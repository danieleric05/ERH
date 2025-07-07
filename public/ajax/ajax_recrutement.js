var table1 = $('#contact-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('all.contact') }}",
    columns: [
        {data:'id', name:'id'},
        {data:'name', name:'name'},
        {data:'email', name:'email'},
        {data:'phone', name:'phone'},
        {data:'religion', name:'religion'},
        {data:'action', name:'action', orderable: false, searchable: false}
    ]
});



//Insert data by Ajax

$(function(){
    $('#modal-form form').validator().on('submit', function (e) {
        if (!e.isDefaultPrevented()){
            var id = $('#id').val();
            if (save_method == 'add') url = "{{ url('contact') }}";
            else url = "{{ url('contact') . '/' }}" + id;
            $.ajax({
                url : url,
                type : "POST",
                //data : $('#modal-form form').serialize(),
                data: new FormData($("#modal-form form")[0]),
                contentType: false,
                processData: false,
                success : function(data) {
                    $('#modal-form').modal('hide');
                    table1.ajax.reload();
                    swal({
                        title: "Good job!",
                        text: "You clicked the button!",
                        icon: "success",
                        button: "Great!",
                    });
                },
                error : function(data){
                    swal({
                        title: 'Oops...',
                        text: data.message,
                        type: 'error',
                        timer: '1500'
                    })
                }
            });
            return false;
        }
    });
});
//show single data ajax part here
function showData(id) {
    $.ajax({
        url: "{{ url('contact') }}" + '/' + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#single-data').modal('show');
            $('.modal-title').text(data.name +' '+'Informations');
            $('#contactid').text(data.id);
            $('#fullname').text(data.name);
            $('#contactemail').text(data.email);
            $('#contactnumber').text(data.phone);
            $('#creligion').text(data.religion);
        },
        error : function() {
            alert("Ghorar DIm");
        }
    });
}
//edit ajax request are here
function editForm(id) {
    save_method = 'edit';
    $('input[name=_method]').val('PATCH');
    $('#modal-form form')[0].reset();
    $.ajax({
        url: "{{ url('contact') }}" + '/' + id + "/edit",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#modal-form').modal('show');
            $('.modal-title').text('Edit Contact');
            $('#insertbutton').text('Update Contact');
            $('#id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#phone').val(data.phone);
            $('#religion').val(data.religion);
        },
        error : function() {
            alert("Nothing Data");
        }
    });
}
//delete ajax request are here
function deleteData(id){
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url : "{{ url('contact') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table1.ajax.reload();
                        swal({
                            title: "Delete Done!",
                            text: "You clicked the button!",
                            icon: "success",
                            button: "Done",
                        });
                    },
                    error : function () {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '1500'
                        })
                    }
                });
            } else {
                swal("Your imaginary file is safe!");
            }
        });

}