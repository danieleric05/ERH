<!-- Large Size -->
<div class="modal fade" id="Update_unites" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="titeupdate"></h4>
            </div>
            <form action="{{ url('update/unites/updateunites') }}" method="POST" id="updateunites">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id" id="uniteid">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Unite</label>
                                <input placeholder="Unite" id="elabel" name="label" type="text" class="form-control" required="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Modifier</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                </div>
            </form>
        </div>
    </div>
</div>