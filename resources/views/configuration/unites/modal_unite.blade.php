<!-- Large Size -->
<div class="modal fade" id="largeModal_unites" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="titre">Ajouter une equipe</h4>
            </div>
            <form action="{{ url('add/unites') }}" method="POST">
                {{csrf_field()}}
                <div class="modal-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Identifiant</label>
                                <input placeholder="Identifiant" name="id" type="text" class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Unité</label>
                                <input placeholder="Nom de l'Unité" name="label" type="text" class="form-control" required="">
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