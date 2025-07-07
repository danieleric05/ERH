<!-- Large Size -->
<div class="modal fade" id="Update_fonction" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="titeupdate"></h4>
            </div>
            <form action="{{ url('update/fonctions/updatefonctions') }}" method="POST" id="updatefonctions">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id" id="fonctionid">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Fonction</label>
                                <input placeholder="Fonction" id="elabel" name="label" type="text" class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Description de la fonction</label>
                                <textarea placeholder="Description de la fonction" class="form-control" id="edescription" name="description" rows="4"></textarea>
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