<!-- Large Size -->
<div class="modal fade" id="largeModal_equipe" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="titre">Ajouter une equipe</h4>
            </div>
            <form action="{{ url('add/equipes/addequipes') }}" method="POST">
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
                                <label>Equipe</label>
                                <input placeholder="Nom de l'équipe" name="label" type="text" class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Liste des unités</label>
                                <select name="uniteid" class="form-control" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($unites as $liste)
                                        <option value="{{ $liste->id }}">{{ $liste->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Chef d'equipe</label>
                                <select name="chefEquipeid" class="form-control" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($Travailleur as $liste)
                                        <option value="{{ $liste->id }}">{{ $liste->nom }} - {{ $liste->prenom }} - {{ $liste->matricule }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Un mot sur l'equipe</label>
                                <textarea placeholder="Un mot sur l'equipe" class="form-control" name="description" rows="4"></textarea>
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