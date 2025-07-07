<!-- Large Size -->
<div class="modal fade" id="largeModal_fonction" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Ajouter une fonction</h4>
            </div>
            <form action="{{ url('add/fonctions/fonctions') }}" method="POST">
                {{csrf_field()}}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Identifiant</label>
                                <input placeholder="Identifiant" name="id" type="number" class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Fonction</label>
                                <input placeholder="Fonction" name="label" type="text" class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Description de la fonction</label>
                                <textarea placeholder="Description de la fonction" class="form-control" name="description" rows="4"></textarea>
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