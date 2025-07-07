<!-- Large Size -->
<div class="modal fade" id="Update_niveauEtude" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="titeupdate"></h4>
            </div>
            <form action="{{ url('update/niveauEtude/updateniveauEtude') }}" method="POST" id="updateniveauEtude">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id" id="niveauEtudeid">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Niveau d'Etude</label>
                                <input placeholder="Pays" id="elabel" name="label" type="text" class="form-control" required="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-dark">Modifier</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                </div>
            </form>
        </div>
    </div>
</div>