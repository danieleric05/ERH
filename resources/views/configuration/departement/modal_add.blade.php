<!-- Large Size -->
<div class="modal fade" id="largeModal_recrutement" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Ajouter un departement</h4>
            </div>
            <form action="{{ url('add/departements/adddepartements') }}" method="POST">
                {{csrf_field()}}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Identifiant</label>
                                <input placeholder="Identifiant" name="id" type="number" class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Departement</label>
                                <input placeholder="Departement" name="label" type="text" class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone-ex" class="control-label">Unité</label>
                                <select name="uniteid" class="form-control">
                                    <option value="">-DEROULER-</option>
                                    @foreach($unites as $liste)
                                    <option value="{{ $liste->id }}">{{ $liste->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Description du departement</label>
                                <textarea placeholder="Description du departement" class="form-control" name="description" rows="4"></textarea>
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