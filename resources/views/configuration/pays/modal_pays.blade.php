<!-- Large Size -->
<div class="modal fade" id="largeModal_pays" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Ajouter un pays</h4>
            </div>
            <form action="{{ url('add/pays/addpays') }}" method="POST" id="addpays">
                {{csrf_field()}}
                <div class="modal-body">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Pays</label>
                                <input placeholder="Pays" type="text" name="label" class="form-control" required="">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                </div>
            </form>
        </div>
    </div>
</div>