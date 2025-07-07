<!-- Large Size -->
<div class="modal fade" id="Update_recrutement" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="titeupdate"></h4>
            </div>
            <form action="{{ url('update/departements/updatedepartements') }}" method="POST" id="updatedepartements">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id" id="departementid">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Departement</label>
                                <input placeholder="Departement" id="dlabel" name="label" type="text" class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="duniteid" class="control-label">Unité</label>
                                <select name="uniteid" id="duniteid" class="form-control">
                                    <option value="">-DEOULER-</option>
                                    @foreach($unites as $liste)
                                        <option value="{{ $liste->id }}">{{ $liste->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Description du departement</label>
                                <textarea placeholder="Description du departement" class="form-control" id="ddescription" name="description" rows="4"></textarea>
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