<!-- Large Size -->
<div class="modal fade" id="largeModal_mission" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="title" id="largeModalLabel">Ajouter les informations de votre mission</h4>
            </div>
            <form action="{{ url('add/mission/addmission') }}" method="POST" id="addmission">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <div class="modal-body">

                    <div class="row">

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Liste des employés</label>
                                <input placeholder="Categorie" type="text" name="label" class="form-control" required="">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Date debut</label>
                                <input placeholder="Categorie" type="date" name="label" class="form-control" required="">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Date fin de mission</label>
                                <input placeholder="Categorie" type="date" name="label" class="form-control" required="">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Villes ou pays de la mission</label>
                                <input placeholder="Indiquez les villes ou pays de la mission" type="text" name="label" class="form-control" required="">
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